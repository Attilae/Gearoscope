<?php

class Model_DbTable_GearsSubsubcategories extends Zend_Db_Table_Abstract {

	protected $_name = 'gearoscope_gears_subsubcategories';

	public function findAll() {
		$select = $this->select();
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function findByCategory($subcategory_id) {
		$select = $this->select()
			->where("gears_subcategory_id = ?", $subcategory_id);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	public function getCategories() {
		$select = $this->select()
		->order('gears_subsubcategory_id DESC');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function getCategory($subsubcategory_id) {
		$subsubcategory_id = (int) $subsubcategory_id;
		$select = $this->select()				
			->where("gears_subsubcategory_id = ?", $subsubcategory_id);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function saveCategory($subsubcategory, $subcategory_id, $user_id) {
		$data = array(
                        'subsubcategory' => $subsubcategory,
			'gears_subcategory_id' => $subcategory_id,
			'user_id' => $user_id
		);
		return $this->insert($data);
	}

	public function updateCategory($subsubcategory_id, $subsubcategory) {
		$data = array(
            'subsubcategory' => $subsubcategory
		);
		$where = 'gears_subsubcategory_id = ' . $subsubcategory_id;
		$this->update($data, $where);
	}

}