<?php
/**
 * Class for the settings page
 * 
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) )
	die();

if( class_exists( 'PWP_Settings' ) ) {
	$pwp_setting = new PWP_Settings();
}

class PWP_Settings {

    private $options; // holds the values to be used in the fields callbacks

    public function __construct() {

      	// only in admin mode
    	if( is_admin() ) {    
    		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    		add_action( 'admin_init', array( $this, 'register_settings' ) );
    	}

    }

    /**
     * Add options page
     * 
     */
    public function add_plugin_page() {

    	add_options_page(
    		__( 'Piwik/Matomo for WordPress', 'pwp_textdomain' ),
    		__( 'Piwik/Matomo', 'pwp_textdomain' ),
    		'manage_options',
    		PWP_SLUG,
    		array( $this, 'create_admin_page' )
    	);

    }

    public function create_admin_page() {
    	if ( ! current_user_can( 'manage_options' ) ) {
    	    return;
    	} ?>
    	<div class="wrap">
    	    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    	    <form action="options.php" method="post">
    	        <?php
    	        // output security fields for the registered setting
    	        settings_fields( 'pwp' );
    	        // output setting sections and their fields
    	        do_settings_sections( 'pwp' );
    	        // output save settings button
    	        submit_button( __( 'Save Settings', 'pwp_textdomain' ) );
    	        ?>
    	    </form>
    	</div>
    	<?php
    }

    /**
     * Register and add settings
     * 
     */
    public function register_settings(){
    	register_setting( 'pwp', 'pwp_options' );

        // General settings section
    	add_settings_section(
            'general_setting_section',
            __( 'General settings', 'pwp_textdomain' ),
            '',
            'pwp'
        ); 

        add_settings_field(
            'pwp_site_id',
            __( 'Enter the site ID: ', 'pwp_textdomain' ),
            array( $this, 'pwp_site_id_callback' ),
            'pwp',
            'general_setting_section',
            [
                'label_for' => 'pwp_site_id',
                'class' => 'form-field',
            ]
        );

        add_settings_field(
            'pwp_matomo_subdomain',
            __( 'Enter your analytics subdomain: ', 'pwp_textdomain' ),
            array( $this, 'pwp_analytics_subdomain_callback' ),
            'pwp',
            'general_setting_section',
            [
                'label_for' => 'pwp_matomo_subdomain',
                'class' => 'form-field',
            ]
        );

        register_setting(
          	'pwp',
          	'pwp_options',
          	array( $this, 'input_validate_sanitize' )
        );

    }

    /**
     * Sanitize settings fields
     * 
     */
    public function input_validate_sanitize( $input ) {
    	$output = array();

    	if( isset( $input['pwp_site_id'] ) ){
    		// $output['pwp_site_id'] = stripslashes( wp_filter_post_kses( addslashes( $input['pwp_site_id'] ) ) );
    		$output['pwp_site_id'] = $input['pwp_site_id'];
    	}

    	if( isset( $input['pwp_matomo_subdomain'] ) ){
    		$output['pwp_matomo_subdomain'] = stripslashes( wp_filter_post_kses( addslashes( $input['pwp_matomo_subdomain'] ) ) );
    		// $output['pwp_matomo_subdomain'] = $input['pwp_matomo_subdomain'];
    	}

    	return $output;
    }

    /**
     * Input HTML
     * 
     */
    function pwp_site_id_callback( $args ) {
        $options = get_option( 'pwp_options' ); ?>
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>"
               name="pwp_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
               type="number"
               value="<?php echo $options['pwp_site_id']; ?>"
               placeholder="0"
        >
	    <p class="description">
	        <?php echo __( 'Define site Piwik/Matomo unique identification.', 'pwp_textdomain' ); ?>
	    </p>
        <?php
    }

    function pwp_analytics_subdomain_callback( $args ) {
        $options = get_option( 'pwp_options' ); ?>
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>"
               name="pwp_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
               type="text"
               value="<?php echo $options['pwp_matomo_subdomain']; ?>"
               placeholder="http://"
        >
	    <p class="description">
	        <?php echo __( 'The address where your Piwik/Matomo instance is running.', 'pwp_textdomain' ); ?>
	    </p>
        <?php
    }

}

?>