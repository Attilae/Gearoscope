<?php

class Model_DbTable_Mobiler extends Zend_Db_Table_Abstract {

    /**
     * The default table name
     */
    protected $_name = 'mobilers';

    public function createMobiler($name, $sex, $city, $graduate, $job, $age, $phone, $email, $activate, $facebook, $facebookUsers, $twitter, $twitterUsers, $blog, $blogAddress, $app, $bio, $date) {
        // create a new row
        $rowUser = $this->createRow();
        if ($rowUser) {
            // update the row values
            $rowUser->name = $name;
            $rowUser->sex = $sex;
            $rowUser->city = $city;
            $rowUser->graduate = $graduate;
            $rowUser->job = $job;
            $rowUser->age = $age;
            $rowUser->phone = $phone;
            $rowUser->email = $email;
            $rowUser->activate = $activate;
            $rowUser->facebook = $facebook;
            $rowUser->facebookUsers = $facebookUsers;
            $rowUser->twitter = $twitter;
            $rowUser->twitterUsers = $twitterUsers;
            $rowUser->blog = $blog;
            $rowUser->blogAddress = $blogAddress;
            $rowUser->app = $app;
            $rowUser->bio = $bio;
            $rowUser->date = $date;
            $rowUser->save();
            //return the new user
            return $rowUser;
        } else {
            throw new Zend_Exception("Could not create user! ");
        }
    }

    public function listMobiler() {
        $select = $this->select()
                ->order("date DESC");
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

}
