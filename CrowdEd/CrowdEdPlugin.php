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
        'admin_item_form',
        'admin_items_panel_fields',
        'admin_items_batch_edit_form',
        'items_batch_edit_custom',
        'admin_items_search',
        'admin_items_browse_simple_each'
    );
    
    protected $_filters = array(
        
        'public_navigation_main',
        'public_navigation_admin_bar',
        
        'admin_navigation_main',
        
        'item_citation',
        
        'item_search_filters',
        
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
        
        'crowdedRecipient' => array('EditElements','Item','Item Type Metadata','Recipient'),
        'crowdedRecipientInputs' => array('ElementInput','Item','Item Type Metadata','Recipient'),
        
        'crowdedFlag' => array('ElementForm','Item','Crowdsourcing Metadata','Flag for Review'),
        'crowdedFlagInputs' => array('ElementInput','Item','Crowdsourcing Metadata','Flag for Review')    
    );
    
    public function setUp() {
        parent::setUp();
    }
     
    public function hookInstall() {
        set_option('crowded_plugin_version', CROWDED_PLUGIN_VERSION);
        $db = $this->_db;
        $editStatusSQL = "CREATE TABLE `edit_statuses` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `status` varchar(45) NOT NULL,
            `description` text,
            `isLockedStatus` bit(1) DEFAULT b'0',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf-8;";

        $editStatusesItemsSQL = "CREATE TABLE `edit_statuses_items` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `item_id` int(11) NOT NULL,
            `edit_status_id` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `item_id_UNIQUE` (`item_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        $db->query($editStatusSQL);
        $db->query($editStatusesItemsSQL);
        
        // TODO: CREATE all the other table scripts
          
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
        //$this->_crowded_user_bar();
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
        if (is_admin_theme()){
            if ($args['post']['edit_statuses_id']) {
                $editStatusItem = new EditStatusItems();
                $editStatusItem->edit_status_id = $args['post']['edit_statuses_id'];
                $editStatusItem->item_id = $args['record']->id;
                $editStatusItem->save();
            } 
        }
    }
    
    public function hookAdminItemForm($args) {
        $form = $args['form'];
        $item = $args['record'];
        $html = '<h1>Placeholder for Edit-Locking functionality.</h1>';
        return $html;
    }
    
    public function hookAdminItemsPanelFields($args) {
        $html = '<div id="edit-status-form" class="field">';
        $html .=  $args['view']->formLabel('edit-statuses-id', __('Edit Status'));
        $html .= '<div class="inputs">';
        
        $editStatusItem = new EditStatusItems();
        $itemStatus = $editStatusItem->getItemEditStatus($args['record']);
        if (!$itemStatus) {
            $statusId = 0; 
        } else {
            $statusId = $itemStatus->edit_status_id;
        }
        $html .= $args['view']->formSelect('edit_statuses_id', $statusId, array('id' => 'edit-statuses-id'), get_table_options('EditStatus'));
        $html .= '</div></div>';
        echo $html;
    }
    
    public function hookAdminItemsBatchEditForm($args) {
        $html = '<div id="custom-edit-statuses-form" class="field">';
        $html .=  $args['view']->formLabel('custom[edit_statuses_id]', __('Edit Status'),array('class'=>'two columns alpha'));
        $html .= '<div class="inputs five columns omega">';
        $html .= $args['view']->formSelect('custom[edit_statuses_id]', '', array('id' => 'custom-edit_statuses_id'), get_table_options('EditStatus'));
        $html .= '</div></div>';
        echo $html;
    }
    
    public function hookAdminItemsSearch($args) {
        $view = $args['view'];
        $html = '<div class="field"><div class="two columns alpha">'
          . $view->formLabel('edit_status_id', __('Search by Editing Status'))
          . '</div><div class="five columns omega inputs">'
          . $view->formSelect('edit_status_id', @$_GET['edit_status_id'], array(), get_table_options('EditStatus'))
          . '</div></div>';
        echo $html;
    }
    
    public function filterItemSearchFilters($displayArray, $args) {
        $request_array = $args['request_array'];
        if (isset($request_array['edit_status_id'])) {
            $db = get_db();
            $editStatusItems = $db->getTable('EditStatusItems')->findBy($params = array('edit_status_id'=>$request_array['edit_status_id']));
            $displayValue = array();
            foreach ($editStatusItems as $esi) {
                $displayValue = 'Pending';
            }
            $displayArray = null;
            $displayArray['edit_status_id'] = $displayValue;
        }
        return $displayArray;
    }
    
    public function hookAdminItemsBrowseSimpleEach($args) {
        $esi = new EditStatusItems();
        $item = $args['item'];
        $e = $esi->getItemEditStatus($item);
        if ($e) {
            $args['edit_status_id'] = $e->status;
        } else {
            $args['edit_status_id'] = 'Unedited';
        }
        return $args;
    }
    
    
    public function hookItemsBatchEditCustom($args) {
        $item = $args['item'];
        $custom = $args['custom'];
        $editStatusItem = new EditStatusItems();
        $editStatusItem->edit_status_id = $custom['edit_statuses_id'];
        $editStatusItem->item_id = $item->id;
        $editStatusItem->save();
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
        if (!is_admin_theme()) {
            $components['html'] = get_view()->formText($args['input_name_stem'].'[text]', $args['value'],array('class'=>'span6'));
            $components['form_controls'] = null;
            $components['html_checkbox'] = null;
        }
        return $components;
   
    }
    
    public function crowdedTitle($components,$args) {
        if (!is_admin_theme()) {
            $components['label'] = 'Document Title';
            $components['description'] = trim($components['description']);
            $components['html'] = $this->_setUpFormElement($components,$args);
        } 
        return $components;
        
    }
    
    public function crowdedDescription($components,$args) {
        if (!is_admin_theme()) {
            $components['label'] = 'General Description';
            $components['description'] = trim($components['description']);
            $components['html'] = $this->_setUpFormElement($components,$args,6);
        }
        return $components;
    }
    
    public function crowdedDescriptionInputs($components,$args) {
        if (!is_admin_theme()) {
            $components['html'] = get_view()->formTextarea($args['input_name_stem'].'[text]', $args['value'], array('class'=>'span6','rows'=>'3'));
            $components['form_controls'] = null;
            $components['html_checkbox'] = null;
        }
        return $components; 
    }
    
    public function crowdedCreator($components,$args) {
        if (!is_admin_theme()) {
            $components['label'] = 'Document Author(s)';
            $components['description'] = trim($components['description']);
        }
        return $components;
    }
    
    public function crowdedCreatorInputs($components,$args) {
        if (!is_admin_theme()) {
            $components['html'] = $this->_setUpFormElement($components,$args,6,'<i class="icon-user"></i> ');
            $components['form_controls'] = null;
            $components['html_checkbox'] = null;
        }
        return $components; 
    }
    
    public function crowdedRecipient($components,$args) {
        if (!is_admin_theme()) {
            $components['label'] = 'Document Recipient(s)';
            $components['description'] = trim($components['description']);
            $components['html'] = $this->_setUpFormElement($components,$args,6,'<i class="icon-user"></i> ');
        }
        return $components;
    }
    
    public function crowdedRecipientInputs($components,$args) {
        if (!is_admin_theme()) {
            $components['form_controls'] = null;
            $components['html_checkbox'] = null;
        }
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
        // overwrite basic public nav admin bar args
        return $this->_crowded_user_bar();
    }
    
    private function _crowded_participate_item() {
        $item = get_current_record('item');
        
        $esi = new EditStatusItems();
        $status = $esi->getItemEditStatus($item);
        if ($status) {
            $es = new EditStatus();
            $lockStatus = $es->getLockedStatus($status->edit_status_id);
        } else {
            $lockStatus = 0;
        }
        
        $html = '<hr /><h4><i class="icon-edit"></i> Participate</h4>';
        if ($lockStatus == 0) {
            $html .= '<div><a href="/participate/edit/'. $item->id .'">Assist us with editing and cataloging this item!</a></div>';
        } else {
            $html .= '<div><p class="alert alert-info"><i class="icon-lock"></i> This item has already been edited and is now locked.</p></div>';
            $user = current_user();
            if ($user && ($user->role == 'admin' || $user->role == 'super')) {
                $html .= '<p><strong>As an administrative user, <a href="/participate/edit/'. $item->id .'">you may still edit this item</a>.</p>';
            }  else {
                $html .= '<p><a href="/participate/random"><strong>How about trying an unedited item?</strong></a></p>';
            }
        }
        
        echo $html;
        
    }
    
    private function _crowded_user_bar() {
        $user = current_user();
        $content = '<div class="navbar navbar-fixed-top"><div id="crowded-navbar" class="navbar-inner"><div class="brand" style="margin: 0;">Crowd-Ed</div><ul class="nav pull-right">';
        if ($user) {
            $content .= '<li><a href="/participate/profile/' . $user->id . '"><i class="icon-user"></i> ' . $user->username . '</a></li><li><a href="' . url(array('action'=>'logout', 'controller'=>'users'), 'default') . '"><i class="icon-off"></i> Logout</a></li>';
        } else {
            $content .= '<li><a href="/users/login"><i class="icon-signin"></i> Log in</a></li><li><a href="/participate/join"><i class="icon-cog"></i> Create Account</a></li>';
        }
        $content .= '</ul></div></div>';
        echo $content;
    }
}
?>
