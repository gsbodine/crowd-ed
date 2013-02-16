<?php
/**
 * Description of IndexController
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_IndexController extends Omeka_Controller_AbstractActionController {
    public function indexAction() {
        
    }
    
    public function browseAction() {
        
    }
    
    public function flaggedAction() {
        //$results = $this->_helper->searchItems();
        $paginationUrl = $this->getRequest()->getBaseUrl().'/items/browse/';

        $pagination = array('menu'          => null, 
                            'page'          => $results['page'], 
                            'per_page'      => $results['per_page'], 
                            'total_results' => $results['total_results'], 
                            'link'          => $paginationUrl);
        
        Zend_Registry::set('pagination', $pagination);
        
        fire_plugin_hook('browse_items', $results['items']);
        
        $this->view->assign(array('items'=>$results['items'], 'total_items'=>$results['total_items']));
    }
    
    public function reviewAction() {
        
    }
        
}

?>
