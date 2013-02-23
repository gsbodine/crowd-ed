<?php
/**
 * Description of IndexController
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class CrowdEd_IndexController extends Omeka_Controller_AbstractActionController {
    
    public function init() {
        $this->_helper->db->setDefaultModelName('Item');
    }
    
    public function indexAction() {
        
    }
    
    public function browseAction() {
        
        parent::browseAction();
    }
    
    public function flaggedAction() {
        
        parent::browseAction();
    }
    
    public function reviewAction() {
        
    }
        
}

?>
