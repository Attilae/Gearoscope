<?php
class FeedController extends Zend_Controller_Action
{
    public function rssAction()
    {

        // build the feed array
        $feedArray = array();
        
        // the title and link are required
        $feedArray['title'] = 'Samsung Mobilers';
        $feedArray['link'] = 'http://mobilers.samsung.hu';
        
        // the published timestamp is optional
        $feedArray['published'] = Zend_Date::now()->toString(Zend_Date::TIMESTAMP);
        // the charset is required
        $feedArray['charset'] = 'UTF8';
        
        // first get the most recent pages
        $mdlPosts = new Model_DbTable_Posts();
        $recentPages = $mdlPosts->getPostsActive();
        
        //add the entries 
        if(is_array($recentPages) && count($recentPages) > 0) {
            foreach ($recentPages as $page) {
                // create the entry
                $entry = array();
                $entry['guid'] = $page["post_id"];
                $entry['title'] = $page["title"];
                $entry['link'] = 'http://mobilers.samsung.hu/posts/view/id/' . $page["post_id"] ;
                $entry['description'] = $page["lead"];
                $entry['content'] = $page["content"];
                
                // add it to the feed
                $feedArray['entries'][] = $entry;
            }
        }
        // create an RSS feed from the array
        $feed = Zend_Feed::importArray($feedArray, 'rss');
        
        // now send the feed
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $feed->send();        
    }
    
}
?>