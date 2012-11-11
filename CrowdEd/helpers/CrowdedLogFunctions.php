<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * 
 * Solution implemented below based on one found at SO: 
 * http://stackoverflow.com/questions/9426768/auditlogging-functionality-for-zend-db
 * 
 * 
 */
?>


<?php
function logUpdate(array $values) { 
    $columnMapping = array('id' => 'id', 
                           'table' => 'table', 
                           'column' => 'column',
                           'rowId' => 'rowId',
                           'oldvalue' => 'oldvalue',
                           'newvalue' => 'newvalue',
                           'updatedon' => 'updatedon',
                           'updatedbyuser' => 'updatedbyuser');

    $writer = new Zend_Log_Writer_Db($db, 'auditlog_table', $columnMapping);

    $logger = new Zend_Log($writer);

    $logger->setEventItem('id', $values['id']);
    $logger->setEventItem('table', $values['table']);
    $logger->setEventItem('column', $values['column']);
    $logger->setEventItem('rowId', $values['rowId']);
    $logger->setEventItem('oldvalue', $values['oldValue']);
    $logger->setEventItem('newValue', $values['newValue']);
    $logger->setEventItem('updatedon', $values['updatedon']);
    $logger->setEventItem('updatedbyuser', $values['updatedbyuser']);
} 

function updateGroup($data,$id) {       
    $row = $this->find($id)->current();

    $values = array('table' => $this->name);
    $values = array('updatedon' => $data['user']);
    $values = array('updatedbyuser' => date());
   //go through all data to log the modified columns
    foreach($data as $key => $value){
      //check if modified log the modification
      if($row->$key != $value){
        $values = array('column' => $key);
        $values = array('oldValue' => $row->$key);
        $values = array('newValue' => $value);
        logUpdate($values);
      }
    }

    // set the row data
    $row->name                  = $data['name'];
    $row->description           = $data['description'];

    $row->updatedBy         = $data['user'];
    $row->updatedOn         = date(); 

    $id = $row->save();


    return $id;
}


?>