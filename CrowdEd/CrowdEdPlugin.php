<?php

/**
 * Description of CrowdEd
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

require_once 'Omeka/Plugin/Abstract.php';

class CrowdEdPlugin extends Omeka_Plugin_Abstract {
    
    protected $_hooks = array(
        'install',
        'uninstall',
        'upgrade',
        'config_form',
        'initialize',
        'public_theme_header',
        'public_theme_body',
        'public_append_to_items_show',
        'define_routes',
        'after_save_item',
        'before_save_form_item',
        'after_save_form_item',
        'after_delete_item',
        'after_validate_item',
        'define_acl'
    );
    
    public function hookInstall() {
        set_option('crowded_plugin_version', CROWDED_PLUGIN_VERSION);
        $db = get_db();
        // TODO: Set up DB tables for all the Crowd-Ed specific junk, if there is gonna be any...
    }
    
    public function hookUninstall() {
        delete_option('crowded_plugin_version');
        $db = get_db();
        // TODO: DROP all tables created for the purpose of Crowd-ed
    }
    
    public function hookUpgrade() {
        
    }
    
    public function hookConfigForm() {
        include "config_form.php";
    }
    
    public function hookInitialize() {
        Zend_Controller_Front::getInstance()->registerPlugin(new CrowdEd_Controller_Plugin_Security);
        Zend_Controller_Front::getInstance()->registerPlugin(new CrowdEd_Controller_Plugin_SelectFilter);
    }
    
    public function hookDefineRoutes($router) {
        $router->addConfig(new Zend_Config_Ini(CROWDED_DIR . DIRECTORY_SEPARATOR . 'routes.ini', 'routes'));
    }
 
    public function hookBeforeSaveFormItem($item) {
       
    }
    
    public function hookAfterSaveItem($item) {

    }
    
    public function hookAfterSaveFormItem($item) {
       
    }
    
    public function hookAfterDeleteItem($item) {
        
    }
    
    public function hookAfterValidateItem($item) {
        
    }
    
    public function hookPublicThemeHeader() {
        queue_css(array('crowded'));
        queue_js(array('crowded'));
    }

    public function hookPublicAppendToItemsShow(){
       $this->crowded_participate_item();
    }

    public function hookPublicThemeBody() {
        $this->crowded_user_bar();
    }
    
    public function hookDefineAcl($acl) {
        $acl->addResource(new Zend_Acl_Resource('participate'));
        $crowdEditor = new Zend_Acl_Role(CROWDED_USER_ROLE);
        $acl->addRole($crowdEditor);
        $acl->allow($crowdEditor, 'participate', array('edit','profile'));
        
    }

    public function crowded_date_formfield($html, $inputNameStem, $date) {
        return __v()->formSelect($inputNameStem . '[Date]', $date, null, array());
    }
    
    public static function adminNavigationMain($nav) {
        // if (has_permission('CrowdEd_Index','index')) {
            $nav['Crowd Ed'] = uri(array('module'=>'crowd-ed', 'controller'=>'index', 'action'=>'index'), 'default');
        // }
        return $nav;
    }


    /* PRIVATE FUNCTIONS */    
    
    
    private function crowded_participate_item() {
        $item = get_current_item();
        echo("<h4>Participate</h4><div><a href=\"/participate/edit/". $item->id ."\">Assist us with editing and cataloging this item!</a></div>");
    }
    
    private function crowded_user_bar() {

        $user = current_user();
        echo '<div class="container"><div class="navbar navbar-static-top"><div class="navbar-inner"><div class="brand">Crowd-Ed</div><ul class="nav pull-right">';
        
        if ($user) {
            $content = "<li><a href=\"/participate/profile/". $user->id . "\"><i class=\"icon-user\"></i> " . $user->username . "</a></li><li><a href=\"" . uri(array('action'=>'logout', 'controller'=>'users'), 'default') . "\"><i class=\"icon-off\"></i> Logout</a></li>";
        } else {
            $content = "<li><a href=\"/participate/login\"><i class=\"icon-user\"></i> Log in</a></li><li><a href=\"/participate/join\"><i class=\"icon-cog\"></i> Create Account</a></li>";
        }
        
        echo($content . '</ul></div></div></div>');
    }
}

?>
