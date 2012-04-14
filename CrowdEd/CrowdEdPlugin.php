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
        'after_validate_item'
    );
    
    public function hookInstall() {
        set_option('crowded_plugin_version', CROWDED_PLUGIN_VERSION);
        $db = get_db();
        // TODO: Set up DB tables for all the Crowd-Ed specific junk
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
        //echo print_r($item);
        //break;
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
        queue_css('crowded');
    }

    public function hookPublicAppendToItemsShow(){
        $this->crowded_participate_item();
    }

    public function hookPublicThemeBody() {
        $this->crowded_user_bar();
    }
    
    public function crowded_setup_acl($acl) {
        $acl->addRole(new Omeka_Acl(CROWDED_USER_ROLE));
        $acl->loadResourceList(); // TODO: Finish up the resource list for Crowd-Ed users
    }

    public function crowded_date_formfield($html, $inputNameStem, $date) {
        return __v()->formSelect($inputNameStem . '[Date]', $date, null, array());
    }
    
    public static function adminNavigationMain($nav) {
        $nav['Crowd Ed'] = uri('crowd-ed');
        return $nav;
    }


    /* PRIVATE FUNCTIONS */    
    
    
    private function crowded_participate_item() {
        $item = get_current_item();
        echo("<h3>Participate</h3><div><a href=\"/participate/edit/". $item->id ."\">Assist us with cataloging this item!</a></div>");
    }
    
    private function crowded_user_bar() {

        $user = current_user();

        if ($user) {
            $content = "<div id=\"crowded_login_bar\"><a href=\"/participate/profile/". $user->id . "\">" . $user->username . "</a> | <a href=\"" . uri(array('action'=>'logout', 'controller'=>'users'), 'default') . "\">Logout</a></div>";
        } else {
            $content = "<div id=\"crowded_login_bar\"><a href=\"/participate/login\">Log in</a> | <a href=\"/participate/join\">Create Account</a></div>";
        }

        echo($content);
    }
}

?>
