<?php
/**
  * Reusable functions for wp-content-subscription.
  * @link https://github.com/damieng/wordpressbits/wp-content-subscription
  * @copyright 2013-2014 Damien Guard.
  * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2
  */

/**
 * Get the raw post body and return it as an associative
 * array of key/value pairs.
 *
 * @return array Associative array of post key/value pairs.
 */
function get_post_associative()
{
    $raw = file_get_contents('php://input');
    $a = array();
    foreach (explode('&', $raw) as $pair) {
        $pair = explode('=', $pair);
        if (count($pair) == 2)
        $a[$pair[0]] = urldecode($pair[1]);
    }

    return $a;
}

/**
 * Write a timestamped error message to the error_log.
 *
 * @param string $message Message to be written to the error_log.
 * @return void
 */
function error_stamp($message)
{
    error_log(date('[Y-m-d H:i e] ') . $message . PHP_EOL, 3, 'error_log');
}

/**
 * Determine if a subscription is active for a given email address.
 *
 * @param string $email Email address of the user to check.
 * @return boolean true if the user has active subscription, false if not.
 */
function is_active_subscriber($email)
{
    $user = get_user_by('email', $email);
    if (!$user)
        return false;
    return subscription_active($user->ID);
}

/**
 * Get a user from WordPress and create them if they do not already exist.
 *
 * @param string $email Email address of the user to lookup or create.
 * @param string $first_name First name of the user to create.
 * @param string $last_name Last name of the user to create.
 * @return WP_User The WordPress User object for this email address.
 */
function get_or_create_user($email, $first_name, $last_name)
{
    $user = get_user_by('email', $email);
    $action = !$user ? 'Created' : 'Updated';

    if ($action == 'Created')
        $user = setup_new_user($email, $first_name, $last_name);

    return $user;
}

/**
 * Setup a new WordPress user given the details provided.
 *
 * @param string $email Email address of the user to create.
 * @param string $first_name First name of the user to create.
 * @param string $last_name Last name of the user to create.
 * @return WP_User The WordPress User object for the new user.
 */
function setup_new_user($email, $first_name, $last_name)
{
    $password = wp_generate_password();

    $user_id = wp_insert_user(array(
        'user_login' => $email,
        'user_pass' => $password,
        'user_email' => $email,
        'nickname' => $first_name,
        'first_name' => $first_name,
        'last_name' => $last_name
    ));

    wp_new_user_notification($user_id, $password);

    return get_user_by('id', $user_id);
}

/**
 * Checks to see if the user has a subscription id and length and if so
 * activates the users subscription.
 *
 * @param WP_User $user WordPress user object to check and activate if necessary.
 * @return void
 */
function activate_user_if_complete($user)
{
    $subs_length = get_user_meta($user->id, 'subs_length');
    if (!$subs_length) return;

    $subs_id = get_user_meta($user->id, 'subs_id');
    if (!$subs_id) return;

    start_subscription_if_not_already_started($user);
    update_subscription_end_date($user);
}

/**
 * Recalculates and updates the subscription end date for a given user
 * based on todays date and the length of the subscription period.
 *
 * @param WP_User $user WordPress user object to recalculate.
 * @return void
 */
function update_subscription_end_date($user)
{
    $now = new DateTime();
    $subs_length = get_user_meta($user->id, 'subs_length', true);
    $interval = new DateInterval($subs_length);
    $subs_end = $now->add($interval)->format('Y-m-d');
    update_user_meta($user->id, 'subs_end', $subs_end);
}

/** 
 * Starts the users subscription if it is not already started.
 *
 * @param WP_User $user WordPress user to start the subscription for.
 * @return void
 */ 
function start_subscription_if_not_already_started($user)
{
    $subs_start = get_user_meta($user->id, 'subs_start');
    if (!$subs_start)
        start_subscription($user);
}

/**
 * Starts the users subscription from today.
 *
 * @param WP_User $user WordPress user to start the subscription for.
 * @return void
 */
function start_subscription($user)
{
    $now = new DateTime();
    update_user_meta($user->id, 'subs_start', $now->format('Y-m-d'));
}

/**
 * Determine if the users subscription has ended.
 *
 * @param integer $user_id User ID of the WordPress user to check.
 * @return boolean true if the subscription has ended, false if it is active.
 */
function subscription_ended($user_id)
{
    $now = new DateTime();
    $end = get_user_meta($user_id, 'subs_end', true);

    return ($end != '') && (new DateTime($end) < $now);
}

/**
 * Determine if the users subscription has started.
 *
 * @param integer $user_id User ID of the WordPress user to check.
 * @return boolean true if the subscription has start, false if it has not.
 */
function subscription_started($user_id)
{
    $now = new DateTime();
    $start = get_user_meta($user_id, 'subs_start', true);

    return ($start == '') || (new DateTime($start) <= $now);
}

/**
 * Determine if the users subscription is active (has started and not ended).
 *
 * @param integer $user_id User ID of the WordPress user to check.
 * @return boolean true if the subscription is active, false if it is not.
 */
function subscription_active($user_id)
{
    return subscription_started($user_id) && !subscription_ended($user_id);
}