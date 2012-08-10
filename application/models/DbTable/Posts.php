<?php

class Model_DbTable_Posts extends Zend_Db_Table_Abstract {

    protected $_name = 'posts';

    public function getPosts() {
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.edited', 'posts.active', 'posts.prior', 'posts.topic', 'posts.lead', 'posts.comments', 'posts.rates', 'posts.date', 'posts.photo'))
                        ->joinLeft(array('tags'), 'tags.post_id=posts.post_id', array('COUNT(tags.tag)'))
                        ->joinLeft(array('users'), 'posts.user_id=users.user_id', array('users.username'))
                        ->group("posts.title")
                        ->order('posts.post_id DESC')
                        ->setIntegrityCheck(false);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getPostsActive() {
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.edited', 'posts.active', 'posts.prior', 'posts.topic', 'posts.lead', 'posts.comments', 'posts.rates', 'posts.date', 'posts.photo'))
                        ->joinLeft(array('tags'), 'tags.post_id=posts.post_id', array('COUNT(tags.tag)'))
                        ->joinLeft(array('users'), 'posts.user_id=users.user_id', array('users.username'))
                        ->group("posts.title")
                        ->order('posts.post_id DESC')
                        ->where('posts.active = ?', "1")
                        ->setIntegrityCheck(false);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getPostsNonActive() {
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.edited', 'posts.active', 'posts.prior', 'posts.topic', 'posts.lead', 'posts.comments', 'posts.rates', 'posts.date', 'posts.photo'))
                        ->joinLeft(array('tags'), 'tags.post_id=posts.post_id', array('COUNT(tags.tag)'))
                        ->joinLeft(array('users'), 'posts.user_id=users.user_id', array('users.username'))
                        ->group("posts.title")
                        ->order('posts.post_id DESC')
                        ->where('posts.active = ?', "0")
                        ->setIntegrityCheck(false);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function countMobilerPosts($user_id) {
        $select = $this->select()
                        ->from(array('posts'), array('COUNT(posts.post_id)'))
                        ->where("posts.user_id = ?", $user_id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getPostsByMobiler($user_id) {
        $select = $this->select()
                        ->where("user_id = ?", $user_id)
                        ->where("active = 1")
                        ->order("post_id DESC");
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getPostsByMobilerForEdit($user_id) {
        $select = $this->select()
                        ->where("user_id = ?", $user_id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getPostsByTopic($topic) {
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.lead', 'posts.comments', 'posts.photo', 'posts.date'))
                        ->joinLeft(array('tags'), 'tags.post_id=posts.post_id', array('COUNT(tags.tag)'))
                        ->joinLeft(array('users'), 'posts.user_id=users.user_id')
                        ->group("posts.title")
                        ->order('posts.post_id DESC')
                        ->setIntegrityCheck(false)
                        ->where("posts.topic = ?", $topic)
                        ->where("posts.active = 1");
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getPostsByMonth($month) {
        $month = (int) $month;
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.lead', 'posts.comments', 'posts.photo', 'posts.date'))
                        ->joinLeft(array('tags'), 'tags.post_id=posts.post_id', array('COUNT(tags.tag)'))
                        ->joinLeft(array('users'), 'posts.user_id=users.user_id')
                        ->group("posts.title")
                        ->order('posts.post_id DESC')
                        ->setIntegrityCheck(false)
                        ->where("MONTH(date) = ?", $month)
                        ->where("posts.active = 1")
        ;
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getPostsByTag($tag) {
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.lead', 'posts.comments', 'posts.photo', 'posts.date'))
                        ->joinLeft(array('tags'), 'tags.post_id=posts.post_id', array('COUNT(tags.tag)'))
                        ->joinLeft(array('users'), 'posts.user_id=users.user_id')
                        ->group("posts.title")
                        ->order('posts.post_id DESC')
                        ->setIntegrityCheck(false)
                        ->where("posts.active = 1")
                        ->where("tags.tag = ?", $tag);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getPriorPost() {
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.lead', 'posts.comments', 'posts.photo', 'posts.date'))
                        ->joinLeft(array('tags'), 'tags.post_id=posts.post_id', array('COUNT(tags.tag)'))
                        ->joinLeft(array('users'), 'posts.user_id=users.user_id')
                        ->group("posts.title")
                        ->order('posts.post_id DESC')
                        ->setIntegrityCheck(false)
                        ->where("prior = 1")
                        ->where("posts.active = 1")
                        ->limit(6);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getPost($id) {
        $id = (int) $id;
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.edited', 'posts.date', 'posts.active AS postactive', 'posts.lead', 'posts.topic', 'posts.content', 'posts.comments', 'posts.rates', 'posts.photo', 'posts.video', 'posts.gallery'))
                        ->joinLeft(array('tags'), 'tags.post_id=posts.post_id', array('COUNT(tags.tag)'))
                        ->joinLeft(array('users'), 'posts.user_id=users.user_id')
                        ->group("posts.title")
                        ->order('posts.post_id DESC')
                        ->setIntegrityCheck(false)
                        ->where("posts.post_id = ?", $id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getMorePostsByMobiler($user_id, $post_id) {
        $select = $this->select()
                        ->where("user_id = ?", $user_id)
                        ->where("post_id != ?", $post_id)
                        ->where("active = 1")
                        ->limit(3);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getFirstThree() {
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.topic', 'posts.lead', 'posts.content', 'posts.comments', 'posts.photo', 'posts.date',))
                        ->joinLeft(array('users'), 'users.user_id=posts.user_id', array('users.*'))
                        ->order('posts.post_id DESC')
                        ->setIntegrityCheck(false)
                        ->where("posts.active = 1")
                        ->limit('3');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function aggregateComments($post_id, $comments) {
        $data = array(
            'comments' => $comments,
        );
        $where = 'post_id = ' . $post_id;
        $this->update($data, $where);
    }

    public function aggregateRates($post_id, $rates) {
        $data = array(
            'rates' => $rates,
        );
        $where = 'post_id = ' . $post_id;
        $this->update($data, $where);
    }

    public function countPosts() {
        $select = $this->select()
                        ->from('posts', array('COUNT( post_id ) as countPosts'));
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function savePost($user_id, $title, $topic, $lead, $content, $photo_url, $video,
    //$gallery,
            $date) {
        $data = array(
            'user_id' => $user_id,
            'title' => $title,
            'topic' => $topic,
            'lead' => $lead,
            'content' => $content,
            'video' => $video,
            'photo' => $photo_url,
            'date' => $date
                //'gallery' => $gallery,
        );
        return $this->insert($data);
    }

    public function updatePost($post_id, $title, $topic, $lead, $content, $video, $gallery) {
        $data = array(
            'title' => $title,
            'topic' => $topic,
            'lead' => $lead,
            'content' => $content,
            'video' => $video,
            'gallery' => $gallery
        );
        $where = 'post_id = ' . $post_id;
        $this->update($data, $where);
    }

    public function updatePhoto($post_id, $photo) {
        $data = array(
            'photo' => $photo,
        );
        $where = 'post_id = ' . $post_id;
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

    public function setEdited($id) {
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            //update the password
            $rowUser->edited = "1";
            $rowUser->save();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

    public function setUnedited($id) {
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            //update the password
            $rowUser->edited = "0";
            $rowUser->save();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

    public function setPrior($id) {
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            //update the password
            $rowUser->prior = "1";
            $rowUser->save();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

    public function setSimple($id) {
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            //update the password
            $rowUser->prior = "0";
            $rowUser->save();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

    public function deletePost($id) {
        $row = $this->find($id)->current();
        if ($row) {
            $row->delete();
        }
    }

    public function mostMobilerPost() {
        //SELECT `posts`.`post_id`, COUNT(posts.user_id), `users`.`username` FROM `posts`
//        LEFT JOIN `users` ON users.user_id=posts.user_id
//        WHERE (posts.active = 1)
//        GROUP BY posts.user_id
//        ORDER BY COUNT(posts.user_id) DESC
//        LIMIT 5
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'COUNT(posts.user_id)',))
                        ->joinLeft(array('users'), 'users.user_id=posts.user_id', array('users.username'))
                        ->group("posts.user_id")
                        ->order('COUNT(posts.user_id) DESC')
                        ->setIntegrityCheck(false)
                        ->where("posts.active = 1");
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function mostCommentedPost() {
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.comments'))
                        ->order('posts.comments DESC')
                        ->limit(5)
                        ->where("posts.active = 1");
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function mostRatedPost() {
        $select = $this->select()
                        ->from(array('posts'), array('posts.post_id', 'posts.title', 'posts.rates'))
                        ->limit(5)
                        ->order('posts.rates DESC')
                        ->where("posts.active = 1");
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

}