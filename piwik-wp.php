<?php
/*
Plugin Name: Piwik/Matomo WordPress
Plugin URI: https://github.com/Darciro/Piwik-WordPress
Description: Adds a script code to track down your web traffic in the #1 secure ppen web analytics platform - Matomo, former Piwik
Version: 2.0
Author: Ricardo Carvalho
Author URI: https://galdar.com.br
License: GNU GPLv3
*/

if ( !defined( 'WPINC' ) )
	die();

define( 'PWP_SLUG', 'pwp' );
define( 'PWP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PWP_PLUGIN_PATH', dirname( __FILE__ ) );

// Include our options page
require_once( 'inc/settings.php' );

class PiwikWP {

	public function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		// Add piwik script to the head element
		add_action( 'wp_head', array( $this, 'piwik_script' ) );

	}

	/**
	 * Load the plugin text domain for translation
	 *
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'pwp_textdomain', false, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/lang/' ); 
	}

	/**
	* Register piwik script to the head of document
	* 
	*/
	public function piwik_script(){
		$options = get_option( 'pwp_options');  ?>

		<!-- Matomo -->
		<script type="text/javascript">
			var _paq = window._paq || [];
			/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			(function() {
				var u="<?php echo $options['pwp_matomo_subdomain']; ?>";
				_paq.push(['setTrackerUrl', u+'matomo.php']);
				_paq.push(['setSiteId', <?php echo $options['pwp_site_id']; ?>]);
				var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
				g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
			})();
		</script>
		<!-- End Matomo Code -->

		<?php
	}

}

// Instantiate our plugin
$piwi_wp = new PiwikWP();

?>