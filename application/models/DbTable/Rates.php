<?php

class Model_DbTable_Rates extends Zend_Db_Table_Abstract {

    protected $_name = 'rates';

    public function getRates($post_id) {
        $select = $this->select()
                        ->where("post_id = ?", $post_id)
                        ->order('rate_id DESC');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function deleteRate($rate_id) {
        $where = 'rate_id = ' . (int) $rate_id;
        $this->delete($where);
    }

    public function saveRate($post_id, $user_id) {
        $data = array(
            'post_id' => $post_id,
            'user_id' => $user_id,
            'date' => time()
        );
        $this->insert($data);
    }

    public function mostRatedMobiler() {
//        SELECT COUNT(rates.post_id), users.username  FROM `rates`
//LEFT JOIN posts ON rates.post_id=posts.post_id
//LEFT JOIN users ON posts.user_id=users.user_id
//GROUP by rates.post_id
        $select = $this->select()
                        ->from(array('rates'), array('rates.post_id', 'COUNT(rates.user_id)',))
                        ->joinLeft(array('posts'), 'rates.post_id=posts.post_id', array('posts.post_id'))
                        ->joinLeft(array('users'), 'users.user_id=posts.user_id', array('users.username'))
                        ->group("rates.user_id")
                        ->order('COUNT(rates.user_id) DESC')
                        ->setIntegrityCheck(false)
                        ->limit('5');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function mostUserRate() {
        //SELECT COUNT(rates.user_id), users.username FROM rates LEFT JOIN users ON users.user_id=rates.user_id GROUP by rates.user_id
        $select = $this->select()
                        ->from(array('rates'), array('rates.user_id', 'COUNT(rates.user_id)',))
                        ->joinLeft(array('users'), 'users.user_id=rates.user_id', array('users.username'))
                        ->group("rates.user_id")
                        ->order('COUNT(rates.user_id) DESC')
                        ->setIntegrityCheck(false)
                        ->limit('5');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function userRated($user_id, $post_id) {
        $select = $this->select()
                        ->where("user_id = ?", $user_id)
                        ->where("post_id = ?", $post_id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

}