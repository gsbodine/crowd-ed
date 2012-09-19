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
require_once CROWDED_DIR . '/helpers/CrowdedElementForm.php';
require_once 'CrowdEdPlugin.php';

//TODO: clean up these filters -- don't think they're used anymore...
add_filter(array('Form', 'Item', 'Dublin Core', 'Date'),'crowded_form_item_date_filter');
add_filter(array('Flatten','Item','Dublin Core','Date'),'crowded_element_item_date_filter');

add_filter('admin_navigation_main','CrowdEdPlugin::adminNavigationMain');

function crowded_form_item_date_filter($html, $inputNameStem, $value, $options, $item, $element) {
 
        $list = explode('-', $value);
        
        $year = $list[0];
        $month = $list[1];
        $day = $list[2];
        
        //$html = '<div>value: ' . $value . '<br />list count: ' . count($list) . '<br /> first list item: ' . $list[0] . '</div>';
        $html = '<div class="dateinput">';
        $html .= 'Month: ' . __v()->formText($inputNameStem . '[text][month]', $month, array('class'=>'textinput', 'size'=>'2'));
        $html .= ' Day: ' . __v()->formText($inputNameStem . '[text][day]', $day, array('class'=>'textinput', 'size'=>'2'));
        $html .= ' Year: ' . __v()->formText($inputNameStem . '[text][year]', $year, array('class'=>'textinput', 'size'=>'4'));
        $html .= '</div>';

        return $html;
    }
    
    function crowded_element_item_date_filter($flatText,$postArray, $element) {
        $day = $postArray['text']['day'];
        $month = $postArray['text']['month'];
        $year = $postArray['text']['year'];
        
        $flatText = $year . '-' . $month . '-' . $day;
        return $flatText;
    }
    

$crowdEdPlugin = new CrowdEdPlugin;
$crowdEdPlugin->setUp();

