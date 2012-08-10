<?php
class IndexController extends Zend_Controller_Action
{
	public function init()
	{
		$this->view->addHelperPath(
                'ZendX/JQuery/View/Helper'
                ,'ZendX_JQuery_View_Helper');
	}
	public function indexAction ()
	{
		 
		$baseUrl = $this->view->baseUrl();
		 
		$this->view->headScript()->prependFile($baseUrl."/public/skins/gearoscope/js/jquery.nivo.slider.js");
		
		$this->view->headScript()->appendScript("
            (function($) {
                $(window).load(function(){
                    $('.NivoSzakiSlider .nivoSlider').nivoSlider({
                    effect:'random',
                    slices:10,
                    animSpeed:500,
                    pauseTime:6000,
                    startSlide:0,
                    directionNav:0,
                    directionNavHide:1,
                    controlNav:1,
                    controlNavThumbs:1,
                    controlNavThumbsFromRel:false,
                    controlNavThumbsSearch: '.jpg',
                    controlNavThumbsReplace: '_thumb.jpg',
                    keyboardNav:0,
                    pauseOnHover:1,
                    manualAdvance:0,
                    captionOpacity:1
                    });
                });
            })(jQuery);");

		$this->view->headLink()->appendStylesheet($baseUrl."/public/skins/gearoscope/css/nivo-slider-enhanced.css");
		
		$newsModel = new Model_DbTable_News();
    	$news = $newsModel->getActive();
    	     	    	 
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($news);
        $paginator->setItemCountPerPage(7);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

		//$this->view->headLink()->appendStylesheet($baseUrl."/public/skins/gearoscope/css/moo_maximenuH_CK.css");

		//$this->view->headLink()->appendStylesheet($baseUrl."/public/skins/gearoscope/css/maximenuH_CK.css");

	}
}

