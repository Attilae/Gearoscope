<?php

class Model_DbTable_Activity extends Zend_Db_Table_Abstract {

    protected $_name = 'activities';

    public function getAll() {
        $select = $this->select();
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getActivities() {
        $select = $this->select();
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getActivity($id) {
        $id = (int) $id;
        $select = $this->select()
                        ->where("activity_id = ?", $id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function countPosts() {
        $select = $this->select()
                        ->from('posts',
                                array('COUNT( post_id ) as countPosts'));
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function saveActivity($title, $active, $photo, $link) {
        $data = array(
            'title' => $title,
            'active' => $active,
            'photo' => $photo,
            'link' => $link
        );
        return $this->insert($data);
    }

    public function editActivity($id, $title, $link) {
        $data = array(
            'title' => $title,
            'link' => $link
        );
        $where = 'activity_id = ' . $id;
        $this->update($data, $where);
    }

    public function updatePhoto($id, $photo) {
        $data = array(
            'photo' => $photo,
        );
        $where = 'activity_id = ' . $id;
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

    public function getFirstThree() {
        $select = $this->select()
                        ->limit('3');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function deleteActivity($id) {
        $where = 'activity_id = ' . (int) $id;
        $this->delete($where);
    }

}