<?php
/**
  * Setup the administrative config options for the plugin in wp-admin.
  * @link https://github.com/damieng/wordpressbits/wp-content-subscription
  * @copyright 2013-2014 Damien Guard.
  * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2
  */

/**
 * Provides the plugin configuration options within wp-admin.
 */
class ContentSubscriptionSetting
{
    /**
     * Currently configured plugin settings.
     * @var array
     */
    private $options;

    /**
     * Title of the plugin to display in wp-admin.
     * @var string
     */
    private $title = 'Content Subscription';

    /**
     * Constructor that registers the plugin into wp-admin.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Add the plugin configuration page to wp-admin.
     *
     * @return void
     */
    public function add_plugin_page()
    {
        add_options_page(
            $this->title . ' Settings',
            $this->title,
            'manage_options',
            'content-subscription',
            array($this, 'create_admin_page')
        );
    }

    /**
     * Renders the administration page with the plugin options.
     *
     * @return void
     */
    public function create_admin_page()
    {
        $this->options = get_option('subscription_options'); ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2><?php echo $this->title; ?> Settings</h2>
            <form method="post" action="options.php">
                <?php settings_fields('subscription_group');
                do_settings_sections('content-subscription');
                submit_button(); ?>
            </form>
        </div><?php
    }

    /**
     * Registers the plugin settings with WordPress.
     *
     * @return void
     */
    public function page_init()
    {
        register_setting(
            'subscription_group',
            'subscription_options',
            array($this, 'sanitize')
        );

        add_settings_section(
            'subscription-general',
            'General Settings',
            array($this, 'section_callback'),
            'content-subscription'
        );

        add_settings_field(
            'debug',
            'Debugging',
            array($this, 'debug_callback'),
            'content-subscription',
            'subscription-general',
            array('label_for' => 'debug')            
        );
     
        $this->page_init_stripe();
        $this->page_init_paypal();
    }
    
    /**
     * Registers the Stripe-specific plugin settings with WordPress.
     *
     * @return void
     */ 
    public function page_init_stripe()
    {
        add_settings_section(
            'subscription-stripe-ids',
            'Stripe IDs',
            array($this, 'section_callback'),
            'content-subscription'
        );

        add_settings_field(
            'strip-test-id',
            'Test Publisher ID',
            array($this, 'stripe_test_id_callback'),
            'content-subscription',
            'subscription-stripe-ids',
            array('label_for' => 'stripe-test-id')
        );

        add_settings_field(
            'stripe-live-id',
            'Live Publisher ID',
            array($this, 'stripe_live_id_callback'),
            'content-subscription',
            'subscription-stripe-ids',
            array('label_for' => 'stripe-live-id')
        );
    }
    
    /**
     * Registers the PayPal-specific plugin settings with WordPress.
     *
     * @return void
     */ 
    public function page_init_paypal()
    {
        /* PayPal Sandbox settings */
        
        add_settings_section(
            'subscription-paypal-sandbox',
            'Paypal Sandbox',
            array($this, 'section_callback'),
            'content-subscription'
        );

        add_settings_field(
            'paypal-sandbox',
            'Sandbox Enabled',
            array($this, 'paypal_sandbox_callback'),
            'content-subscription',
            'subscription-paypal-sandbox',
            array('label_for' => 'paypal-sandbox')
        );

        add_settings_field(
            'paypal-sandbox-email',
            'Sandbox Email',
            array($this, 'paypal_sandbox_email_callback'),
            'content-subscription',
            'subscription-paypal-sandbox',
            array('label_for' => 'paypal-sandbox-email')
        );

        add_settings_field(
            'paypal-sandbox-id',
            'Sandbox ID',
            array($this, 'paypal_sandbox_id_callback'),
            'content-subscription',
            'subscription-paypal-sandbox',
            array('label_for' => 'paypal-sandbox-id')
        );
        
        /* PayPal General settings */

        add_settings_section(
            'subscription-paypal',
            'Paypal Settings',
            array($this, 'section_callback'),
            'content-subscription'
        );

        add_settings_field(
            'paypal-email',
            'Receiver Email',
            array($this, 'paypal_email_callback'),
            'content-subscription',
            'subscription-paypal',
            array('label_for' => 'paypal-email')
        );

        add_settings_field(
            'paypal-id',
            'Receiver ID',
            array($this, 'paypal_id_callback'),
            'content-subscription',
            'subscription-paypal',
            array('label_for' => 'paypal-id')
        );
    }

    /**
     * Collect the configuration settings saved by the user and safely
     * combine them together into an associative array of setting.
     *
     * @param array $input Associative array of configuration settings to be sanitized.
     * @return array Associative array of safely sanitized configuration settings.
     */
    public function sanitize($input)
    {
        $fields = array('debug',
            'stripe-test-id', 'stripe-live-id',
            'paypal-sandbox', 'paypal-sandbox-email', 'paypal-sandbox-id',
            'paypal-email', 'paypal-id');

        $o = array();

        foreach ($fields as $field) {
            if (isset($input[$field]))
                $o[$field] = sanitize_text_field($input[$field]);
        }

        return $o;
    }

    /**
     * Callback for rendering sections of the settings.
     *
     * @return void
     */
    public function section_callback()
    {
    }

    /**
     * Callback for rendering the debug setting that renders it as a check box.
     *
     * @return void
     */
    public function debug_callback()
    {
        $this->checkbox('debug');
    }
    
    /**
     * Callback for rendering the Stripe test ID setting that renders it as a text box.
     *
     * @return void
     */
    public function stripe_test_id_callback()
    {
        $this->text('stripe-test-id');
    }

    /**
     * Callback for rendering the Strip live ID setting that renders it as a text box.
     *
     * @return void
     */
    public function stripe_live_id_callback()
    {
        $this->text('stripe-live-id');
    }

    /**
     * Callback for rendering the PayPal sandbox setting that renders it as a check box.
     *
     * @return void
     */
    public function paypal_sandbox_callback()
    {
        $this->checkbox('paypal-sandbox');
    }

    /**
     * Callback for rendering the PayPal sandbox email setting that renders it as a text box.
     *
     * @return void
     */
    public function paypal_sandbox_email_callback()
    {
        $this->text('paypal-sandbox-email');
    }

    /**
     * Callback for rendering the PayPal sandbox ID setting that renders it as a text box.
     *
     * @return void
     */
    public function paypal_sandbox_id_callback()
    {
        $this->text('paypal-sandbox-id');
    }

    /**
     * Callback for rendering the PayPal receiver email setting that renders it as a text box.
     *
     * @return void
     */
    public function paypal_email_callback()
    {
        $this->text('paypal-email');
    }

    /**
     * Callback for rendering the PayPal receiver id setting that renders it as a text box.
     *
     * @return void
     */
    public function paypal_id_callback()
    {
        $this->text('paypal-id');
    }

    /**
     * Render a check box for the given id.
     *
     * @param string $id ID of the configuration setting.
     * @return string HTML of the rendered check box.
     */
    private function checkbox($id)
    {
        printf('<input type="checkbox" id="%s" name="subscription_options[%s]" %s />',
            $id, $id, isset($this->options[$id]) ? 'checked' : '');
    }

    /**
     * Render a text box for the given id.
     *
     * @param string $id ID of the configuration setting.
     * @return string HTML of the rendered text box.
     */
    private function text($id)
    {
        printf('<input type="text" id="%s" name="subscription_options[%s]" value="%s" />',
            $id, $id, isset($this->options[$id]) ? esc_attr($this->options[$id]) : '');
    }
}

if (is_admin())
    $subscription_settings_page = new ContentSubscriptionSetting();

?>
