<?php

class SearchController extends Zend_Controller_Action {

    public function indexAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        if ($this->_request->isPost()) {
            $keywords = $this->_request->getParam('query');
            $query = Zend_Search_Lucene_Search_QueryParser::parse($keywords);
            $index = Zend_Search_Lucene::open(APPLICATION_PATH . '/indexes');
            $hits = $index->find($query);
            $this->view->results = $hits;
            $this->view->keywords = $keywords;
        } else {
            $this->view->results = null;
        }
    }

    public function buildAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        // create the index
        $index = Zend_Search_Lucene::create(APPLICATION_PATH . '/indexes');

        // fetch all of the current pages
        $mdlPosts = new Model_DbTable_Posts();
        $currentPosts = $mdlPosts->getPosts();
        if ($currentPosts) {
            // create a new search document for each page
            foreach ($currentPosts as $p) {
                $doc = new Zend_Search_Lucene_Document();
                // you use an unindexed field for the id because you want the id to be
                // included in the search results but not searchable
                $doc->addField(Zend_Search_Lucene_Field::unIndexed('post_id', $p["post_id"]));
                // you use text fields here because you want the content to be searchable
                // and to be returned in search results 
                $doc->addField(Zend_Search_Lucene_Field::text('title', $p["title"], 'UTF-8'));
                $doc->addField(Zend_Search_Lucene_Field::text('lead', $p["lead"], 'UTF-8'));
                $doc->addField(Zend_Search_Lucene_Field::text('comments', $p["comments"]));
                $doc->addField(Zend_Search_Lucene_Field::text('photo', $p["photo"]));
                $doc->addField(Zend_Search_Lucene_Field::text('user', $p["username"], 'UTF-8'));
                $doc->addField(Zend_Search_Lucene_Field::text('date', $p["date"]));
                // add the document to the index
                $index->addDocument($doc);
            }
        }
        // optimize the index
        $index->optimize();
        // pass the view data for reporting
        $this->view->indexSize = $index->numDocs();
    }

}

?>