//
// Plugin Name: Custom Post Donations
// Plugin URI: http://labs.hahncreativegroup.com/wordpress-plugins/custom-post-donations/
//
jQuery(document).ready(function(){
	var defaultDonation = jQuery('#total_amt').html();
	jQuery('#amount').keydown(function(e){if(!((e.keyCode > 47 && e.keyCode < 59) || ( e.keyCode >= 96 && e.keyCode <= 105 ||  e.keyCode == 110 || e.keyCode == 190) || e.keyCode == 8)){e.preventDefault();}});
	
	//Type 1
	jQuery('#amount').keyup(function(){												   
		  var donation = jQuery('#amount').val();
		  if(!isNaN(donation)){
			  var total = donation;
			  jQuery('#amount').val(total);
			  jQuery('#total_amt').html(total);
		  }else{
			  jQuery('#total_amt').html(defaultDonation);
		  }		  
		  
	});
	
	//Type 2
	jQuery('#amount2').keyup(function(){ 	  
		  var donation = parseFloat(jQuery('#fixed-amount').html());
		  var quantity = (parseFloat(jQuery('option:selected').val()) > 1) ? parseFloat(jQuery('option:selected').val()) : 1;
		  var additionalDonation = parseFloat(jQuery('#amount2').val());
		  if(!isNaN(additionalDonation)){
			  var total = additionalDonation+(donation*quantity);
			  jQuery('#amount').val(total);
			  jQuery('#total_amt').html(total);
		  }else{
			  jQuery('#total_amt').html(defaultDonation);
		  }	
	});

	//Type 3
	jQuery('#quantity').change(function(){ 
		  jQuery('#amount2').val('');						
		  var quantity = parseFloat(jQuery(this).val());
		  if(!isNaN(quantity)){
			  var total = quantity*parseFloat(jQuery('#fixed-amount').html());
			  jQuery('#amount').val(total);
			  jQuery('#total_amt').html(total);
		  }else{
			  jQuery('#total_amt').html(defaultDonation);
		  }	
	});
});