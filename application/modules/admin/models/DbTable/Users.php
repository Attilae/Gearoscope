<?php

class Admin_Model_DbTable_Users extends Zend_Db_Table_Abstract {

    protected $_name = 'users';

    public function getUser($id) {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function findCredentials($username, $pwd) {
        $select = $this->select()->where('username = ?', $username)
                        ->where('password = ?', $pwd);
        $row = $this->fetchRow($select);
        if ($row) {
            /*
             * If success return the row
             */
            return $row;
        }
        return false;
    }

    protected function hashPassword($pwd) {
        /*
         * return an md5 hash
         */
        return md5($pwd);
    }

    public function addUser($idCompany, $username, $password, $role) {
        $data = array(
            "idceg" => $idCompany,
            "felhasznalo" => $username,
            "jelszo" => $password,
            "jog" => $role,
            "need_help" => "1"
        );
        $this->insert($data);
    }

        public function updateUser($idUser, $active) {
        $data = array(
            "active" => $active,
        );
        $this->update($data, 'idfelhasznalo = ' . (int) $idUser);
    }

}