<?php

class Model_DbTable_Score extends Zend_Db_Table_Abstract {

    protected $_name = 'score';

    public function findOneToTen() {
        $select = $this->select()                        
                        ->order('score ASC')
                ->limit(15);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

     public function findTenToTwenty() {
        $select = $this->select()
                        ->order('score ASC')
                ->limit(10, 10);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }


    public function saveScore($name, $score) {
        $data = array(            
            'name' => $name,
            'score' => $score,
            'date' => date("Y-m-d H:i:s", time())
        );
        $this->insert($data);
    }

}