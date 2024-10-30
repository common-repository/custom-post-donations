<?php
//Donation Widget
class CP_Donation_Widget extends WP_Widget {
	//register widget
	function __construct() {
		parent::__construct(
			'cp_donation_widget',
			__('WP Donations Widget', 'custom-post-donations-pro'),
			array('description' => __('Simple Donation Widget', 'custom-post-donations-pro'), )
		);
	}
	
	//front-end
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( !empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		
		if ( empty( $instance['default_donation'] ) ||  empty( $instance['item_name'] )) {
			$instance['default_donation'] = '5.00';
			$instance['item_name'] = 'Donation';
		}
		
			$buttonStyle = "<input type='image' src='https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif' style='width: 92px;' border='0' name='submit' class='paypalSubmit' alt='' onclick='return false;'>";
	
			switch(get_option('cpDonations_buttonStyle')) {
				case "small":
					$buttonStyle = "<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif' style='width: 74px;' border='0' name='submit' class='paypalSubmit' alt='' onclick='return false;'>";
					break;
				case "withCC":
					$buttonStyle = "<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif' style='width: 147px;' border='0' name='submit' class='paypalSubmit' alt='' onclick='return false;'>";
					break;
				default:
					$buttonStyle = "<input type='image' src='https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif' style='width: 92px;' border='0' name='submit' class='paypalSubmit' alt='' onclick='return false;'>";
					break;
			}
	
			echo "<div><form class='cpDonation' action='https://www.paypal.com/cgi-bin/webscr' method='post'>".
					"<input type='hidden' class='cmd' name='cmd' value='_donations'>".
					"<p class='donate_amount'><label class='cp-donation' for='amount'>Your Donation Amount:</label><input type='text' class='cp-donation amount' name='amount' value='".$instance['default_donation']."' /></p>\n".
					"<p>Your total amount is : <span class='total_amt'>".$instance['default_donation']."</span> <small>(Currency: USD)</small></p>".
					"<input type='hidden' name='cp_quantity' class='cp_quantity' value='0' />".
					"<input type='hidden' name='item_name' class='item_name' value='".$instance['item_name']."'>".
					"<input type='hidden' name='business' value='".get_option("cpDonations_Business_Name")."'>".
					"<input type='hidden' name='no_note' value='1'>".
					"<input type='hidden' name='no_shipping' value='0'>".
					"<input type='hidden' name='rm' value='1'>".
					"<input type='hidden' name='currency_code' value='USD'>".
					"<input type='hidden' name='bn' value='PP-DonationsBF:btn_donateCC_LG.gif:NonHosted'>".
					"<p class='submit'>".$buttonStyle."".
					"<img alt='' border='0' src='https://www.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'></p>".
				"</form></div>";		
		
		echo $args['after_widget'];
	}
	
	//back-end
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		else {
			$title = __( 'Donation', 'custom-post-donations-pro' );
		}
		if ( isset( $instance['default_donation'] ) ) {
			$default_donation = $instance['default_donation'];
		}
		else {
			$default_donation = __( '5.00', 'custom-post-donations-pro' );
		}
		if ( isset( $instance['item_name'] ) ) {
			$item_name = $instance['item_name'];
		}
		else {
			$item_name = __( 'Donation', 'custom-post-donations-pro' );
		}
		?>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		<label for="<?php echo $this->get_field_id( 'default_donation' ); ?>"><?php _e( 'Default Donation Amount:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'default_donation' ); ?>" name="<?php echo $this->get_field_name( 'default_donation' ); ?>" type="text" value="<?php echo esc_attr( $default_donation ); ?>">
		<label for="<?php echo $this->get_field_id( 'item_name' ); ?>"><?php _e( 'Donation name/reason:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'item_name' ); ?>" name="<?php echo $this->get_field_name( 'item_name' ); ?>" type="text" value="<?php echo esc_attr( $item_name ); ?>">
		<?php
	}
	
	//sanitize form values when updated
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['default_donation'] = ( !empty( $new_instance['default_donation'] ) ) ? strip_tags( $new_instance['default_donation'] ) : '';
		$instance['item_name'] = ( !empty( $new_instance['item_name'] ) ) ? strip_tags( $new_instance['item_name'] ) : '';
		
		return $instance;
	}
}
?>