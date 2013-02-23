<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

class CrowdEd_View_Helper_Flash extends Zend_View_Helper_Abstract {
    
    private $_flashMessenger;

    public function __construct() {
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    }
    
    public function flash() {
        $flashHtml = '';
        if ($this->_flashMessenger->hasMessages() || $this->_flashMessenger->hasCurrentMessages()) {
            if (!is_admin_theme()) {
                $flashHtml .= '<div id="flash" class="modal show fade" role="dialog" tab-index="-1" aria-labelled-by="flashModal">';
                $flashHtml .= '<div class="modal-header">';
                $flashHtml .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3><i class="icon-info-sign"></i> '. get_option('site_title') .'</h3></div>';
                $flashHtml .= '<div class="modal-body"><ul class="unstyled">';
            } else {
                $flashHtml .= '<div id="flash"><ul>';
            }
            foreach ($this->_flashMessenger->getMessages() as $status => $messages) {
                foreach ($messages as $message) {
                    $flashHtml .= $this->_getListHtml($status, $message);
                }
            }
            foreach ($this->_flashMessenger->getCurrentMessages() as $status => $messages) {
                foreach ($messages as $message) {
                    $flashHtml .= $this->_getListHtml($status, $message);
                }
            }
            $flashHtml .= '</ul></div>';
            
            if (!is_admin_theme()) {
                $flashHtml .= '<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">OK</button></div></div>';
            }
        }
        $this->_flashMessenger->clearMessages();
        $this->_flashMessenger->clearCurrentMessages();
        return $flashHtml;
    }
    
    private function _getListHtml($status, $message) {
        return '<li class="' . $this->view->escape($status) . '">' 
            . $this->view->escape($message)
            . '</li>';
    }
}

?>
