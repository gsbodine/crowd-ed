<?php

/*
 * @version $Id$
 * @package CrowdEd
 */

defined('CROWDED_DIR') or define('CROWDED_DIR',dirname(__FILE__));
define('CROWDED_PLUGIN_VERSION', get_plugin_ini(CROWDED_DIR, 'version'));
define('CROWDED_USER_ROLE','crowd-editor');

require_once CROWDED_DIR . '/helpers/CrowdedFormFunctions.php';
require_once CROWDED_DIR . '/helpers/CrowdedElementForm.php';
require_once 'CrowdEdPlugin.php';

add_filter(array('Form', 'Item', 'Dublin Core', 'Date'),'crowded_form_item_date_filter');
add_filter(array('Flatten','Item','Dublin Core','Date'),'crowded_element_item_date_filter');
add_filter('admin_navigation_main','CrowdEdPlugin::adminNavigationMain');
add_plugin_hook('public_theme_footer', 'crowded_public_theme_footer');

function crowded_public_theme_footer() {
    echo '<p class="pull-right"><small>Crowdsourcing provided by the <a href="http://gsbodine.github.com/crowd-ed">Crowd-Ed plugin</a></small></p>';
}

function crowded_form_item_date_filter($html, $inputNameStem, $value, $options, $item, $element) {
 $list = explode('-', $value);
        
        if (count($list) == 3) {
            $year = $list[0];
            $month = $list[1];
            $day = $list[2];
        } else if (count($list) == 2) {
            $year = '';
            $month = $list[0];
            $day = $list[1];
        } else if (count($list) == 1 && strlen($list[0]) == 4){
            $year = $list[0];
            $month = '';
            $day = '';
        } else {
            $year = '';
            $month = $list[0];
            $day = '';
        }
        
        $years = array(''=>'');
        $curYear = date('Y');
        for ( $i = 1900; $i <= $curYear; $i++) {
            $years[$i] = $i;  
        }
        
        $days = array(''=>'');
        for ( $i = 1; $i < 32; $i++) {
            $days[$i] = $i;
        }
        
        //$html = '<div>value: ' . $value . '<br />list count: ' . count($list) . '<br /> first list item: ' . $list[0] . '</div>';
        $html = '<div class="form-inline dateinput">';
        //$html .= ' Month: ' . $this->view->formText($inputNameStem . '[month]', $month, array('class'=>'textinput input-mini', 'maxlength'=>'2'));
        $html .= 'Month: ' . __v()->formSelect($inputNameStem . '[text][month]',$month, array('class'=>'input-medium'), array('' => '','1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December'));
        $html .= ' Day: ' . __v()->formSelect($inputNameStem . '[text][day]', $day, array('class'=>'textinput input-mini'), $days);
        $html .= ' Year: ' . __v()->formSelect($inputNameStem . '[text][year]', $year, array('class'=>'textinput input-small'), $years);
        
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

