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

add_action( 'admin_init', 'settings');

function settings() {
  register_setting( 'plugin-settings-group', 'on_off' );
  register_setting( 'plugin-settings-group', 'discipleship_categories' );
  register_setting( 'plugin-settings-group', 'discipleship_pages');
}

function render_plugin_settings_page() {
  
    ?>
    <h2>Outline Plugin Settings</h2>
    <form method="post" action="options.php">
       <?php settings_fields('plugin-settings-group'); ?>
        <?php do_settings_sections( 'plugin-settings-group' ); ?>

        <table class="form-table">
          <tbody>
              <tr>
                  <td>
                      <th>
                          <label for="on_off">Off/On</label>
                      </th>
                  </td>
                  <td>
                  <label class="switch">
                      <input type="checkbox" name="on_off" <?php echo get_option('on_off') == 'on' ? 'checked=checked' : ''; ?> />
                      <span class="slider round"></span>
                  </label>
                  </td>
              </tr>
              <tr>
                <td>
                    <th>
                        <label>Salvation tracked</label>
                    </th>
                </td>
                <td>
                    <p class="description">
                        Gravity forms hook added on submission for specific form to be selected in settings page (recorded as Salvation eventType)
                    </p>
                </td>
              </tr>
              <tr>
                  <td>
                      <th>
                          <label>Discipleship tracked</label>
                      </th>
                  </td>
                  <td>
                      <p class="description">
                          Blog/Page Category/categories to be selected as being discipleship pages to be tracked that way (recorded as discipleship eventType)   
                      </p>
                      <div class="discipleship-section">
                          <div class="pages">
                              <h2>Pages</h2>
                              <ul>
                                <?php foreach(get_pages() as $page) { ?>
                                  <li>
                                        <label>
                                            <input type="checkbox" name="discipleship_pages[<?= $page->ID ?>]" <?php echo ! empty(get_option('discipleship_pages')[$page->ID]) ? 'checked=checked' : ''; ?> /><?= $page->post_name; ?>
                                        </label>
                                    </li>
                                <?php } ?>
                              </ul>
                          </div>
                          <div class="categories">
                              <h2>Categories</h2>
                              <ul>
                                <?php foreach(get_categories() as $category) { ?>
                                  <li>
                                        <label>
                                            <input type="checkbox" name="discipleship_categories[<?= $category->cat_ID ?>]" <?php echo ! empty(get_option('discipleship_categories')[$category->cat_ID]) ? 'checked=checked' : ''; ?> /><?= $category->name; ?>
                                        </label>
                                    </li>
                                <?php } ?>
                              </ul>
                          </div>
                      </div>
                  </td>
              </tr>
          </tbody>
        </table>    
    <?php submit_button(); ?>
    </form>

    <style>
    .discipleship-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 30px;
    }
    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: #2196F3;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
    </style>

    <?php
}

