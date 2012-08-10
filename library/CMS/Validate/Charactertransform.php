<?php
class CMS_Validate_Charactertransform {
	public static function specToNormal($string) {	
        $mit = array('&#244;', '&#251;', '&#212;', '&#219;');
        $mire = array('ő', 'ű', 'Ő', 'Ű');
        return $string = str_replace($mit, $mire, $string);
    
	}
	
	public static function normalToSpec($string) {
		$mit = array('ő', 'ű', 'Ő', 'Ű');
        $mire = array('&#244;', '&#251;', '&#212;', '&#219;');
        return $string = str_replace($mit, $mire, $string);
	}
}

?>