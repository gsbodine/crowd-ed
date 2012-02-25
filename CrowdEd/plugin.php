<?php

/*
 * @version $Id$
 * @package CrowdEd
 */

defined('CROWDED_DIR') or define('CROWDED_DIR',dirname(__FILE__));

define('CROWDED_PLUGIN_VERSION', get_plugin_ini(CROWDED_DIR, 'version'));
define('CROWDED_USER_ROLE','crowd-ed');

// Hooks
add_plugin_hook('install', 'crowded_install');
add_plugin_hook('uninstall', 'crowded_uninstall');
add_plugin_hook('upgrade', 'crowded_upgrade');
//add_plugin_hook('define_acl', 'crowded_define_acl');
//add_plugin_hook('config_form', 'crowded_config_form');
add_plugin_hook('define_routes','crowded_define_routes');
add_plugin_hook('initialize', 'crowded_add_controller');
add_plugin_hook('public_append_to_items_show', 'crowded_participate_item');
add_plugin_hook('public_theme_body','crowded_user_status');
add_plugin_hook('public_theme_header','crowded_queue_css');

// Other Plugin Hooks
add_plugin_hook('html_purifier_form_submission', 'crowded_filter_html');

// Filters
// add_filter(array('Form', 'Item', 'Dublin Core', 'Description'), 'crowded_description_formfield');
// add_filter(array('Form', 'Item', 'Dublin Core', 'Date'), 'crowded_date_formfield');

require_once CROWDED_DIR . '/helpers/CrowdedElementFormFunctions.php';
require_once CROWDED_DIR . '/helpers/CrowdedFormFunctions.php';
require_once CROWDED_DIR . '/functions.php';

function crowded_define_routes($router) {
     $router->addConfig(new Zend_Config_Ini(CROWDED_DIR . DIRECTORY_SEPARATOR . 'routes.ini', 'routes'));
}

function crowded_add_controller() {
    require_once 'CrowdEdControllerPlugin.php';
    Zend_Controller_Front::getInstance()->registerPlugin(new CrowdEdControllerPlugin);
}

function crowded_install() {
    set_option('crowded_plugin_version', CROWDED_PLUGIN_VERSION);
    $db = get_db();
    // TODO: Set up DB tables for all the Crowd-Ed specific junk
}

function crowded_uninstall() {
    delete_option('crowded_plugin_version');
    $db = get_db();
    // TODO: DROP all tables created for the purpose of Crowd-ed
}

function crowded_setup_acl($acl) {
    $acl->addRole(new Omeka_Acl(CROWDED_USER_ROLE));
    $acl->loadResourceList(); // TODO: Finish up the resource list for Crowd-Ed users
}

function crowded_config_form() {
    include "config_form.php";
}

function crowded_date_formfield($html, $inputNameStem, $date) {
    return __v()->formSelect($inputNameStem . '[Date]', $date, null, array());
}

function crowded_description_formfield($html, $inputNameStem, $description) {
    return __v()->formSelect($inputNameStem . '[textarea]', $description, null, array());
}