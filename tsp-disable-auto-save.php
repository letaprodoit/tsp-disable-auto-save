<?php
/*
Plugin Name: 	TSP Disable Auto-Save
Plugin URI: 	http://www.thesoftwarepeople.com/software/plugins/wordpress/disable-autosave-for-wordpress.html
Description:    Plugin to prevent WordPress from automatically saving duplicate copies of posts while editing.
Author: 		The Software People
Author URI: 	http://www.thesoftwarepeople.com/
Version: 		1.1.0
Text Domain: 	tspdas
Copyright: 		Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
License: 		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
*/

require_once(ABSPATH . 'wp-admin/includes/plugin.php' );

define('TSPDAS_PLUGIN_FILE', 				__FILE__ );
define('TSPDAS_PLUGIN_PATH',				plugin_dir_path( __FILE__ ) );
define('TSPDAS_PLUGIN_URL', 				plugin_dir_url( __FILE__ ) );
define('TSPDAS_PLUGIN_BASE_NAME', 			plugin_basename( __FILE__ ) );
define('TSPDAS_PLUGIN_NAME', 				'tsp-disable-auto-save');
define('TSPDAS_PLUGIN_TITLE', 				'TSP Diable Auto-Save');
define('TSPDAS_PLUGIN_REQ_VERSION', 		"3.5.1");

// If either of the plugins exist then inlude the register script to register all the classes
// else deactivaet this plugin and do not allow installation
if ( !file_exists ( WP_PLUGIN_DIR . "/tsp-easy-dev/TSP_Easy_Dev.register.php" ) && !file_exists( WP_PLUGIN_DIR . "/tsp-easy-dev-pro/TSP_Easy_Dev.register.php" ) )
{
	add_action( 'admin_notices', function (){
		
		$message = TSPDAS_PLUGIN_TITLE . ' <strong>was not installed</strong>, plugin requires the installation and activation of <strong><a href="plugin-install.php?tab=search&type=term&s=TSP+Easy+Dev">TSP Easy Dev</a></strong> or <strong><a href="plugin-install.php?tab=search&type=term&s=TSP+Easy+Dev+Pro">TSP Easy Dev Pro</a></strong>.';
	    ?>
	    <div class="error">
	        <p><?php echo $message; ?></p>
	    </div>
	    <?php
	} );
	
	deactivate_plugins( TSPDAS_PLUGIN_BASE_NAME );
	
	return;
}//endif
else
{
    if (file_exists( WP_PLUGIN_DIR . "/tsp-easy-dev-pro/TSP_Easy_Dev.register.php" ))
    {
    	include_once WP_PLUGIN_DIR . "/tsp-easy-dev-pro/TSP_Easy_Dev.register.php";
    }//end if
    elseif (file_exists( WP_PLUGIN_DIR . "/tsp-easy-dev/TSP_Easy_Dev.register.php" ))
    {
    	include_once WP_PLUGIN_DIR . "/tsp-easy-dev/TSP_Easy_Dev.register.php";
    }//end else
}//end else

global $easy_dev_settings;

require( TSPDAS_PLUGIN_PATH . 'TSP_Easy_Dev.config.php');
require( TSPDAS_PLUGIN_PATH . 'TSP_Easy_Dev.extend.php');
//--------------------------------------------------------
// initialize the Facepile plugin
//--------------------------------------------------------
$diable_autosave 								= new TSP_Easy_Dev( TSPDAS_PLUGIN_FILE, TSPDAS_PLUGIN_REQ_VERSION );

// Display the parent page but not the options page for this plugin
$diable_autosave->set_options_handler( new TSP_Easy_Dev_Options_Auto_Save( $easy_dev_settings, true, false ) );

// Remove revisions actions
remove_action('pre_post_update', 'wp_save_post_revision');

$diable_autosave->remove_registered_scripts( array( 'autosave' ) );

$diable_autosave->set_plugin_icon( TSPDAS_PLUGIN_URL . 'images' . DS . 'tsp_icon_16.png' );

$diable_autosave->run( TSPDAS_PLUGIN_FILE );
?>