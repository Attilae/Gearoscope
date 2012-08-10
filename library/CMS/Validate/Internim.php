<?php
class CMS_Validate_Internim
{	public static function getMicrotime()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float)$usec + (float)$sec);
        }
}

?>