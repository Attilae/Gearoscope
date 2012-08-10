<?php

class Model_Picasa {

    public $picasaArray;

    public function generatePicasaUser($url) {
        $user = explode("/", $url);
        return $user[7];
    }

    public function generatePicasaAlbum($url) {
        $album = explode("/", $url);

        $album = explode("?", $album[9]);

        return $album[0];
    }

    public function generatePics($url) {
        $galleryUser = $this->generatePicasaUser($url);        
        $galleryAlbum = $this->generatePicasaAlbum($url);        

        $tSize = "72c";
        $maxSize = "720u";

        $file = file_get_contents('http://picasaweb.google.com/data/feed/api/user/' . $galleryUser . '/albumid/' . $galleryAlbum . '?kind=photo&access=public&thumbsize=72c&imgmax=' . $maxSize); // get the contents of the album
        try {
            $xml = new SimpleXMLElement($file);
            $xml->registerXPathNamespace('media', 'http://search.yahoo.com/mrss/');
        } catch (Exception $e) {
            return "A szolgáltatás átmenetileg nem elérhető";
        }

        //$this->view->xml = $xml->entry;

        $i=0;
        $picasaArray = array();
        foreach ($xml->entry as $feed) {
            $group = $feed->xpath('./media:group/media:thumbnail');
            $description = $feed->xpath('./media:group/media:description');
            if (str_word_count($description[0]) > 0) {
                $description = $feed->title . ": " . $description[0];
            } else {
                $description = $feed->title;
            }
            $a = $group[0]->attributes();
            $b = $feed->content->attributes();
            $picasaArray[$i]["url"] = $a['url'];
            $picasaArray[$i]["title"] = $feed->title;
            $picasaArray[$i]["width"] = $a['width'];
            $picasaArray[$i]["height"] = $a['height'];
            $i++;
        }
        return $picasaArray;
    }

}