<?php
/**
 * Plugin Name:       Scrollup
 * Plugin URI:        http://wordpress.org/plugins/scrollup-master/
 * Description:       This plugin is for wordpress to create scroll to top button.
 * Version:           2.6.0
 * Author:            Sayful Islam
 * Author URI:        http://sayfulit.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if( !class_exists('Scrollup_Master')):

class Scrollup_Master {

	protected static $instance = null;

	public function __construct(){
		add_action( 'admin_init', array( $this, 'settings_init') );
		add_action( 'admin_menu', array( $this, 'admin_menu') );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts') );
		add_action( 'wp_enqueue_scripts', array( $this, 'custom_style') );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts') );
		add_action( 'wp_footer', array( $this, 'inline_scripts') );
	}

	public static function instance(){
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function inline_scripts() {

		$options = self::get_options();
		
		?><script type="text/javascript">
			jQuery(function () {
				jQuery.scrollUp({
			        scrollDistance: <?php echo $options['scrolldistance']; ?>,
			        scrollSpeed: <?php echo $options['scrollspeed']; ?>,
			        easingType: '<?php echo $options['tooltipeasingtype']; ?>',
			        animation: '<?php echo $options['animation']; ?>',
			        scrollTitle: '<?php echo $options['tooltiptitle']; ?>',
			        scrollImg: <?php echo ( $options['btn_type'] == 'image' ) ? 'true' : 'false'; ?>
				});
			});
		</script><?php

	}

	public static function get_options(){
		$options_array = array(
	      	'btn_bottom' 			=> '20px',
	      	'btn_right' 			=> '20px',
	      	'tooltiptitle' 			=> 'Scroll to top',
	      	'tooltipeasingtype' 	=> 'linear',
	      	'scrollspeed' 			=> '300',
	      	'scrolldistance' 		=> '300',
	      	'animation' 			=> 'fade',
	      	'btn_type' 				=> 'image',
	      	'tab_txt_color' 		=> '#828282',
	      	'tab_bg_color' 			=> '#E6E6E6',
	      	'pill_txt_color' 		=> '#FFFFFF',
	      	'pill_bg_color' 		=> '#555555',
	      	'pill_bghover_color' 	=> '#000000'
	    );
	   	$options = wp_parse_args(get_option( 'sis_scrooltotop_settings' ), $options_array);
	   	return $options;
	}

	public function settings_init(){
	    register_setting(
	    	'sis_scrooltotop_settings',
	    	'sis_scrooltotop_settings',
	    	array( $this, 'sanitize' )
	    );
	}

	public function sanitize($input) {

	    $input['btn_bottom'] 			=  wp_filter_nohtml_kses($input['btn_bottom']);
	    $input['btn_right'] 			=  wp_filter_nohtml_kses($input['btn_right']);
	    $input['tooltiptitle'] 			=  wp_filter_nohtml_kses($input['tooltiptitle']);
	    $input['tooltipeasingtype'] 	=  wp_filter_nohtml_kses($input['tooltipeasingtype']);
	    $input['scrollspeed'] 			=  wp_filter_nohtml_kses($input['scrollspeed']);
	    $input['scrolldistance'] 		=  wp_filter_nohtml_kses($input['scrolldistance']);
	    $input['animation'] 			=  wp_filter_nohtml_kses($input['animation']);
	    $input['btn_type'] 				=  wp_filter_nohtml_kses($input['btn_type']);
	    $input['tab_txt_color'] 		=  wp_filter_nohtml_kses($input['tab_txt_color']);
	    $input['tab_bg_color'] 			=  wp_filter_nohtml_kses($input['tab_bg_color']);
	    $input['pill_txt_color'] 		=  wp_filter_nohtml_kses($input['pill_txt_color']);
	    $input['pill_bg_color'] 		=  wp_filter_nohtml_kses($input['pill_bg_color']);
	    $input['pill_bghover_color'] 	=  wp_filter_nohtml_kses($input['pill_bghover_color']);
	   
	    return $input;
	}

	public function admin_menu(){
	    add_options_page(
	    	'Scrollup Settings',
	    	'Scrollup',
	    	'manage_options',
	    	'scrollup-master/scrollup-options.php'
	    );
	}

	public function enqueue_scripts() {
		$options = self::get_options();

		wp_enqueue_script('scrollUp',	plugins_url( '/js/jquery.scrollUp.min.js' , __FILE__ ), array( 'jquery' ));

		if ( !in_array($options['tooltipeasingtype'], array( 'linear', 'swing' ) ) ) {
			wp_enqueue_script( 'easing', plugins_url( '/js/jquery.easing.js' , __FILE__ ), array( 'jquery' ));
		}

		if ( $options['btn_type'] == 'link' ){

			wp_enqueue_style( 'scrollup-master', plugins_url( '/css/link.css' , __FILE__ ), array(), '1.0.0', 'all' );

		} elseif ( $options['btn_type'] == 'tab' ) {

			wp_enqueue_style( 'scrollup-master', plugins_url( '/css/tab.css' , __FILE__ ), array(), '1.0.0', 'all' );

		} elseif ( $options['btn_type'] == 'pill' ) {

			wp_enqueue_style( 'scrollup-master', plugins_url( '/css/pill.css' , __FILE__ ), array(), '1.0.0', 'all' );

		} else {

			wp_enqueue_style( 'scrollup-master', plugins_url( '/css/image.css' , __FILE__ ), array(), '1.0.0', 'all' );

		}
	}

	public function admin_scripts( $hook_suffix ) {
	    wp_enqueue_style( 'wp-color-picker' );
	    wp_enqueue_script(
	    	'scrollup_color_script', 
	    	plugins_url('/js/admin.js', __FILE__ ),
	    	array( 'wp-color-picker' ),
	    	false,
	    	true
	    );
	}

	public function custom_style(){
		$options = self::get_options();
		$style = '';

		if ( $options['btn_type'] == 'image' ){

			$style .= "#scrollUp { bottom: {$options['btn_bottom']}; right: {$options['btn_right']}; }";

		} elseif ( $options['btn_type'] == 'tab' ){

			$style .= "#scrollUp { right: {$options['btn_right']}; color: {$options['tab_txt_color']}; background-color: {$options['tab_bg_color']};}";

		} elseif ( $options['btn_type'] == 'pill' ){

			$style .= "#scrollUp { background-color: {$options['pill_bg_color']}; color: {$options['pill_txt_color']}; right: {$options['btn_right']}; bottom: {$options['btn_bottom']}; } #scrollUp:hover { background-color: {$options['pill_bghover_color']}; } ";

		} elseif ( $options['btn_type'] == 'link' ){

			$style .= "#scrollUp { bottom: {$options['btn_bottom']}; right: {$options['btn_right']}; }";

		}

		wp_add_inline_style( 'scrollup-master', $style );
	}
}
add_action('plugins_loaded', array( 'Scrollup_Master', 'instance' ));

endif;
