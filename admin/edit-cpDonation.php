<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;
global $cpDonations_table;

ob_start();

//Select gallery
if(isset($_POST['widgetId'])) {
	if(check_admin_referer('cp_donation','cp_donation')) {
	  $gid = (isset($_POST['select_widget'])) ? $_POST['select_widget'] : $_POST['widgetId'];
	  $donationWidget = $wpdb->get_row( "SELECT * FROM $cpDonations_table WHERE Id = $gid" );
	}
}
if(isset($_POST['deleteDonationId'])) {
	if(check_admin_referer('cp_donation','cp_donation')) {
	  $donationWidget = $wpdb->get_row( "SELECT * FROM $cpDonations_table WHERE Id = '".$_POST['deleteDonationId']."'" );
	  $tempName = $donationWidget->name;
	  $wpdb->query( "DELETE FROM $cpDonations_table WHERE Id = '".$_POST['deleteDonationId']."'" );
	  ?>
	  <div class="updated"><p><strong><?php __($tempName) . _e(' has been deleted.', 'custom-post-donations'); ?></strong></p></div>
	  <?php
	}
}

if(isset($_POST['cpDonations_edit_widget']))
{
	if(check_admin_referer('cp_donation','cp_donation')) {
	  if($_POST['cpDonationName'] != "") {
				$cpDonationName = $_POST['cpDonationName'];
				$slug = strtolower(str_replace(" ", "", $_POST['cpDonationName']));
				$cpDonationDescription = $_POST['cpDonationDescription'];
				$cpDonationAmount = $_POST['cpDonationAmount'];
				$cpDonationType = $_POST['cpDonationType'];
				$cpDonationMaxItems = $_POST['cpDonationMaxItems'];

		if(isset($_POST['cpDonations_edit_widget'])) {
			$widgetEdited = $wpdb->update( $cpDonations_table, array( 'name' => $cpDonationName, 'slug' => $slug, 'description' => $cpDonationDescription, 'donationtype' => $cpDonationType, 'defaultdonation' => $cpDonationAmount, 'maxitems' => $cpDonationMaxItems ), array( 'Id' => intval($_POST['cpDonations_edit_widget']) ) );

				?>
				<div class="updated"><p><strong><?php _e('Donation widget has been edited.', 'custom-post-donations' ); ?></strong></p></div>
				<?php
		}
	  }
	}
}

$cpDonationWidgets = $wpdb->get_results( "SELECT * FROM $cpDonations_table" );

?>
<div class='wrap'>
	<h2><?php _e('Edit custom donation widgets', 'custom-post-donations' ); ?></h2>
	<p style="float: right; font-weight: bold; font-style: italic;"><a href="https://plugingarden.com/wordpress-paypal-plugin/?src=cpd">Upgrade to WP Donations Pro</a></p>
	<div style="clear: both;"></div>
    <?php if(!isset($_POST['select_widget']) && !isset($_POST['widgetId'])) { ?>
	<table class="widefat post fixed" cellspacing="0">
		<thead>
			<tr>
				<th style='width: 125px;'></th>
				<th><?php _e('Name', 'custom-post-donations'); ?></th>
				<th><?php _e('Type', 'custom-post-donations'); ?></th>
				<th><?php _e('Description', 'custom-post-donations'); ?></th>
			</tr>
		</thead>
		<?php
		foreach($cpDonationWidgets as $widget) {
		?>
			<tr>
				<td>
					<form name="view_donation_form-<?php _e($widget->Id) ?>" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post" style="float: left;">
						<?php wp_nonce_field('cp_donation','cp_donation'); ?>
						<input type="hidden" name="widgetId" value="<?php _e($widget->Id) ?>" />
						<span class="major-publishing-actions"><input type="submit" name="Submit" class="button-primary" value="Edit" /></span>
					</form>
					<form name="delete_donation_form-<?php _e($widget->Id) ?>" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post" style="float: right;">
						<?php wp_nonce_field('cp_donation','cp_donation'); ?>
						<input type="hidden" name="deleteDonationId" value="<?php _e($widget->Id) ?>" />
						<span class="major-publishing-actions"><input type="submit" name="Submit" class="button-primary" value="Delete" /></span>
					</form>
				</td>
				<td><?php _e($widget->name); ?></td>
				<td><?php switch($widget->donationtype) {
								case 1:
									_e('Standard Donation', 'custom-post-donations');
									break;
								case 2:
									_e('Fixed + Additional', 'custom-post-donations');
									break;
								case 3:
									_e('Per Item + Additional', 'custom-post-donations');
									break;
								default:
									break;
				          }
				?></td>
				<td><?php _e($widget->description);	?></td>
			</tr>
		<?php
		}
		?>
	</table>
    <?php } else if(isset($_POST['select_widget']) || isset($_POST['widgetId'])) { ?>
    <style>
		<?php
		if($donationWidget->donationtype == 3) {
			?>
			.default-donation {display:none;}
			<?php
		} else {
			?>
			.per-item {display:none;}
			<?php
		}
		?>
	</style>
	<h3><?php _e('Donation Form', 'custom-post-donations' ); ?>: <?php _e($donationWidget->name); ?></h3>
    <form name="switch_widgets" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="switch" value="true" />
    <?php wp_nonce_field('cp_donation','cp_donation'); ?>
    <p><input type="submit" name="Submit" class="button-primary" value="<?php _e('Back', 'custom-post-donations' ); ?>" /></p>
    </form>

    <p><?php _e('This is where you can edit existing donation widgets.', 'custom-post-donations' ); ?></p>

    <form name="cpDonations_edit_widget_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
    <input type="hidden" name="cpDonations_edit_widget" value="<?php _e($gid); ?>" />
    <?php wp_nonce_field('cp_donation','cp_donation'); ?>
    <table class="widefat post fixed" cellspacing="0">
    	<thead>
        <tr>
        	<th width="250"><?php _e('Field Name', 'custom-post-donations' ); ?></th>
            <th><?php _e('Value', 'custom-post-donations' ); ?></th>
            <th><?php _e('Description', 'custom-post-donations' ); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
        	<th><?php _e('Field Name', 'custom-post-donations' ); ?></th>
            <th><?php _e('Value', 'custom-post-donations' ); ?></th>
            <th><?php _e('Description', 'custom-post-donations' ); ?></th>
        </tr>
        </tfoot>
        <tbody>
        	<tr>
            	<td><strong><?php _e('Enter Unique Form/Product Name', 'custom-post-donations' ); ?>:</strong></td>
                <td><input type="text" size="30" name="cpDonationName" value="<?php _e($donationWidget->name); ?>" /></td>
                <td><?php _e('This name is the internal name for the donation form. This also shows as the item name in PayPal<br />Please avoid non-letter characters such as', 'custom-post-donations' ); ?> ', ", *, etc.</td>
            </tr>
            <tr>
            	<td><strong><?php _e('Enter Form Description', 'custom-post-donations' ); ?>:</strong></td>
                <td><input type="text" size="50" name="cpDonationDescription" value="<?php _e($donationWidget->description); ?>" /></td>
                <td><?php _e('This name is the internal description for the donation form.', 'custom-post-donations' ); ?></td>
            </tr>
            <tr>
            	<td><strong><span class="default-donation"><?php _e('Enter Default Amount', 'custom-post-donations' ); ?></span><span class="per-item"><?php _e('Enter per Item Amount', 'custom-post-donations' ); ?></span>:</strong></td>
                <td><input type="text" size="30" name="cpDonationAmount" value="<?php _e($donationWidget->defaultdonation); ?>" /></td>
                <td><span class="default-donation"><?php _e('This is the default donation amount.', 'custom-post-donations' ); ?></span><span class="per-item"><?php _e('This is the per item price.', 'custom-post-donations' ); ?></span></td>
            </tr>
            <tr>
            	<td><strong><?php _e('Select Form Type', 'custom-post-donations' ); ?>:</strong></td>
                <td>
                  <select name="cpDonationType" id="donation-type">
                  	<option id="standard" value="1"<?php echo ($donationWidget->donationtype == 1) ? " selected='selected'" : "" ?>><?php _e('Standard Donation', 'custom-post-donations'); ?></option>
                    <option id="fixed-additional" value="2"<?php echo ($donationWidget->donationtype == 2) ? " selected='selected'" : "" ?>><?php _e('Fixed + Additional', 'custom-post-donations'); ?></option>
                    <option id="per-item" value="3"<?php echo ($donationWidget->donationtype == 3) ? " selected='selected'" : "" ?>><?php _e('Per Item + Additional', 'custom-post-donations'); ?></option>
                  </select>
                </td>
                <td>
                	<?php _e('This determines what type of form appears in the post or page.', 'custom-post-donations' ); ?>
                    <ol>
                    	<li><?php _e('Standard Donation - One editable donation amount field.', 'custom-post-donations' ); ?></li>
                        <li><?php _e('Fixed + Additional - One fixed donation amount with an additional editable donation amount field.', 'custom-post-donations' ); ?></li>
                        <li><?php _e('Per Item + Additional - Fixed donation amount per item with an additional editable donation amount field.', 'custom-post-donations' ); ?></li>
                    </ol>
                </td>
            </tr>
            <tr class="per-item">
            	<td><strong><?php _e('Enter Maximum Number of Items', 'custom-post-donations' ); ?>:</strong></td>
                <td><input type="text" size="30" name="cpDonationMaxItems" value="<?php _e($donationWidget->maxitems); ?>" /></td>
                <td><?php _e('This is will set the maximum number of items that can be selected in a per item form.', 'custom-post-donations' ); ?></td>
            </tr>
            <tr>
            	<td class="major-publishing-actions"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Edit Donation Form', 'custom-post-donations' ); ?>" /></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
	</table>
    </form>
    <?php } ?>
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
