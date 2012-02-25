<?php
/**
 * @version $Id$
 * @copyright Garrick S. Bodine
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package CrowdEd
 * @subpackage Forms
 **/

class CrowdEd_Form_Item extends Omeka_Form {
    public function init() {
        parent::init();
        
        $this->setMethod('post');
        $this->setAttrib('id', 'crowded-item-form');
        $this->addElement('textarea','crowded_item_description', 
                array(
                    'label' => 'Document Description',
                    'rows' => '8',
                    'cols' => '65',
                    'value' => 'This is the description text mock-up')
                );
                $this->addElement('submit','submit',array(
                    'label' => 'Submit Changes'
                ));
    }
}
