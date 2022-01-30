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
define( 'OUTLINE_API_SERVER_NAME', 'https://salvation.inspirationhosting.com');

register_activation_hook( __FILE__, array( 'Outline', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Outline', 'plugin_deactivation' ) );

require_once( OUTLINE__PLUGIN_DIR . 'class.outline.php' );

add_action( 'template_redirect', array( 'Outline', 'init' ) );
add_action( 'gform_after_submission',  array( 'Outline', 'gformSubmitHook' ) );

add_action( 'admin_menu', 'add_settings_page' );
function add_settings_page() {
  add_options_page( 'Outline plugin page', 'Outline Plugin Menu', 'manage_options', 'outline-plugin-page', 'render_plugin_settings_page' );
}

add_action( 'admin_init', 'settings');

function at_rest_visit_endpoint() {
  $onOff = get_option('on_off');
  if ($onOff == 'on') {
    Outline::trackingVisited("visit");
    checkRedirect();
    return new WP_REST_Response(['message' => 'Successfully created.']);
  }
}

function at_rest_salvation_endpoint() {
  $onOff = get_option('on_off');
  if ($onOff == 'on') {
    Outline::trackingVisited("salvation");
    checkRedirect();
    return new WP_REST_Response(['message' => 'Successfully created.']);
  }
}

function at_rest_discipleship_endpoint() {
  $onOff = get_option('on_off');
  if ($onOff == 'on') {
    Outline::trackingVisited("discipleship");
    checkRedirect();
    return new WP_REST_Response(['message' => 'Successfully created.']);
  }
}

function checkRedirect() {
  if(isset($_GET['redirect-back'])) {
    header("HTTP/1.1 301 Moved Permanently"); 
    header("Location: " . $_SERVER['HTTP_REFERER']); 
    exit();
  }
}

function at_rest_init() {
  // route url: domain.com/wp-json/$namespace/$route
  $namespace = 'outline/v1';
  $routeTypes = [
    'visit',
    'salvation',
    'discipleship',
  ];

  foreach($routeTypes as $type) {
    register_rest_route($namespace, $type, array(
      'methods'   => WP_REST_Server::READABLE,
      'callback'  => 'at_rest_' . $type . '_endpoint'
    ));
  }
}

add_action('rest_api_init', 'at_rest_init');

function settings() {
  register_setting( 'plugin-settings-group', 'on_off' );
  register_setting( 'plugin-settings-group', 'discipleship_categories' );
  register_setting( 'plugin-settings-group', 'discipleship_pages');
  register_setting( 'plugin-settings-group', 'discipleship_posts');
  register_setting( 'plugin-settings-group', 'salvation');
}

function render_plugin_settings_page() {
  require_once(OUTLINE__PLUGIN_DIR . "views/settings.php");
?>
<?php
}

