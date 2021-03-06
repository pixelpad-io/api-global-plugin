<?php

/**
 * Plugin Name: PixelPAD Global Plugin
 * Date: Nov 2021
 * Version: 1.0.21
 * Description: Files used in all pixelpad subdomains should be here. Maybe we should move the global plugin to the THEME in the future
 * Text Domain: global-plugin
 * Author: pixelpad.io
 * //make sure you update the version number in the info.json file as well
 * //to update this, update the global plugin and push to git. then pull changes into other subdomains
 */

define("GLOBAL_PLUGIN_URL",  plugin_dir_url(__FILE__));
define("GLOBAL_PLUGIN_DIR",  plugin_dir_path(__FILE__));

/**
 * load necessary files
 */
add_action("init", function () {
    require_once(GLOBAL_PLUGIN_DIR . "class-custom-post.php");
    require_once(GLOBAL_PLUGIN_DIR . "class-bloat.php");
    require_once(GLOBAL_PLUGIN_DIR . "class-rest.php");
    require_once(GLOBAL_PLUGIN_DIR . "class-style.php");
    require_once(GLOBAL_PLUGIN_DIR . "class-updater.php");
    require_once(GLOBAL_PLUGIN_DIR . "class-import.php");
    
}, 1);


/**
 * run the classes
 */
add_action("rest_api_init", "REST::registerMeta");
add_action("admin_menu", "Bloat::removeMenuItems");
add_action("admin_enqueue_scripts", "\CUSTOMPOSTS\Style::load");
add_action("admin_menu", "\PIXELPAD\Import::add_admin_menu");
add_action("admin_init", "\PIXELPAD\Import::get_xml_post");
add_action("admin_init", function () {
    $updater = new \CUSTOMPOSTS\Updater();
});