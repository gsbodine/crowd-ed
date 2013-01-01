<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

require_once APP_DIR . '/views/helpers/Flash.php';

class CrowdEd_View_Helper_Flash extends Omeka_View_Helper_Flash {
    public function flash($flashIcon = null, $flashHeading = null) {
        $flashHtml = '';
        if ($this->_flashMessenger->hasMessages()
         || $this->_flashMessenger->hasCurrentMessages()) {
            $flashHtml .= '<div id="flash" class="modal hide fade" role="dialog" tab-index="-1" aria-labelled-by="flashModal">';
            $flashHtml .= '<div class="modal-header">';
            $flashHtml .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="flashModal">'. $flashIcon . ' ' . $flashHeading .'</h3></div>';
            $flashHtml .= '<ul class="unstyled">';
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
        }
        $this->_flashMessenger->clearMessages();
        $this->_flashMessenger->clearCurrentMessages();
        return $flashHtml;
    }
}

?>
