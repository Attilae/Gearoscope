<?php

class AddscoreController extends Zend_Controller_Action {

    public function indexAction() {

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $name = $this->_getParam('name');
        $score = (int) $this->_getParam('score');
        $pass = $this->_getParam('pass');

        $date = date("Y-m-d H:i:s", time());

        if ($pass == md5("amajdnemmarnemeleg2008") AND $name != "" AND $score != "") {
            $modelScore = new Model_DbTable_Score();
            $modelScore->saveScore($name, $score);
        }

        $score = $score / 1000;
        $scoreArray = explode(".", $score);
        $scoreMin = $scoreArray[0] / 60;
        $scoreMinArray = explode(".", $scoreMin);
        $scoreSec = $scoreArray[0] % 60;
        $finalScore = (int) $scoreMin . ":" . $scoreSec . "." . $scoreArray[1];

        $bodyText = "<body>";
        $bodyText .= "<p>Toplista beküldés érkezett a Samsung Mob!lers oldalról</p>";
        $bodyText .= "<p>Név: " . $name . "</p>";
        $bodyText .= "<p>Időeredmény: " . $finalScore . "</p>";
        $bodyText .= "<p>Dátum: " . $date . "</p>";
        $bodyText .= "</body>";

        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText($bodyText);
        $mail->setBodyHtml($bodyText);
        $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
        $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
        $mail->addTo("attila.erdei87@gmail.com");
        $mail->addBcc("fiertelmeister.anita@carpedm.hu");
        $mail->addBcc("egri.denes@carpedm.hu");
        $mail->setSubject('Samsung Mob!lers toplista beküldés');
        $mail->send();
    }

}

