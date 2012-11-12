<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Description of PersonName
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */
class PersonName extends Omeka_Record {
    
    public $firstname = '';
    public $middlename = '';
    public $lastname = '';
    public $title = '';
    public $suffix = '';
    public $element_text_id = '';
    public $record_id = '';
    public $element_id = '';
    
    protected function _initializeMixins() {
        $this->_mixins[] = new PersonNameElementText($this);
    }
    
    public function setId($id) {
        if (is_int($id)) {
            $this->id = $id;
        } else {
            throw new Omeka_Record_Exception(__("IDs must be integers, of course!"));
        }
    }
    
    public function setFirstname($firstname) {
        $this->firstname = trim($firstname);
    }
    
    public function setLastname($lastname) {
        $this->lastname = trim($lastname);
    }
    
    public function setMiddlename($middlename) {
        $this->middlename = trim($middlename);
    }
    
    public function setSuffix($suffix) {
        $this->suffix = trim($suffix);
    }
    
    public function setTitle($title) {
        $this->title = trim($title);
    }

    public function setArray($data) {
        if (is_string($data)) {
            $this->setName($data);
        } else {
            foreach ($data as $key => $value) {
                switch ($key) {
                    case 'id':
                        $this->setId($value);
                    case 'firstname':
                        $this->setFirstname($value);
                        break;
                    case 'middlename':
                        $this->setMiddlename($value);
                        break;
                    case 'lastname':
                        $this->setLastname($value);
                        break;
                    case 'title':
                        $this->setTitle($value);
                        break;
                    case 'suffix':
                        $this->setSuffix($value);
                        break;
                    default:
                        $this->$key = $value;
                        break;
                }
            }
        }
    }
    
    
    
}

?>
