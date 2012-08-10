<?php

class Model_DbTable_Styles extends Zend_Db_Table_Abstract {

	protected $_name = 'gearoscope_styles';

	public function findForAutocomplete($tag) {
		$select = $this->select()
			->where("style LIKE ?", "%".$tag."%")
			->limit(100);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function getStyles() {
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

	public function getStylesActive() {
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

	public function getStylesNonActive() {
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
	
	public function getStyle($style) {		
		$select = $this->select()
			->from(array('gearoscope_styles'), array('gearoscope_styles.style_id', 'gearoscope_styles.style'))		
			->where("gearoscope_styles.style = ?", $style);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function addStyle($style) {
		$data = array(            
            'style' => $style
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