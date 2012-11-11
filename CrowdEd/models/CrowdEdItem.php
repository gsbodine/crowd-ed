<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of Item
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

include_once APP_DIR . '/models/Item.php';

class CrowdEdItem extends Item {
    
    protected $_related = array('Collection'=>'getCollection', 
                                'TypeMetadata'=>'getTypeMetadata', 
                                'Type'=>'getItemType',
                                'Tags'=>'getTags',
                                'Files'=>'getFiles',
                                'Elements'=>'getElements',
                                'ItemTypeElements'=>'getItemTypeElements',
                                'ElementTexts'=>'getElementText',
                                'PersonNames'=>'getPersonNames');
    
    protected function _initializeMixins() {
        $this->_mixins[] = new Taggable($this);
        $this->_mixins[] = new Relatable($this);
        $this->_mixins[] = new ActsAsElementText($this);
        $this->_mixins[] = new PublicFeatured($this);
        $this->_mixins[] = new PersonNameElementText($this);
    }
    
}

?>
