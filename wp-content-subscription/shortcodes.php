<?php
/**
  * Shortcodes for authors to restrict content and display subscription info.
  * @link https://github.com/damieng/wordpressbits/wp-content-subscription
  * @copyright 2013-2014 Damien Guard.
  * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2
  */

require_once 'functions.php';

add_shortcode('subscriptionActive', 'shortcodeSubscriptionActive');
/**
 * A shortcode to display HTML content only if the user has an active subscription.
 * This is used to display content that is for active subscribers only.
 *
 * @param string $content HTML content to display if user has an active subscription.
 * @param array $atts Normally attributes but ignored.
 * @return string HTML content if the user has an active subscription, blank otherwise.
 */
function shortcodeSubscriptionActive($atts = array(), $content = '')
{
    global $user_ID;

    return subscription_active($user_ID) ? do_shortcode($content) : '';
}

add_shortcode('subscriptionExpired', 'shortcodeSubscriptionExpired');
/**
 * A shortcode to display HTML content only if the users subscription expired.
 * This is used to display messages to expired users offering renewal options.
 *
 * @param string $content HTML content to display if user has an active subscription.
 * @param array $atts Normally attributes but ignored.
 * @return string HTML content if the user has an expired subscription, blank otherwise.
 */
function shortcodeSubscriptionExpired($atts = array(), $content = '')
{
    global $user_ID;

    return subscription_ended($user_ID) ? do_shortcode($content) : '';
}

add_shortcode('subscriptionEndDate', 'shortcodeSubscriptionEndDate');
/**
 * A shortcode to the date the users subscription expires.
 *
 * @param string $content Normally HTML content but ignored.
 * @param array $atts Normally attributes but ignored.
 * @return string Date the users subscription will expire, blank otherwise.
 */
function shortcodeSubscriptionEndDate($atts = array(), $content = '')
{
    global $user_ID;

    return get_user_meta($user_ID, 'subs_end', true);
}
