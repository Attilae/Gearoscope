<?php

class Model_Youtube {

    public static function generateEmbed($url) {
        preg_match("/v=([^&]+)/i", $url, $matches);
        $id = $matches[1];

        $code = '<iframe width="625" height="348" src="http://www.youtube.com/embed/{id}?rel=0" frameborder="0" allowfullscreen></iframe>';

        return $code = str_replace('{id}', $id, $code);
    }

    public static function generateMobileEmbed($url) {
        preg_match("/v=([^&]+)/i", $url, $matches);
        $id = $matches[1];

        $code = '<iframe width="300" height="182" src="http://www.youtube.com/embed/{id}?rel=0" frameborder="0" allowfullscreen></iframe>';

        return $code = str_replace('{id}', $id, $code);
    }

}