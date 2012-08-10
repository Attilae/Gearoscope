<?php

class Model_DbTable_GearsItems extends Zend_Db_Table_Abstract {

	protected $_name = 'gearoscope_gears_items';

	public function getItems($id) {
		$select = $this->select()
		->from(array('items'), array('items.item_id', 'items.title', 'items.active', 'items.lead',  'items.date', 'items.photo', 'items.featured', 'items.sub_subcat_id'))
		->order('items.item_id DESC')
		->where('items.sub_subcat_id = ?', $id);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function getItemsActive() {
		$select = $this->select()
		->from(array('gearoscope_gears_items'), array('gearoscope_gears_items.gears_item_id', 'gearoscope_gears_items.gears_subsubcategory_id', 'gearoscope_gears_items.user_id', 'gearoscope_gears_items.gear_name', 'gearoscope_gears_items.serial_number', 'gearoscope_gears_items.gear_photo_url',  'gearoscope_gears_items.date', 'gearoscope_gears_items.featured'))
		->order('gearoscope_gears_items.gears_item_id DESC')
		->where('gearoscope_gears_items.active = ?', '1');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	public function getItemsActiveForSearch() {
		$select = $this->select()
		->from(array('items'), array('items.item_id', 'items.title', 'items.active', 'items.lead',  'items.date', 'items.photo', 'items.featured', 'items.sub_subcat_id'))
		->order('items.item_id DESC')
		->where('items.active = ?', '1');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function getByCategory($subcat_id) {
		$select = $this->select()
			->where("subcat_id = ?", $subcat_id)
			->where('active = 1');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function getItemFeatured() {
		$select = $this->select()
		->from(array('items'), array('items.item_id', 'items.title', 'items.active', 'items.lead',  'items.date', 'items.photo', 'items.featured'))
		->order('items.item_id DESC')
		->where('items.active = ?', "1")
		->where('items.featured = ?', "1");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function getItem($id) {
		$id = (int) $id;
		$select = $this->select()
		->from(array('items'), array('items.item_id', 'items.title', 'items.active', 'items.lead',  'items.date', 'items.photo', 'items.sub_subcat_id'))
		->group("items.title")
		->order('items.item_id DESC')
		->where("items.item_id = ?", $id);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}	

	public function saveItem($title, $lead, $photo_url, $subsubcategory, $date) {
		$data = array(
            'title' => $title,
        	'lead' => $lead,
            'photo' => $photo_url,
        	'sub_subcat_id' => $subsubcategory,
            'date' => $date
		);
		return $this->insert($data);
	}

	public function updateItem($item_id, $title, $lead) {
		$data = array(
            'title' => $title,
            'lead' => $lead
		);
		$where = 'item_id = ' . $item_id;
		$this->update($data, $where);
	}

	public function updatePhoto($item_id, $photo) {
		$data = array(
            'photo' => $photo,
		);
		$where = 'item_id = ' . $item_id;
		$this->update($data, $where);
	}

	public function setActive($id) {
		$rowUser = $this->find($id)->current();
		if ($rowUser) {
			//update the password
			$rowUser->active = "1";
			$rowUser->save();
		} else {
			throw new Zend_Exception("Password update failed.  User not found!");
		}
	}

	public function setDeactive($id) {
		$rowUser = $this->find($id)->current();
		if ($rowUser) {
			//update the password
			$rowUser->active = "0";
			$rowUser->save();
		} else {
			throw new Zend_Exception("Password update failed.  User not found!");
		}
	}

	public function setFeatured($id) {
		$rowUser = $this->find($id)->current();
		if ($rowUser) {
			//update the password
			$rowUser->featured = "1";
			$rowUser->save();
		} else {
			throw new Zend_Exception("Password update failed.  User not found!");
		}
	}

	public function setSimple($id) {
		$rowUser = $this->find($id)->current();
		if ($rowUser) {
			//update the password
			$rowUser->featured = "0";
			$rowUser->save();
		} else {
			throw new Zend_Exception("Password update failed.  User not found!");
		}
	}

	public function deleteItem($item_id) {
		$where = 'item_id = ' . (int) $item_id;
		$this->delete($where);
	}

}