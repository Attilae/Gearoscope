<?php

class Model_DbTable_Tags extends Zend_Db_Table_Abstract {
    /*
     * @var $_name table name : users
     */

    protected $_name = 'tags';

    public function addTag($post_id, $tag) {
        $data = array(
            'post_id' => $post_id,
            'tag' => $tag
        );
        $this->insert($data);
    }

    public function findAll() {
        $select = $this->select()
                        ->from(array('tags'), array('tags.*'))
                        ->joinLeft(array('posts'), 'tags.post_id=posts.post_id', array('posts.title'))
                        ->setIntegrityCheck(false);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function findByPost($post_id) {
        $select = $this->select()
                        ->where("post_id = ?", $post_id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function findForCloud() {
        $select = $this->select()
                        ->from('tags', array('COUNT( tag ) AS tagCount', 'tag'))
                        ->joinLeft('posts', 'tags.post_id=posts.post_id', array('posts.active'))
                        ->group("tag")
                        ->order("tagCount DESC")
                        ->order("rand()")
                        ->where('posts.active = ?', "1")
                        ->limit(40)
                ->setIntegrityCheck(false);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function deleteTag($tag, $postId) {
        $data = 'tag ="' . $tag . '" AND post_id = ' . (int) $postId;
        $this->delete($data);
    }

    public function admindeleteTag($tid) {
        $where = 'tag_id = ' . (int) $id;
        $this->delete($where);
    }

}