<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Taken from an idea on StackOverflow: http://stackoverflow.com/questions/2381166/how-does-one-add-a-plain-text-node-to-a-zend-form
 *
 * @author Garrick S. Bodine <garrick.bodine@gmail.com>
 */

class CrowdEd_Form_Element_Modal extends Zend_Form_Element_Xhtml {  
    public $helper = 'formNote';  
    
    public function isValid($value){ 
        return true; 
    }
}



?>
