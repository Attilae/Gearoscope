<?php

/**
 * Auth Default Controller
 *
 *
 * @author          Eddie Jaoude
 * @package       Auth Module
 *
 */
class Xls_IndexController extends Zend_Controller_Action {

    /**
     * default method
     *
     * @author          Eddie Jaoude
     * @param           void
     * @return           void
     *
     */
    public function indexAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        ini_set("display_errors", "on");

        //set_time_limit( 0 );
//        $model = new Auth_Model_Account();
//        $data = $model->getData();
        //$accounts = $this->_em->getRepository('Auth_Model_Account')->findAll();

        $model = new Model_DbTable_Mobiler();
        $currentUsers = $model->listMobiler();


        //die(print_r($currentUsers));

        $filename = APPLICATION_PATH . "/tmp/mobilers_" . date("Y_m_d_H_i", time()) . ".csv";

        $realPath = realpath($filename);

        if (false === $realPath) {
            touch($filename);
            chmod($filename, 0777);
        }

        $filename = realpath($filename);
        $handle = fopen($filename, "w");
        $finalData = array();

        $finalData[] = array(
            "mobiler_id",
            "Név",
            "Nem",
            "Város",
            "Végzettség",
            "Foglalkozás",
            "Kor",
            "E-mail",
            "Telefon",
            "Aktivitás",
            "Facebook",
            "Facebook felhasználók",
            "Twitter",
            "Twitter felhasználók",
            "Blog",
            "Blog URL",
            "Kedvenc app",
            "Bio",
            "Dátum"
        );

        foreach ($currentUsers as $v) {

            $long = array("ő", "Ő", "Ű", "ű");
            $short = array("ö", "Ö", "Ü", "ü");

            $finalData[] = array(
                utf8_decode($v["id"]), // For chars with accents.
                str_replace($long, $short, utf8_decode($v["name"])),
                str_replace($long, $short, utf8_decode($v["sex"])),
                str_replace($long, $short, utf8_decode($v["city"])),
                str_replace($long, $short, utf8_decode($v["graduate"])),
                str_replace($long, $short, utf8_decode($v["job"])),
                utf8_decode($v["age"]),
                utf8_decode($v["email"]),
                str_replace($long, $short, utf8_decode($v["phone"])),
                str_replace($long, $short, utf8_decode($v["activate"])),
                utf8_decode($v["facebook"]),
                utf8_decode($v["facebookUsers"]),
                utf8_decode($v["twitter"]),
                utf8_decode($v["twitterUsers"]),
                str_replace($long, $short, utf8_decode($v["blog"])),
                str_replace($long, $short, utf8_decode($v["blogAddress"])),
                str_replace($long, $short, utf8_decode($v["app"])),
                str_replace($long, $short, utf8_decode($v["bio"])),
                str_replace($long, $short, utf8_decode($v["date"]))
            );
        }

        //die(print_r($finalData));

        foreach ($finalData AS $finalRow) {
            fputcsv($handle, $finalRow, ";");
        }

        fclose($handle);



        $this->setHeader("Content-Type: application/ms-excel; charset=UTF-8")
                ->setHeader("Content-Disposition: attachment; filename=mobilers_" . date("Y_m_d_H_i", time()) . ".csv")
                ->setHeader("Content-Transfer-Encoding: binary")
                ->setHeader("Expires: 0")
                ->setHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
                ->setHeader("Pragma: public")
                ->setHeader("Content-Length: " . filesize($filename))
                ->sendResponse();

        readfile($filename);
        exit();
    }

}

