<?php

/**
 * Plugin Name:       Modularity Tiles
 * Plugin URI:        https://github.com/helsingborg-stad/modularity-tiles
 * Description:       Display tile-style post/page grid
 * Version:           1.0.0
 * Author:            Nikolas Ramstedt
 * Author URI:        https://github.com/helsingborg-stad
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       modularity-tiles
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('MODULARITYTILES_PATH', plugin_dir_path(__FILE__));
define('MODULARITYTILES_URL', plugins_url('', __FILE__));
define('MODULARITYTILES_TEMPLATE_PATH', MODULARITYTILES_PATH . 'templates/');
define('MODULARITYTILES_MODULE_PATH', MODULARITYTILES_PATH . 'source/php/Module');


load_plugin_textdomain('modularity-tiles', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once MODULARITYTILES_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once MODULARITYTILES_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new ModularityTiles\Vendor\Psr4ClassLoader();
$loader->addPrefix('ModularityTiles', MODULARITYTILES_PATH);
$loader->addPrefix('ModularityTiles', MODULARITYTILES_PATH . 'source/php/');
$loader->register();

// Start application
new ModularityTiles\App();

// Acf auto import and export
add_action('plugins_loaded', function () {
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('modularity-testimonials');
    $acfExportManager->setExportFolder(MODULARITYTILES_PATH . 'source/php/acf-fields/');
    $acfExportManager->autoExport(array(
        'modularity-tiles' => 'group_59fc24ec4bb83'
    ));
    $acfExportManager->import();
});

/**
 * Registers the module
 */
add_action('plugins_loaded', function () {
    if (function_exists('modularity_register_module')) {
        modularity_register_module(
            MODULARITYTILES_MODULE_PATH ."/Tiles/",
            'Tiles'
        );
    }
});
