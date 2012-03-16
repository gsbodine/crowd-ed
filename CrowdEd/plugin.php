<?php

/*
 * @version $Id$
 * @package CrowdEd
 */

defined('CROWDED_DIR') or define('CROWDED_DIR',dirname(__FILE__));
define('CROWDED_PLUGIN_VERSION', get_plugin_ini(CROWDED_DIR, 'version'));
define('CROWDED_USER_ROLE','crowd-editor');

//require_once CROWDED_DIR . '/helpers/CrowdedElementFormFunctions.php';
require_once CROWDED_DIR . '/helpers/CrowdedFormFunctions.php';
require_once CROWDED_DIR . '/helpers/CrowdedItemForm.php';
require_once 'CrowdEdPlugin.php';

$crowdEdPlugin = new CrowdEdPlugin;
$crowdEdPlugin->setUp();

