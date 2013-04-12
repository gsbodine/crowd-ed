<?php

class CrowdEd_Controller_Plugin_Security extends Zend_Controller_Plugin_Abstract {
    protected $_loginRequiredActions = array(
        array('participate','profile'),
        array('participate','edit')
    );
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $this->_preventAdminAccess($request);
        $this->_forceLogin($request);
    }
    
    protected function _forceLogin($request) {
        if ('crowd-ed' == $request->getModuleName()) {
            $user = current_user();
            
            if (!$user and in_array(array($request->getControllerName(), $request->getActionName()), $this->_loginRequiredActions)) {
                
                // The following code piggybacks off the current (0.10) 
                // implementation of UsersController::loginAction().  May need 
                // to change in the future.
                $session = new Zend_Session_Namespace;
                $session->redirect = $request->getPathInfo();
                $this->_getRedirect()->goto('login', 'users', 'default');
            }
        }        
    }
    
    protected function _preventAdminAccess($request) {
        $user = current_user();
        if ($user && ($user->role == CROWDED_USER_ROLE || $user->role == CROWDED_SUPER_ROLE) and is_admin_theme()) {
            exit;
        }
    }
    
    protected function _getRedirect() {
        return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
    } 
    
    
}
