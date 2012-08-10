<?php

class Model_DbTable_User extends Zend_Db_Table_Abstract {

    /**
     * The default table name
     */
    protected $_name = 'gearoscope_users';

    public function createUser($active, $name, $username, $email, $password, $role, $photo, $code, $register_date) {
        // create a new row
        $rowUser = $this->createRow();
        if ($rowUser) {
            // update the row values
            $rowUser->user_active = $active;
            $rowUser->user_name = $name;
            $rowUser->user_username = $username;
            $rowUser->user_email = $email;
            $rowUser->user_password = md5($password);
            $rowUser->user_role = $role;
            $rowUser->user_photo = $photo;
            $rowUser->user_register_date = $register_date;
            $rowUser->code = $code;
            $rowUser->save();
            //return the new user
            return $rowUser;
        } else {
            throw new Zend_Exception("Could not create user! ");
        }
    }

    public function updatePhoto($id, $photo_url) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();

        if ($rowUser) {
            // update the row values
            $rowUser->photo_url = $photo_url;
            $rowUser->save();
            //return the updated user
            return $rowUser;
        } else {
            throw new Zend_Exception("User update failed.  User not found!");
        }
    }

    public static function getUsers() {
        $userModel = new self();
        $select = $userModel->select();
        return $userModel->fetchAll($select);
    }

    public function getUser($id) {
        $id = (int) $id;
        $row = $this->fetchRow('user_id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }

    public function getUserUsername($username) {
        $select = $this->select()
                ->where('user_username = ?', $username);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getUserEmail($email) {
        $select = $this->select()
                ->where('user_email = ?', $email);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function updateUser($id, $password) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();

        if ($rowUser) {
            // update the row values
            $rowUser->password = md5($password);
            $rowUser->save();
            //return the updated user
            return $rowUser;
        } else {
            throw new Zend_Exception("User update failed.  User not found!");
        }
    }
    
    public function updateLoginDate($id) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();

        if ($rowUser) {
            // update the row values
            $rowUser->user_login_date = time();
            $rowUser->save();
            //return the updated user
            return $rowUser;
        } else {
            throw new Zend_Exception("User update failed.  User not found!");
        }
    }

    public function updatePassword($id, $password) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();

        if ($rowUser) {
            // update the row values
            $rowUser->user_password = md5($password);
            $rowUser->save();
            //return the updated user
            return $rowUser;
        } else {
            throw new Zend_Exception("User update failed.  User not found!");
        }
    }

    public function updateEmail($id, $email) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();

        if ($rowUser) {
            // update the row values
            $rowUser->email = $email;
            $rowUser->save();
            //return the updated user
            return $rowUser;
        } else {
            throw new Zend_Exception("User update failed.  User not found!");
        }
    }

    public function forgotPassword($email, $password) {
        $data = array(
            'user_password' => md5($password)
        );
        $where = 'user_email = "' . $email . '"';
        $this->update($data, $where);
    }

    public function deleteUser($id) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            $rowUser->delete();
        } else {
            throw new Zend_Exception("Could not delete user.  User not found!");
        }
    }

    public function activateUser($id) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            //update the password
            $rowUser->user_active = "1";
            $rowUser->save();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

    public function make_daily_password($pass_len = 8, $pass_num = true, $pass_alpha = true, $pass_mc = true, $pass_exclude = '') {
        // Create the salt used to generate the password
        $salt = '';
        if ($pass_alpha) { // a-z
            $salt .= 'abcdefghijklmnopqrstuvwxyz';
            if ($pass_mc) { // A-Z
                $salt .= strtoupper($salt);
            }
        }

        if ($pass_num) { // 0-9
            $salt .= '0123456789';
        }

        // Remove any excluded chars from salt
        if ($pass_exclude) {
            $exclude = array_unique(preg_split('//', $pass_exclude));
            $salt = str_replace($exclude, '', $salt);
        }
        $salt_len = strlen($salt);

        // Seed the random number generator with today's seed & password's unique settings for extra randomness
        mt_srand((int) date('y') * date('z') * ($salt_len + $pass_len));

        // Generate today's random password
        $pass = '';
        for ($i = 0; $i < $pass_len; $i++) {
            $pass .= substr($salt, mt_rand() % $salt_len, 1);
        }

        return $pass;
    }

    public function setActive($id) {
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            //update the password
            $rowUser->user_active = "1";
            $rowUser->save();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

    public function setDeactive($id) {
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            //update the password
            $rowUser->user_active = "0";
            $rowUser->save();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

}
