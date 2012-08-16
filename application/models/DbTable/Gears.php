<?php

class Model_DbTable_Gears extends Zend_Db_Table_Abstract {

    protected $_name = 'gearoscope_gears';

    public function getItems($id) {
        $select = $this->select()
                ->from(array('gearoscope_gears'), array('gearoscope_gears.gear_id', 'gearoscope_gears.gears_subsubcategory_id', 'gearoscope_gears.user_id', 'gearoscope_gears.gear_name', 'gearoscope_gears.serial_number', 'gearoscope_gears.gear_photo_url', 'gearoscope_gears.date', 'gearoscope_gears.featured'))
                ->order('gearoscope_gears.gear_id DESC')
                ->where('gearoscope_gears.gears_subsubcategory_id = ?', $id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getItemsActive() {
        $select = $this->select()
                ->from(array('gearoscope_gears'), array('gearoscope_gears.gear_id', 'gearoscope_gears.gears_subsubcategory_id', 'gearoscope_gears.user_id', 'gearoscope_gears.gear_name', 'gearoscope_gears.serial_number', 'gearoscope_gears.gear_photo_url', 'gearoscope_gears.gear_thumbnail_url', 'gearoscope_gears.create_date', 'gearoscope_gears.featured'))
                ->joinLeft(array('gearoscope_gears_subsubcategories'), 'gearoscope_gears_subsubcategories.gears_subsubcategory_id=gearoscope_gears.gears_subsubcategory_id', array('gearoscope_gears_subsubcategories.subsubcategory'))
                ->joinLeft(array('gearoscope_gears_subcategories'), 'gearoscope_gears_subcategories.gears_subcategory_id=gearoscope_gears.gears_subcategory_id', array('gearoscope_gears_subcategories.subcategory'))
                ->joinLeft(array('gearoscope_gears_categories'), 'gearoscope_gears_categories.gears_category_id=gearoscope_gears.gears_category_id', array('gearoscope_gears_categories.category'))
                ->order('gearoscope_gears.gear_id DESC')
                ->where('gearoscope_gears.active = ?', '1')
                ->setIntegrityCheck(false);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getItemsActiveForSearch() {
        $select = $this->select()
                ->from(array('items'), array('items.item_id', 'items.title', 'items.active', 'items.lead', 'items.date', 'items.photo', 'items.featured', 'items.sub_subcat_id'))
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
                ->from(array('items'), array('items.item_id', 'items.title', 'items.active', 'items.lead', 'items.date', 'items.photo', 'items.featured'))
                ->order('items.item_id DESC')
                ->where('items.active = ?', "1")
                ->where('items.featured = ?', "1");
        $result = $this->fetchAll($select);
        return $result->toArray();
    }
    
    public function getGearOrderByHits() {
        $id = (int) $id;
        $select = $this->select()
                ->from(array('gearoscope_gears'), array('gearoscope_gears.gear_id', 'gearoscope_gears.hits', 'gearoscope_gears.gear_name'))
                ->order('hits DESC')
                ->limit(12);
        $result = $this->fetchAll($select);
        if ($result) {
            return $result->toArray();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

    public function getGear($id) {
        $id = (int) $id;
        $select = $this->select()
                ->from(array('gearoscope_gears'), array('gearoscope_gears.gear_id', 'gearoscope_gears.gears_category_id', 'gearoscope_gears.gears_subcategory_id', 'gearoscope_gears.gears_subsubcategory_id', 'gearoscope_gears.user_id', 'gearoscope_gears.hits', 'gearoscope_gears.gear_name', 'gearoscope_gears.serial_number', 'gearoscope_gears.description', 'gearoscope_gears.gear_photo_url', 'gearoscope_gears.gear_thumbnail_url', 'gearoscope_gears.create_date', 'gearoscope_gears.featured'))
                ->joinLeft(array('gearoscope_gears_subsubcategories'), 'gearoscope_gears_subsubcategories.gears_subsubcategory_id=gearoscope_gears.gears_subsubcategory_id', array('gearoscope_gears_subsubcategories.subsubcategory'))
                ->joinLeft(array('gearoscope_gears_subcategories'), 'gearoscope_gears_subcategories.gears_subcategory_id=gearoscope_gears.gears_subcategory_id', array('gearoscope_gears_subcategories.subcategory'))
                ->joinLeft(array('gearoscope_gears_categories'), 'gearoscope_gears_categories.gears_category_id=gearoscope_gears.gears_category_id', array('gearoscope_gears_categories.category'))
                ->where('gearoscope_gears.gear_id = ?', $id)
                ->setIntegrityCheck(false);
        $result = $this->fetchRow($select);
        if ($result) {
            return $result->toArray();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

    public function getByUser($user_id) {
        $user_id = (int) $user_id;
        $select = $this->select()
                ->from(array('gearoscope_gears'), array('gearoscope_gears.gear_id', 'gearoscope_gears.gears_subsubcategory_id', 'gearoscope_gears.user_id', 'gearoscope_gears.gear_name', 'gearoscope_gears.serial_number', 'gearoscope_gears.gear_photo_url', 'gearoscope_gears.gear_thumbnail_url', 'gearoscope_gears.create_date', 'gearoscope_gears.featured'))
                ->joinLeft(array('gearoscope_gears_subsubcategories'), 'gearoscope_gears_subsubcategories.gears_subsubcategory_id=gearoscope_gears.gears_subsubcategory_id', array('gearoscope_gears_subsubcategories.subsubcategory'))
                ->joinLeft(array('gearoscope_gears_subcategories'), 'gearoscope_gears_subcategories.gears_subcategory_id=gearoscope_gears.gears_subcategory_id', array('gearoscope_gears_subcategories.subcategory'))
                ->joinLeft(array('gearoscope_gears_categories'), 'gearoscope_gears_categories.gears_category_id=gearoscope_gears.gears_category_id', array('gearoscope_gears_categories.category'))
                ->where('gearoscope_gears.user_id = ?', $user_id)
                ->setIntegrityCheck(false);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function addGear($user_id, $active, $gear_name, $serial_number, $category, $subcategory, $subsubcategory, $featured, $photo_url, $thumbnail_url, $create_date, $last_edit_date, $description) {
        $data = array(
            'user_id' => $user_id,
            'active' => $active,
            'gear_name' => $gear_name,
            'serial_number' => $serial_number,
            'gears_category_id' => $category,
            'gears_subcategory_id' => $subcategory,
            'gears_subsubcategory_id' => $subsubcategory,
            'featured' => $featured,
            'gear_photo_url' => $photo_url,
            'gear_thumbnail_url' => $thumbnail_url,
            'create_date' => $create_date,
            'last_edit_date' => $last_edit_date,
            'description' => $description
        );
        return $this->insert($data);
    }

    public function editGear($gear_id, $gear_name, $serial_number, $subsubcategory, $featured, $photo_url, $thumbnail_url, $last_edit_date, $description) {
        $data = array(
            'gear_name' => $gear_name,
            'serial_number' => $serial_number,
            'gears_subsubcategory_id' => $subsubcategory,
            'featured' => $featured,
            'gear_photo_url' => $photo_url,
            'gear_thumbnail_url' => $thumbnail_url,
            'last_edit_date' => $last_edit_date,
            'description' => $description
        );
        $where = 'gear_id = ' . $gear_id;
        $this->update($data, $where);
    }

    public function updatePhoto($item_id, $photo) {
        $data = array(
            'photo' => $photo,
        );
        $where = 'item_id = ' . $item_id;
        $this->update($data, $where);
    }

    public function deleteImage($gear_id) {
        $data = array(
            'gear_photo_url' => 'dummy.jpg',
        );
        $where = 'gear_id = ' . $gear_id;
        $this->update($data, $where);
    }

    public function deleteThumbnail($gear_id) {
        $data = array(
            'gear_thumbnail_url' => 'dummy_thumbnail.jpg',
        );
        $where = 'gear_id = ' . $gear_id;
        $this->update($data, $where);
    }
    
    public function aggregateHits($gear_id, $hits) {
        $rowGear = $this->find($gear_id)->current();
        if ($rowGear) {
            //update the password
            $rowGear->hits = $hits;
            $rowGear->save();
        } else {
            throw new Zend_Exception("Gear update failed.  Gear not found!");
        }
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