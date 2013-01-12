<?php

/**
 * Description of CrowdEd
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

defined('CROWDED_DIR') or define('CROWDED_DIR',dirname(__FILE__));
defined('CROWDED_PLUGIN_VERSION') or define('CROWDED_PLUGIN_VERSION', get_plugin_ini(CROWDED_DIR, 'version'));
defined('CROWDED_USER_ROLE') or define('CROWDED_USER_ROLE','crowd-editor');

class CrowdEdPlugin extends Omeka_Plugin_AbstractPlugin {
    
    protected $_hooks = array(
        'install',
        'uninstall',
        'config',
        'config_form',
        'initialize',
        'public_head',
        'public_footer',
        'public_body',
        'public_items_show',
        'define_routes',
        'define_acl',
        'after_save_item',
        'admin_item_form'
    );
    
    protected $_filters = array(
        'public_navigation_main',
        'public_navigation_admin_bar',
        
        'admin_navigation_main',
        
        'item_citation',
        
        'crowdedDateFlatten' => array('Flatten','Item','Dublin Core','Date'),
        
        'crowdedType' => array('ElementForm','Item','Dublin Core','Type'),
        'crowdedTypeInputs' => array('ElementInput','Item','Dublin Core','Type'),
        
        'crowdedScriptType' => array('ElementForm','Item','Item Type Metadata','Script Type'),
        'crowdedScriptTypeInputs' => array('ElementInput','Item','Item Type Metadata','Script Type'),
        
        'crowdedDate' => array('ElementForm','Item','Dublin Core','Date'),
        'crowdedDateInputs' => array('ElementInput','Item','Dublin Core','Date'),
        
        'crowdedTitle' => array('ElementForm','Item','Dublin Core','Title'),
        'crowdedTitleInputs' => array('ElementInput','Item','Dublin Core','Title'),
        
        'crowdedDescription' => array('ElementForm','Item','Dublin Core','Description'),
        'crowdedDescriptionInputs' => array('ElementInput','Item','Dublin Core','Description'),
        
        'crowdedCreator' => array('ElementForm','Item','Dublin Core','Creator'),
        'crowdedCreatorInputs' => array('ElementInput','Item','Dublin Core','Creator'),
        
        'crowdedRecipient' => array('ElementForm','Item','Item Type Metadata','Recipient'),
        'crowdedRecipientInputs' => array('ElementInput','Item','Item Type Metadata','Recipient'),
        
        'crowdedFlag' => array('ElementForm','Item','Crowdsourcing Metadata','Flag for Review'),
        'crowdedFlagInputs' => array('ElementInput','Item','Crowdsourcing Metadata','Flag for Review')    
    );
    
    public function setUp() {
        parent::setUp();
    }
     
    public function hookInstall() {
        set_option('crowded_plugin_version', CROWDED_PLUGIN_VERSION);
        //$db = get_db();
        // TODO: Set up DB tables for all the Crowd-Ed specific junk
    }
    
    public function hookUninstall() {
        delete_option('crowded_plugin_version');
        //$db = get_db();
        // TODO: DROP all tables created for the purpose of Crowd-ed
    }
    
    public function hookConfigForm() {
        include "config_form.php";
    }
    
    public function hookConfig($args) {
        set_option('crowded_require_terms_of_service', htmlspecialchars($_POST['crowded_require_terms_of_service']));
        set_option('crowded_terms_of_service',  htmlspecialchars($_POST['crowded_terms_of_service']));
        
    }
    
    public function hookInitialize() {
        Zend_Controller_Front::getInstance()->registerPlugin(new CrowdEd_Controller_Plugin_Security);
        Zend_Controller_Front::getInstance()->registerPlugin(new CrowdEd_Controller_Plugin_SelectFilter);
        get_view()->addHelperPath(dirname(__FILE__) . '/views/helpers', 'CrowdEd_View_Helper_');
    }
    
    public function hookDefineRoutes() {
        /* if (is_admin()) {
            return;
        } */

        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->addConfig(new Zend_Config_Ini(CROWDED_DIR . DIRECTORY_SEPARATOR . 'routes.ini', 'routes'));
    }
  
    public function hookAdminHead($args) {
        queue_css_file('crowded');
        queue_js_file('crowded');
    }
    
    public function hookPublicHead($args) {
        $view = $args['view'];
        $view->addHelperPath(CROWDED_DIR . '/helpers', 'CrowdEd_View_Helper_');
        
        queue_css_file(array('crowded','bootstrap-tagmanager'));
        queue_js_file(array('crowded','bootstrap-tagmanager'));
        
        if (plugin_is_active('Geolocation')) {
            $view = $args['view'];
            $view->addHelperPath(GEOLOCATION_PLUGIN_DIR . '/helpers', 'Geolocation_View_Helper_');
            queue_css_file('geolocation-items-map');
            queue_css_file('geolocation-marker');
            queue_js_url("http://maps.google.com/maps/api/js?sensor=false");
            queue_js_file('map');        
        }
        
    }

    public function hookPublicItemsShow(){
       $this->_crowded_participate_item();
    }

    public function hookPublicBody() {
        $this->_crowded_user_bar();
    }
    
    public function hookPublicFooter($args) {
        echo '<p class="pull-right"><small>Crowdsourcing provided by the <a href="http://gsbodine.github.com/crowd-ed"><i class="icon-github"></i> Crowd-Ed plugin</a></small></p>';
    }
    
    public function hookDefineAcl($args) {
        $acl = $args['acl'];
        
        $participateResource = new Zend_Acl_Resource('CrowdEd_Participate');
        $acl->addResource($participateResource);
        $acl->allow(null, $participateResource, array('index','join','login','logout','forgot-password'));
        
        $crowdEditor = new Zend_Acl_Role(CROWDED_USER_ROLE);
        $acl->addRole($crowdEditor);
        $acl->allow($crowdEditor,array($participateResource,'Items','Tags','Search')); //todo: refine crowd-editor permissions
    }
    
    public function hookAfterSaveItem($args) {
        //$id = $args['id'];
        
    }
    
    public function hookAdminItemForm($args) {
        $form = $args['form'];
        $item = $args['record'];
        $html = '<h1>Placeholder for Edit-Locking functionality.</h1>';
        return $html;
    }
    
    public function crowdedTypeInputs($components,$args) {
        $components['form_controls'] = null;
        $components['html_checkbox'] = null;
        return $components; 
    }
    
    public function crowdedType($components,$args) {
        $components['label'] = 'Item Type';
        $components['description'] = trim($components['description']);
        $components['html'] = $this->_setUpFormElement($components,$args,3,'<i class="icon-file"></i> ');
        return $components;
    }
    
    public function crowdedScriptTypeInputs($components,$args) {
        $components['form_controls'] = null;
        $components['html_checkbox'] = null;
        return $components; 
    }
    
    public function crowdedScriptType($components,$args) {
        $components['label'] = 'Document Script Type';
        $components['description'] = trim($components['description']);
        $components['html'] = $this->_setUpFormElement($components,$args,3,'<i class="icon-pencil"></i> ');
        return $components;
    }
    
    public function crowdedDateInputs($components,$args) {
        $list = explode('-', $args['value']);
        
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
        
        $html = '<div class="form-inline dateinput">';
        $html .= 'Month: ' . get_view()->formSelect($args['input_name_stem'] . '[text][month]',$month, array('class'=>'input-medium'), array('' => '','1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December'));
        $html .= ' Day: ' . get_view()->formSelect($args['input_name_stem'] . '[text][day]', $day, array('class'=>'textinput input-mini'), $days);
        $html .= ' Year: ' . get_view()->formSelect($args['input_name_stem'] . '[text][year]', $year, array('class'=>'textinput input-small'), $years);
        
        $html .= '</div>';
        
        $components['html'] = $html;
        return $components;
    }
    
    public function crowdedDate($components,$args) { 
        $components['label'] = 'Document Date';
        $components['description'] = trim($components['description']);
        $components['html'] = $this->_setUpFormElement($components,$args,6);
        return $components;
    }

    public function crowdedTitleInputs($components,$args) {
        $components['html'] = get_view()->formText($args['input_name_stem'].'[text]', $args['value'],array('class'=>'span6'));
        $components['form_controls'] = null;
        $components['html_checkbox'] = null;
        return $components;
    }
    
    public function crowdedTitle($components,$args) {
        $components['label'] = 'Document Title';
        $components['description'] = trim($components['description']);
        $components['html'] = $this->_setUpFormElement($components,$args);
        return $components;
    }
    
    public function crowdedDescription($components,$args) {
        $components['label'] = 'General Description';
        $components['description'] = trim($components['description']);
        $components['html'] = $this->_setUpFormElement($components,$args,6);
        return $components;
    }
    
    public function crowdedDescriptionInputs($components,$args) {
        $components['html'] = get_view()->formTextarea($args['input_name_stem'].'[text]', $args['value'], array('class'=>'span6','rows'=>'3'));
        $components['form_controls'] = null;
        $components['html_checkbox'] = null;
        return $components; 
    }
    
    public function crowdedCreator($components,$args) {
        $components['label'] = 'Document Author(s)';
        $components['description'] = trim($components['description']);
        $components['html'] = $this->_setUpFormElement($components,$args,6,'<i class="icon-user"></i> ');
        return $components;
    }
    
    public function crowdedCreatorInputs($components,$args) {
        $components['form_controls'] = null;
        $components['html_checkbox'] = null;
        return $components; 
    }
    
    public function crowdedRecipient($components,$args) {
        $components['label'] = 'Document Recipient(s)';
        $components['description'] = trim($components['description']);
        $components['html'] = $this->_setUpFormElement($components,$args,6,'<i class="icon-user"></i> ');
        return $components;
    }
    
    public function crowdedRecipientInputs($components,$args) {
        $components['form_controls'] = null;
        $components['html_checkbox'] = null;
        return $components; 
    }
    
    public function crowdedFlagInputs($components,$args) {
        $components['form_controls'] = null;
        $components['html_checkbox'] = null;
        return $components; 
    }
    
    public function crowdedFlag($components,$args) {
        $components['label'] = 'Flag Document for Review';
        $components['description'] = trim($components['description']);
        $components['html'] = $this->_setUpFormElement($components,$args,6,'<i class="icon-flag"></i> ');
        return $components;
    }
    
    public function filterPublicNavigationMain($navArray) {
        $navArray[] = array('label'=> __('Community'),
                       'uri' => url('community')
                      );
        $navArray[] = array('label'=> __('Participate'),
                        'uri' => url('participate')
                      );
        return $navArray;
    }
    
    public function filterAdminNavigationMain($nav) {
    $nav[] = array(
                    'label' => 'Crowd-Ed',
                    'uri' => url('crowd-ed')
                  );
    return $nav;
    } 
    
    public function filterItemCitation($citation, $args) {
        $citation = get_view()->itemCitation($args['item']); 
        return $citation;
    }
       
    
    public function crowdedDateFlatten($components,$args) {
        $day = $args['post_array']['text']['day'];
        $month = $args['post_array']['text']['month'];
        $year = $args['post_array']['text']['year'];
        
        $flatText = $year . '-' . $month . '-' . $day;
        return $flatText;
        
    }                    
    
    private function _getHelpText($text) {
        $helpText = '';
        if ($text) {
            $helpText = ' <a class="helpText" href="#" rel="tooltip" title="' . html_escape($text) .'" data-placement="right"><i class="icon-question-sign"></i></a>';
        }
        return $helpText;
    }
    
    private function _setUpFormElement($components,$args,$columns=3,$labelIcon='') {
        $html = '';
        $html .= '<div class="span'. $columns .'">';
        $html .= '<label>'.$labelIcon.' '.$components['label'].'</label>';
        $html .= $this->_getHelpText($components['comment']);
        $html .= '<div>' . $components['inputs'] . '</div>';
        $html .= '</div>';
        return $html;
    }
    
    public function filterPublicNavigationAdminBar($args) {
        $args = null;
        return $args;
    }
    
    private function _crowded_participate_item() {
        $item = get_current_record('item');
        echo("<hr /><h4><i class=\"icon-edit icon-large\"></i> Participate</h4><div><a href=\"/participate/edit/". $item->id ."\">Assist us with editing and cataloging this item!</a></div>");
    }
    
    private function _crowded_user_bar() {
        $user = current_user();
        $content = '<div class="container"><div class="navbar navbar-static-top"><div class="navbar-inner"><div class="brand">Crowd-Ed</div><ul class="nav pull-right">';
        if ($user) {
            $content .= "<li><a href=\"/participate/profile/". $user->id . "\"><i class=\"icon-user\"></i> " . $user->username . "</a></li><li><a href=\"" . url(array('action'=>'logout', 'controller'=>'users'), 'default') . "\"><i class=\"icon-off\"></i> Logout</a></li>";
        } else {
            $content .= "<li><a href=\"/participate/login\"><i class=\"icon-signin\"></i> Log in</a></li><li><a href=\"/participate/join\"><i class=\"icon-cog\"></i> Create Account</a></li>";
        }
        $content .= '</ul></div></div></div>';
        echo $content;
    }
}
?>
