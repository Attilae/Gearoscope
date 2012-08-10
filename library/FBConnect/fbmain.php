<?php

/*
 * @author: Mahmud Ahsan (http://thinkdiff.net)
 */
    //facebook application
    $fbconfig['appid' ]     = "244707452272083";
    $fbconfig['secret']     = "196ce7a77ec83bbd4b93e83e638bab3a";
    $fbconfig['baseurl']    = "http://superbutt.net/gearoscope/hu/"; //"http://thinkdiff.net/demo/newfbconnect1/php/sdk3/index.php";

    //
    if (isset($_GET['request_ids'])){
        //user comes from invitation
        //track them if you need
    }
    
    $user            =   null; //facebook user uid
    try{
        include_once "../library/FBConnect/facebook.php";
    }
    catch(Exception $o){
        error_log($o);
    }
    // Create our Application instance.
    $facebook = new Facebook(array(
      'appId'  => $fbconfig['appid'],
      'secret' => $fbconfig['secret'],
      'cookie' => true,
    ));

    //Facebook Authentication part
    $user       = $facebook->getUser();
    // We may or may not have this data based 
    // on whether the user is logged in.
    // If we have a $user id here, it means we know 
    // the user is logged into
    // Facebook, but we don�t know if the access token is valid. An access
    // token is invalid if the user logged out of Facebook.
    
    
    $loginUrl   = $facebook->getLoginUrl(
            array(
                'scope'         => 'email',
                'redirect_uri'  => $fbconfig['baseurl']
            )
    );
    
    $logoutUrl  = $facebook->getLogoutUrl();
   

    if ($user) {
      try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me');
      } catch (FacebookApiException $e) {
        //you should use error_log($e); instead of printing the info on browser
        d($e);  // d is a debug function defined at the end of this file
        $user = null;
      }
    }
   
    
    //if user is logged in and session is valid.
    if ($user) {
        //get user basic description
        $userInfo           = $facebook->api("/$user");
        
        //Retriving movies those are user like using graph api
        try{
            $movies = $facebook->api("/$user/movies");
        }
        catch(Exception $o){
            d($o);
        }               
    }
    
    function d($d){
        echo '<pre>';
        //print_r($d);
        echo '</pre>';
    }
?>
