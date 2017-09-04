<?php
/**
 * Process IDN messages from PayPal.
 * @link https://github.com/damieng/wordpressbits/wp-content-subscription
 * @copyright 2013-2014 Damien Guard.
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2
 */

require_once 'functions.php';
require_once '../../../wp-load.php';

if (!function_exists('curl_init'))
  die('cURL is not installed!');

$options = get_option('subscription_options');
$d = get_post_associative();
$debug = $options['debug'] == 'on';
$sandbox = isset($d['test_ipn']) && strcmp($d['test_ipn'], '1') == 0;

stop_if_not_paypal();
callback_paypal(create_paypal_callback_fields());

process_message();

/**
 * Stops processing the PayPal message if the incoming message is
 * from the sandbox and the sandbox option is not enabled or if the
 * receiver id and email from PayPal do not match the plugin settings.
 *
 * @return void
 */
function stop_if_receiver_mismatch()
{
    global $options, $d, $sandbox;

    $sandboxEnabled = $options['paypal-sandbox'] == 'on';
    if (!$sandboxEnabled && $sandbox)
        stop('PayPal sandbox message received but sandbox is disabled');

    $expectedEmail = $sandbox ? $options['paypal-sandbox-email'] : $options['paypal-email'];
    $expectedId = $sandbox ? $options['paypal-sandbox-id'] : $options['paypal-id'];

    stop_if_mismatch($d['receiver_email'], $expectedEmail, 'email');
    stop_if_mismatch($d['receiver_id'], $expectedId, 'ID');
}

/**
 * Stops processing if the received value is different from the setting.
 *
 * @param string $received The value received from PayPal to be compared.
 * @param string $setting The plugin configuration setting to be compared with.
 * @param string $field The name of the field being compared for the log message.
 * @return void
 */
function stop_if_mismatch($received, $setting, $field)
{
    if (strcmp($received, $setting) == 0) return;
    stop("received $field in message '$received' does not match setting '$setting'");
}

/**
 * Stops processing and writes the reason processing was stopped to the error_log if
 * debugging is enabled.
 *
 * @param string $reason The reason processing was stopped.
 * @return void
 */
function stop($reason)
{
    global $debug;
    if ($debug)
        error_stamp("Stopping because $reason");
    exit;
}

/**
 * Process the message from PayPal by determining its transaction type
 * and calling the appropriate function to process it. If it is not a supported
 * transaction type then log the ignored type to error_log.
 *
 * @return void
 */
function process_message()
{
    global $d;
    $txn_type = $d['txn_type'];
    switch ($txn_type) {
        case 'subscr_payment':	return subscription_payment();
        case 'subscr_signup':	return subscription_signup();
        case 'web_accept':		return web_accept();
    }

    stop("transaction type '$txn_type' is ignored");
}

/**
 * Handle the subscr_signup PayPal IDN message by creating the user if necessary,
 * setting the subscription length and activating the user if they are now complete.
 * The create and activation steps are necessary as two messages come from PayPal
 * and may be received in any order.
 *
 * @return void
 */
function subscription_signup()
{
    global $d;
    $user = get_or_create_user($d['payer_email'], $d['first_name'], $d['last_name']);
    $subs_length = paypal_period_to_php_interval($d['period3']);
    update_user_meta($user->id, 'subs_length', $subs_length);
    activate_user_if_complete($user);
}

/**
 * Handle the subscr_payment PayPal IDN message by creating the user if necessary,
 * setting the subscription plan and activating the user if they are now complete.
 * The create and activation steps are necessary as two messages come from PayPal
 * and may be received in any order.
 *
 * @return void
 */
function subscription_payment()
{
    $user = common_payment_setup();

    global $d;    
    update_user_meta($user->id, 'subs_plan', $d['item_number']);

    activate_user_if_complete($user);
}

/**
 * Handle the web_accept PayPal IDN message by creating the user if necessary,
 * setting the subscription plan and activating the user if they are now complete.
 * This message occurs if special buy buttons are being used instead of subscription
 * buttons. Because subscription period is not contained it is necessary to put both
 * the textual plan name and the period in the item_number button details in the
 * right format, e.g. "annual,Y 1" or "quarterly,M 3".
 *
 * @return void
 */
function web_accept()
{
    $user = common_payment_setup();

    global $d;
    $planDetails = explode(',', $d['item_number']);
    update_user_meta($user->id, 'subs_plan', $planDetails[0]);
    $subs_length = paypal_period_to_php_interval($planDetails[1]);
    update_user_meta($user->id, 'subs_length', $subs_length); 
    
    activate_user_if_complete($user);
}

/**
 * Setup the user from the details contained within the message.
 * Used by both subscription_payment and web_accept to setup user with transaction
 * details.
 *
 * @return WP_User object containing newly created user. 
 */
function common_payment_setup()
{
    stop_if_receiver_mismatch();

    global $d;
    $user = get_or_create_user($d['payer_email'], $d['first_name'], $d['last_name']);
    update_user_meta($user->id, 'subs_id', $d['txn_id']);
    update_user_meta($user->id, 'subs_gateway', 'PayPal');
    
    return $user;
}

/**
 * Convert the PayPal subscription period format into the PHP interval period so that
 * we can store it and use it to calculate new end dates when subscriptions are renewed.
 *
 * @param string $period PayPal subscription period (e.g. '3 M' for 3 months)
 * @return string PHP interval equivalent (e.g. 'P3M' for 3 months)
 */
function paypal_period_to_php_interval($period)
{
    return 'P' . str_replace(' ', '', $period);
}

/**
 * It is necessary to call back PayPal to notify that the IDN message was received.
 * Create a list of the parameters we received from PayPal with the addition of
 * cmd=notify-validate to achieve this.
 *
 * @return string Query parameters neccessary to notify PayPal we received the message.
 */
function create_paypal_callback_fields()
{
    global $d;
    $req = 'cmd=_notify-validate';
    $get_magic_quotes_exists = function_exists('get_magic_quotes_gpc');
    foreach ($d as $key => $value) {
       if ($get_magic_quotes_exists && get_magic_quotes_gpc() == 1)
        $value = stripcslashes($value);
       $req .= "&$key=" . urlencode($value);
    }

    return $req;
}

/**
 * Create a cURL request object to POST to PayPal with the fields forming the body.
 *
 * @param string $fields Fields in the format key1=value1&key2=value2 to use as post body.
 * @return resource cURL A cURL resource capable of performing the URL request.
 */
function create_curl_request($fields)
{
    global $debug, $sandbox;
    $url = $sandbox
        ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
        : 'https://www.paypal.com/cgi-bin/webscr';

    $ch = curl_init($url);
    if ($ch == FALSE)
        stop("Unable to curl_init $url");

    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

    return $ch;
}

/**
 * Call back PayPal with the given fields via cURL to validate IPN messages and stop if
 * validation was not successful.
 *
 * @param string $fields Fields in the format key1=value1&key2=value2 to use as post body.
 * @return void
 */
function callback_paypal($fields)
{
    global $debug, $d;
    $request = create_curl_request($fields);
    $response = curl_exec($request);

    if (curl_errno($request) != 0) {
        $error = curl_error($request);
        if ($debug)
            error_stamp("Can not connect to PayPal to validate IPN message: $error");
        curl_close($request);
        exit;
    } else {
        list($headers, $response) = explode("\r\n\r\n", $response, 2);
    }

    $txn_type = $d['txn_type'];
    $verified = strcmp(trim($response), 'VERIFIED') == 0;
    if ($debug || !$verified)
        error_stamp("$txn_type IDN response '$response' for $fields");

    curl_close($request);

    if (!$verified)
        stop('PayPal transaction was not verified');
}

/**
 * Stop processing if the request is not believed to have originated from PayPal.
 * e.g. A user requested the URL from their browser.
 *
 * @return void
 */
function stop_if_not_paypal()
{
    global $d;
    if (isset($d['txn_type']))
        return;

    display_stop_message();
    stop('No transaction found.');
    exit;
}

/**
 * Display a stop message on the users browser.
 *
 * @return void
 */
function display_stop_message() { ?>
    <h1>WP-Content-Subscription</h1>
    <p>This URL is for PayPal and should be specified in your
    <a href="https://developer.paypal.com/webapps/developer/docs/classic/ipn/gs_IPN/">IDN</a> configuration.</p>
<?php
}
?>
