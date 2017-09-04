<?php
/**
  * Add subscription fields to user profiles available to administrator.
  * @link https://github.com/damieng/wordpressbits/wp-content-subscription
  * @copyright 2013-2014 Damien Guard.
  * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2
  */

add_action('edit_user_profile', 'showSubscriberForm');
/**
 * Show the subscriber form with the user's subscription
 * details.
 *
 * @param WP_User $user WordPress user to display the subscription for.
 * @return void
 */
function showSubscriberForm($user) { ?>
<h3>Subscription</h3>
<table class="form-table">
<?php
$subs_start = get_the_author_meta('subs_start', $user->ID);
$subs_end = get_the_author_meta('subs_end', $user->ID);
$subs_plan = get_the_author_meta('subs_plan', $user->ID);
$subs_id = get_the_author_meta('subs_id', $user->ID);
$subs_length = get_the_author_meta('subs_length', $user->ID);
$subs_gateway = get_the_author_meta('subs_gateway', $user->ID);

if ($subs_start != '') {
    $start = new DateTime($subs_start);
    $subs_start = $start->format('Y-m-d');
}

if ($subs_end != '') {
    $end = new DateTime($subs_end);
    $subs_end = $end->format('Y-m-d');
}
?>
    <tbody>
        <tr>
            <th>
                <label for="subs_start">First subscribed</label>
            </th>
            <td>
                <input type="text" name="subs_start" id="subs_start" size="8" maxlength="10" value="<?php echo esc_attr($subs_start) ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="subs_plan">Most recent payment</label>
            </th>
            <td>
                <b><?php echo htmlspecialchars($subs_plan . ' (' . str_replace('P', '', $subs_length) . ')') ?></b>
                paid up to
                <input type="text" name="subs_end" id="subs_end" size="8" maxlength="10" value="<?php echo esc_attr($subs_end) ?>" />
                (<?php echo esc_attr($subs_gateway) ?> transaction
                <?php echo esc_attr($subs_id) ?>)
            </td>
        </tr>
    </tbody>
</table>
<?php }

// Admin user profile field saving
add_action('edit_user_profile_update', 'saveSubscriptionForm');
/**
 * Save changes to the user subscription settings if the user
 * is allowed to edit them.
 *
 * @param integer $user_id User ID of the WordPress user being edited.
 * @return boolean True if the changes were saved, false if they were not permitted.
 */
function saveSubscriptionForm($user_id)
{
    if (!current_user_can('edit_users', $user_id))
        return false;

    update_usermeta($user_id, 'subs_start', $_POST['subs_start'] );
    update_usermeta($user_id, 'subs_end', $_POST['subs_end'] );
    return true;
}

?>
