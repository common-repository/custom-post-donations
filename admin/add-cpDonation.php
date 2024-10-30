<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;
global $cpDonations_table;

$cpDonationName = '';
$publicationDate = '';
$slug = '';
$cpDonationDescription = '';
$cpDonationAmount = '';
$cpDonationType = '';
$cpDonationMaxItems = '';

ob_start();
// add new donation widget
if(isset($_POST['cpDonation_add']))
	{
		if(check_admin_referer('cp_donation','cp_donation')) {
		  if($_POST['cpDonationName'] != "") {
			  if($_POST['cpDonationType'] > 2 && $_POST['cpDonationMaxItems'] == null) {
				$cpDonationName = $_POST['cpDonationName'];
				$slug = strtolower(str_replace(" ", "", $_POST['cpDonationName']));
				$cpDonationDescription = $_POST['cpDonationDescription'];
				$cpDonationAmount = $_POST['cpDonationAmount'];
				$cpDonationType = $_POST['cpDonationType'];
				$cpDonationMaxItems = $_POST['cpDonationMaxItems'];
				  ?>
				<div class="updated"><p><strong><?php _e('Please enter a maximum item number.' ); ?></strong></p></div>
				<?php
			  }
			  else {

				global $wpdb;

				$cpDonationName = $_POST['cpDonationName'];
				$slug = strtolower(str_replace(" ", "", $_POST['cpDonationName']));
				$cpDonationDescription = $_POST['cpDonationDescription'];
				$cpDonationAmount = $_POST['cpDonationAmount'];
				$cpDonationType = $_POST['cpDonationType'];
				$cpDonationMaxItems = ($_POST['cpDonationMaxItems'] != null) ? $_POST['cpDonationMaxItems'] : 1;
				$donationAdded = $wpdb->insert( $cpDonations_table, array( 'name' => $cpDonationName, 'slug' => $slug, 'description' => $cpDonationDescription, 'donationtype' => $cpDonationType, 'defaultdonation' => $cpDonationAmount, 'maxitems' => $cpDonationMaxItems ) );

				if($donationAdded) {
				?>
				<div class="updated"><p><strong><?php _e('WP Donation Widget Added.' ); ?></strong></p></div>
				<?php
				}
				else {
					?>
				  <div class="updated"><p><strong><?php _e('Please enter a widget name.' ); ?></strong></p></div>
				  <?php
				}
			  }
		  }
		}
	}
?>
<style>
.per-item {display: none;}
</style>
<div class='wrap cp-donations'>
	<h2>Add Donation - Create custom donation form</h2>
	<p style="float: right; font-weight: bold; font-style: italic;"><a href="https://plugingarden.com/wordpress-paypal-plugin/?src=cpd">Upgrade to WP Donations Pro</a></p>
	<div style="clear: both;"></div>
    <form name="add_cpdonation_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
    <input type="hidden" name="cpDonation_add" value="true" />
    <?php wp_nonce_field('cp_donation','cp_donation'); ?>
    <table class="widefat post fixed">
    	<thead>
        <tr>
        	<th class="width-250">Field Name</th>
            <th>Value</th>
            <th>Description</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
        	<th>Field Name</th>
            <th>Value</th>
            <th>Description</th>
        </tr>
        </tfoot>
        <tbody>
        	<tr>
            	<td><strong>Enter Unique Widget Name:</strong></td>
                <td><input type="text" size="30" name="cpDonationName" value="<?php _e($cpDonationName); ?>" /></td>
                <td>This name is the internal name for the donation widget.<br />Please avoid non-letter characters such as ', ", *, etc.</td>
            </tr>
            <tr>
            	<td><strong>Enter Widget Description:</strong></td>
                <td><input type="text" size="50" name="cpDonationDescription" value="<?php _e($cpDonationDescription); ?>" /></td>
                <td>This name is the internal description for the donation widget.</td>
            </tr>
            <tr>
            	<td><strong>Enter <span class="default-donation">Default</span><span class="per-item">per Item</span> Amount:</strong></td>
                <td><input type="text" size="30" name="cpDonationAmount" value="<?php _e($cpDonationAmount); ?>" /></td>
                <td>This is the <span class="default-donation">default donation amount</span><span class="per-item">per item price</span>.</td>
            </tr>
            <tr>
            	<td><strong>Select Widget Type:</strong></td>
                <td>
                  <select name="cpDonationType" id="donation-type">
                  	<option id="standard" value="1"<?php echo ($cpDonationType == 1) ? " selected='selected'" : "" ?>>Standard Donation</option>
                    <option id="fixed-additional" value="2"<?php echo ($cpDonationType == 2) ? " selected='selected'" : "" ?>>Fixed + Additional</option>
                    <option id="per-item" value="3"<?php echo ($cpDonationType == 3) ? " selected='selected'" : "" ?>>Per Item + Additional</option>
                  </select>
                </td>
                <td>
                	This determines what type of widget appears in the post or page.
                    <ol>
                    	<li>Fixed Donation - one editable donation amount field</li>
                        <li>Fixed + Additional - one fixed donation amount with an additional editable donation amount field</li>
                        <li>Per Item + Additional - Fixed donation amount per item witn an additional editable donation amount field</li>
                    </ol>
                </td>
            </tr>
            <tr class="per-item">
            	<td><strong>Enter Maximum Number of Items:</strong></td>
                <td><input type="text" size="30" name="cpDonationMaxItems" value="<?php _e($cpDonationMaxItems); ?>" /></td>
                <td>This is will set the maximum number of items that can be selected in a per item widget.</td>
            </tr>
            <tr>
            	<td class="major-publishing-actions"><input type="submit" name="Submit" class="button-primary" value="Create Donation Widget" /></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
	</table>
    </form>
    <br />
     <h2><a href="https://plugingarden.com/wordpress-paypal-plugin/?src=cpd" target="_blank">Upgrade to the Pro Version</a></h2>
     <ul>
        <li>Edit display text for donation form fields</li>
		<li>New 'Campaign' donation type captures name, address, employer and occupation - follows Federal Election Commission (FEC) regulations</li>
        <li>Now supports multiple currencies</li>
        <li>Add customized donation forms to your posts or pages</li>
        <li>Designate alternate PayPal accounts for donations</li>
        <li>Add donation form titles</li>
        <li>Manage multiple donation forms from the easy access admin interface</li>
        <li>Ability to edit donation widgets</li>
     </ul>
     <strong><a href="https://plugingarden.com/wordpress-paypal-plugin/?src=cpd" target="_blank">Upgrade to the Pro Version</a></strong>
     <hr />
     <h3>Try also - <a href="https://plugingarden.com/wordpress-gallery-plugin/?src=cpd" target="_blank">WP Easy Gallery Pro</a></h3>
     <p>WP Easy Gallery allows you to manage multiple image galleries through an easy to use admin interface.</p>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#donation-type').on('change', function(e) {
		var donationTypeId = jQuery(this).children(':selected').attr('id');
		if (donationTypeId == 'per-item') {
			jQuery('.per-item').show();
			jQuery('.default-donation').hide();
		} else {
			jQuery('.per-item').hide();
			jQuery('.default-donation').show();
		}
	});
});
</script>
<?php ob_end_flush(); ?>
