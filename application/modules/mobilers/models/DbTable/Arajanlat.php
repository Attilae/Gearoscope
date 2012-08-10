<?php

class Arajanlat_Model_DbTable_Arajanlat extends Zend_Db_Table_Abstract {

    protected $_name = 'arajanlat';

    public function add($company, $address, $name, $position, $phone, $email, $message) {
        $data = array(
            "ceg" => $company,
            "cim" => $address,
            "nev" => $name,
            "beosztas" => $position,
            "telefon" => $phone,
            "email" => $email,
            "uzenet" => $message
        );
        $this->insert($data);
    }

    public function findAll() {
        $select = $this->select();
        return $this->fetchAll($select);
    }

}

?>
