<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_IndexController extends Omeka_Controller_Action {
    public function indexAction() {
       
    }
    
    public function browseAction() {
        
    }
    
    public function reviewAction() {
        $results = $this->_helper->searchItems();
        
        /** 
         * Now process the pagination
         * 
         */
        $paginationUrl = $this->getRequest()->getBaseUrl().'/items/browse/';

        //Serve up the pagination
        $pagination = array('menu'          => null, // This hasn't done anything since $menu was never instantiated in ItemsController::browseAction()
                            'page'          => $results['page'], 
                            'per_page'      => $results['per_page'], 
                            'total_results' => $results['total_results'], 
                            'link'          => $paginationUrl);
        
        Zend_Registry::set('pagination', $pagination);
        
        fire_plugin_hook('browse_items', $results['items']);
        
        $this->view->assign(array('items'=>$results['items'], 'total_items'=>$results['total_items']));
    }
        
}

?>
