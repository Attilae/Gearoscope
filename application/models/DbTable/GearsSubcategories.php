<?php

class Model_DbTable_GearsSubcategories extends Zend_Db_Table_Abstract {

	protected $_name = 'gearoscope_gears_subcategories';

	public function findAll() {
		$select = $this->select();
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function findByCategory($category_id) {
		$select = $this->select()
			->where("gears_category_id = ?", $category_id);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	public function getCategories() {
		$select = $this->select()
		->order('subcat_id DESC');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function getCategory($subcategory_id) {
		$subcategory_id = (int) $subcategory_id;
		$select = $this->select()				
			->where("gears_subcategory_id = ?", $subcategory_id);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function saveCategory($subcategory, $category_id) {
		$data = array(
            'subcategory' => $subcategory,
			'gears_category_id' => $category_id
		);
		return $this->insert($data);
	}

	public function updateCategory($subcategory_id, $subcategory) {
		$data = array(
            'subcategory' => $subcategory
		);
		$where = 'gears_subcategory_id = ' . $subcategory_id;
		$this->update($data, $where);
	}

}