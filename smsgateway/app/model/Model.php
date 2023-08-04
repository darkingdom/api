<?php
class APIModel
{
    public $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    // GET =========================================================================================================
    public function getMsgNotSend()
    {
        $this->db->query("SELECT * FROM `pesan` WHERE status='0' LIMIT 1");
        return $this->db->single();
    }
    // END GET =====================================================================================================

    // GET ALL =====================================================================================================
    // END GET ALL =================================================================================================

    // COUNT DATA ==================================================================================================
    // END COUNT DATA ==============================================================================================

    // UPDATE DATA =================================================================================================
    public function updateMsgByID($data)
    {
        $this->db->query("UPDATE `pesan` SET status='1' WHERE id=:id");
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }
    // END UPDATE DATA =============================================================================================


    // CREATE ======================================================================================================
    public function createMsg($data)
    {
        $this->db->query("INSERT INTO `pesan` (date_create,receiver,message,app_id,status)VALUES(NOW(),:receiver,:message,:appID,'0')");
        $this->db->bind('receiver', $data['receiver']);
        $this->db->bind('message', $data['message']);
        $this->db->bind('appID', $data['appID']);
        $this->db->execute();
        return $this->db->rowCount();
    }
    // END CREATE ==================================================================================================

    // DELETE DATA =================================================================================================
    public function deleteMsgByID($data)
    {
        $this->db->query("DELETE FROM `pesan` WHERE id=:id");
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }
    // END DELETE DATA =============================================================================================
}
