<?php

class Mobilers_Model_DbTable_Mobilers extends Zend_Db_Table_Abstract {

    protected $_name = 'mobilers';

    public function getMobilers() {
        $orderby = array('mobiler_id DESC');
        $result = $this->fetchAll('1', $orderby);
        return $result->toArray();
    }

    public function getMobiler($id) {
        $id = (int) $id;
        $row = $this->fetchRow('mobiler_id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

    public function createMobiler($email, $bio, $job, $birth_year, $photo_url, $username, $password, $first_name, $last_name, $role) {
        $rowUser = $this->createRow();
        if ($rowUser) {
            $rowUser->username = $username;
            $rowUser->password = $password;
            $rowUser->first_name = $first_name;
            $rowUser->last_name = $last_name;
            $rowUser->role = $role;
            $rowUser->email = $email;
            $rowUser->bio = $bio;
            $rowUser->job = $job;
            $rowUser->birth_year = $birth_year;
            $rowUser->photo_url = $photo_url;
            return $rowUser;
        } else {
            throw new Zend_Exception("Could not create user! ");
        }
    }

    public function updateMobiler($id, $name, $email, $bio, $job, $birth_year, $photo_url) {
        $rowMobiler = $this->find($id)->current();

        if ($rowMobiler) {
            $rowUser->name = $name;
            $rowUser->email = $email;
            $rowUser->bio = $bio;
            $rowUser->job = $job;
            $rowUser->birth_year = $birth_year;
            $rowUser->photo_url = $photo_url;
            $rowUser->save();
            //return the updated user
            return $rowUser;
        } else {
            throw new Zend_Exception("Mobiler update failed.  Mobiler not found!");
        }
    }

}
