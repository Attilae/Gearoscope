<?php

class Model_Archive extends Zend_Db_Table_Abstract {

    protected $_name = 'posts';

    public function find() {
//          SELECT YEAR(date) AS `year`,
//        MONTH(date) AS `month`,
//        count(post_id) as posts
//        FROM posts
//        GROUP BY YEAR(date),
//        MONTH(date)
//        ORDER BY date DESC
        $select = $this->select()
                        ->from(array('posts'), array('YEAR(date) as year', 'MONTH(date) as month', 'COUNT(post_id) as posts'))
                        ->group("YEAR(date), MONTH(date)")
                        ->order('posts.date DESC');
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

}

?>
