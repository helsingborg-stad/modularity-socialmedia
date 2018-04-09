<?php

/**
 * Plugin Name:       Modularity Social Media Feed
 * Plugin URI:        https://github.com/helsingborg-stad/modularity-socialmedia
 * Description:       Get and display a combined feed from multiple social media sources
 * Version:           1.0.0
 * Author:            Sebastian Thulin
 * Author URI:        https://github.com/helsingborg-stad
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       modularity-socialmedia
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

//Define paths
define('MODULARITYSOCIALMEDIA_PATH', plugin_dir_path(__FILE__));
define('MODULARITYSOCIALMEDIA_URL', plugins_url('', __FILE__));
define('MODULARITYSOCIALMEDIA_TEMPLATE_PATH', MODULARITYSOCIALMEDIA_PATH . 'templates/');
define('MODULARITYSOCIALMEDIA_MODULE_PATH', MODULARITYSOCIALMEDIA_PATH . 'source/php/');

//Translations
load_plugin_textdomain('modularity-socialmedia', false, plugin_basename(dirname(__FILE__)) . '/languages');

//Require vendor data
require_once MODULARITYSOCIALMEDIA_PATH . 'vendor/autoload.php';
require_once MODULARITYSOCIALMEDIA_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once MODULARITYSOCIALMEDIA_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new ModularitySocialMedia\Vendor\Psr4ClassLoader();
$loader->addPrefix('ModularitySocialMedia', MODULARITYSOCIALMEDIA_PATH);
$loader->addPrefix('ModularitySocialMedia', MODULARITYSOCIALMEDIA_PATH . 'source/php/');
$loader->register();

// Acf auto import and export
add_action('plugins_loaded', function () {
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('modularity-testimonials');
    $acfExportManager->setExportFolder(MODULARITYSOCIALMEDIA_PATH . 'source/php/acf-fields/');
    $acfExportManager->autoExport(array(
        'mod-socialmedia' => 'group_56dedc26e5388',
    ));
    $acfExportManager->import();
});

/**
 * Registers the module
 */
add_action('plugins_loaded', function () {
    if (function_exists('modularity_register_module')) {
        modularity_register_module(
            MODULARITYSOCIALMEDIA_MODULE_PATH,
            'App'
        );
    }
});

add_action('init', function () {
    new ModularitySocialMedia\Ajax();
    new ModularitySocialMedia\Enqueue();
});
