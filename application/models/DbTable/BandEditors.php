<?php

class Model_DbTable_BandEditors extends Zend_Db_Table_Abstract {

    protected $_name = 'gearoscope_band_editors';

    public function addEditor($user_id, $band_id, $active, $code) {
        $data = array(
            'user_id' => $user_id,
            'band_id' => $band_id,
            'active' => $active,
            'code' => $code
        );
        return $this->insert($data);
    }

    public function getEditors($band_id) {
        $band_id = (int) $band_id;
        $select = $this->select()
                ->from(array('gearoscope_band_editors'), array('gearoscope_band_editors.band_editor_id', 'gearoscope_band_editors.user_id', 'gearoscope_band_editors.band_id', 'gearoscope_band_editors.active'))
                ->joinLeft(array('gearoscope_users'), 'gearoscope_users.user_id=gearoscope_band_editors.user_id', array('user_id', 'user_username'))
                ->where('gearoscope_band_editors.band_id = ?', $band_id)
                ->setIntegrityCheck(false);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function getEditor($id) {
        $id = (int) $id;
        $row = $this->fetchRow('band_editor_id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();
    }
    
    public function getEditorByUserAndBandId($user_id, $band_id) {
        $user_id = (int) $user_id;
         $select = $this->select()
        		->where('gearoscope_band_editors.user_id = ?', $user_id)
        		->where('gearoscope_band_editors.band_id = ?', $band_id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }

    public function setActive($code) {
    	$data = array (
		    'active' => '1'
		);
 
		$where = $this->getAdapter()->quoteInto('code = ?', $code);
 
		$this->update($data, $where);
    }

}