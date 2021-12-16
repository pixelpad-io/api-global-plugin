<?php

/**
 * Plugin Name: PixelPAD Global Plugin
 * Date: Nov 2021
 * Version: 1.0.0
 * Description: Files used in all pixelpad subdomains should be here. Maybe we should move the global plugin to the THEME in the future
 * Text Domain: global-plugin
 * Author: pixelpad.io
 */



define("GLOBAL_PLUGIN_URL",  plugin_dir_url(__FILE__));
define("GLOBAL_PLUGIN_DIR",  plugin_dir_path(__FILE__));

require_once(GLOBAL_PLUGIN_DIR . "class-updater.php");
$updater = new \Updater();

add_action("init", function () {
    /**
     * load necessary files
     */
    require_once(GLOBAL_PLUGIN_DIR . "class-custom-post.php");
    require_once(GLOBAL_PLUGIN_DIR . "class-bloat.php");
    require_once(GLOBAL_PLUGIN_DIR . "class-rest.php");
    require_once(GLOBAL_PLUGIN_DIR . "class-style.php");
    
}, 1);

/**
 * run the classes
 */
add_action("rest_api_init", "REST::registerMeta");
add_action("admin_menu", "Bloat::removeMenuItems");
add_action("admin_head", "Style::load");



