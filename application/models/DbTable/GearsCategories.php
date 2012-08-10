<?php

class Model_DbTable_GearsCategories extends Zend_Db_Table_Abstract {

	protected $_name = 'gearoscope_gears_categories';

	public function findAll() {
		$select = $this->select();
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function getCategories() {
		$select = $this->select()
		->order('gears_category_id DESC');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function getCategory($id) {
		$id = (int) $id;
		$select = $this->select()				
			->where("gears_category_id = ?", $id);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function saveCategory($category) {
		$data = array(
            'category' => $category
		);
		return $this->insert($data);
	}

	public function updateCategory($gears_category_id, $category) {
		$data = array(
            'category' => $category
		);
		$where = 'gears_category_id = ' . $gears_category_id;
		$this->update($data, $where);
	}

}