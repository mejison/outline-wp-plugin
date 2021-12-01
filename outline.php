<?php
/**
 * Plugin Name: Outline Plugin
 * Plugin URI: http://www.outline.com/plugin
 * Description: The very first plugin that I have ever created.
 * Version: 1.0
 * Author: mejison
 * Author URI: http://www.mejison.me
 */

define( 'OUTLINE__VERSION', '0.1.0' );
define( 'OUTLINE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'Outline', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Outline', 'plugin_deactivation' ) );

require_once( OUTLINE__PLUGIN_DIR . 'class.outline.php' );
require_once( OUTLINE__PLUGIN_DIR . 'class.outline-rest-api.php' );


add_action( 'init', array( 'Outline', 'init' ) );


add_action( 'admin_menu', 'add_settings_page' );
function add_settings_page() {
    add_options_page( 'Outline plugin page', 'Outline Plugin Menu', 'manage_options', 'outline-plugin-page', 'render_plugin_settings_page' );
}

function render_plugin_settings_page() {
    ?>
    <h2>Outline Plugin Settings</h2>
    <form action="options.php" method="post">
       <?php
         require_once( OUTLINE__PLUGIN_DIR . 'views/settings.php' );
       ?>
    </form>
    <?php
}