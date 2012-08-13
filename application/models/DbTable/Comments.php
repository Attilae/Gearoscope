<?php

class Model_DbTable_Comments extends Zend_Db_Table_Abstract {

    protected $_name = 'gearoscope_comments';

    public function getComment($id) {
        $id = (int) $id;
        $select = $this->select()
                        ->from(array('comments'), array('comments.comment_id', 'comments.user_id', 'comments.post_id', 'comments.name', 'comments.date', 'comments.description'))
                        ->joinLeft(array('posts'), 'posts.post_id=comments.post_id', array('posts.title'))
                        ->setIntegrityCheck(false)
                        ->where("comments.comment_id = ?", $id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function findAll() {
        $select = $this->select()
                        ->from(array('comments'), array('comments.comment_id', 'comments.user_id', 'comments.post_id', 'comments.name', 'comments.date', 'comments.description'))
                        ->joinLeft(array('posts'), 'posts.post_id=comments.post_id', array('posts.title'))
                        ->joinLeft(array('users'), 'users.user_id=comments.user_id', array('users.username'))
                        ->setIntegrityCheck(false);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getComments($gear_id) {
        $select = $this->select()
                        ->where("gear_id = ?", $gear_id)
                        ->order('comment_id DESC');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function countMobilerComments($user_id) {
        $select = $this->select()
                        ->from(array('comments'), array('COUNT(comments.comment_id)'))
                        ->where("comments.user_id = ?", $user_id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getCommentsByMobiler($user_id) {
        $select = $this->select()
                        ->where("user_id = ?", $user_id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getCommentsByUser($user_id) {
        $select = $this->select()
                        ->from(array('comments'), array('comments.comment_id', 'comments.name', 'comments.date', 'comments.description'))
                        ->joinLeft(array('posts'), 'posts.post_id=comments.post_id', array('posts.*'))
                        ->order('comments.date DESC')
                        ->setIntegrityCheck(false)
                        ->where("comments.user_id = ?", $user_id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function countComments($post_id) {
        $select = $this->select()
                        ->from(self::$_name,
                                array('COUNT( comment_id )'))
                        ->where("post_id = ?", $post_id);
        //die($select);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getFirstThree() {
        $select = $this->select()
                        ->from(array('comments'), array('comments.comment_id', 'comments.post_id', 'comments.name', 'comments.date', 'comments.description'))
                        ->joinLeft(array('users'), 'users.user_id=comments.user_id', array('users.*'))
                        ->order('comments.comment_id DESC')
                        ->setIntegrityCheck(false)
                        ->limit('3');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function saveComment($gear_id, $user_id, $description) {
        $data = array(
            'gear_id' => $gear_id,
            'user_id' => $user_id,
            'description' => $description,            
            'created_date' => time()
        );
        $this->insert($data);
    }

    public function deleteComment($comment_id) {
        $where = 'comment_id = ' . (int) $comment_id;
        $this->delete($where);
    }

    public function deleteAll($user_id) {
        $where = 'user_id = ' . (int) $user_id;
        $this->delete($where);
    }

    public function mostCommentedMobiler() {
//        SELECT COUNT(comments.post_id), users.username FROM `comments`
//        LEFT JOIN posts ON comments.post_id=posts.post_id
//        LEFT JOIN users ON posts.user_id=users.user_id
//        GROUP by comments.post_id
        $select = $this->select()
                        ->from(array('comments'), array('comments.post_id', 'COUNT(comments.user_id)',))
                        ->joinLeft(array('posts'), 'comments.post_id=posts.post_id', array('posts.post_id'))
                        ->joinLeft(array('users'), 'users.user_id=posts.user_id', array('users.username'))
                        ->group("comments.post_id")
                        ->order('COUNT(comments.user_id) DESC')
                        ->setIntegrityCheck(false)
                        ->limit('5');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function mostUserComment() {
        //SELECT COUNT(comments.user_id), users.username FROM `comments` LEFT JOIN users ON comments.user_id=users.user_id GROUP by comments.user_id
        $select = $this->select()
                        ->from(array('comments'), array('comments.user_id', 'COUNT(comments.user_id)',))
                        ->joinLeft(array('users'), 'users.user_id=comments.user_id', array('users.username'))
                        ->group("comments.user_id")
                        ->order('COUNT(comments.user_id) DESC')
                        ->setIntegrityCheck(false)
                        ->limit('5');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

}