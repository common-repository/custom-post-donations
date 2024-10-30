<?php header('Content-type: application/json'); ?>
<?php 
include_once('../../../../wp-config.php' );
$donationResults = $wpdb->get_results( "SELECT Id, name FROM $cpDonations_table" );
$count = 0;
?>{ "cpDonations": [
<?php foreach($donationResults as $donation) { ?>
<?php $count++; ?>
{ "id": "<?php echo $donation->Id; ?>", "name": "<?php echo $donation->name; ?>"}<?php if ($count < count($donationResults)) { echo ","; } ?>
<?php } ?>
]}