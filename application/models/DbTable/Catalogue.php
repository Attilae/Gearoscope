<?php

class Model_DbTable_Category extends Zend_Db_Table_Abstract {

	protected $_name = 'category';
	
	public function findAll() {
        $select = $this->select();
        $result = $this->fetchAll($select);
        return $result->toArray();
    }
	
}