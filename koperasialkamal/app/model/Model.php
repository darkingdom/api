<?php
class APIModel
{
    public $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    // GET =========================================================================================================
    public function getMemberByID($id)
    {
        $this->db->query("SELECT * FROM kp_member WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getMemberByUsername($id)
    {
        $this->db->query("SELECT * FROM kp_member WHERE hpNumber=:username || memberNumber=:username");
        $this->db->bind('username', $id);
        return $this->db->single();
    }
    // END GET =====================================================================================================

    // GET ALL =====================================================================================================
    public function getHistoryThisMonthByMemberID($id)
    {
        $thisMonth = date('m');
        $this->db->query("SELECT * FROM kp_historyTx WHERE MONTH(txDate) = '$thisMonth'  && jenisTabungan='simpanan' &&  memberID=:id");
        $this->db->bind('id', $id);
        return $this->db->resultSet();
    }
    // END GET ALL =================================================================================================

    // COUNT DATA ==================================================================================================
    public function checkUsername($username)
    {
        $this->db->query("SELECT COUNT(id) as total FROM kp_member WHERE hpNumber=:username || memberNumber=:username");
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    public function checkMemberByID($id)
    {
        $this->db->query("SELECT COUNT(id) as total FROM kp_member WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function login($data)
    {
        $password = hash('sha256', $data['password']);
        $this->db->query("SELECT COUNT(id) AS total FROM kp_member WHERE password=:password && (hpNumber=:username || memberNumber=:username)");
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', $password);
        return $this->db->single();
    }

    public function loginPIN($data)
    {
        $this->db->query("SELECT COUNT(id) as total FROM kp_member WHERE id=:id && pinNumber=:pin");
        $this->db->bind('id', $data['id']);
        $this->db->bind('pin', $data['pin']);
        return $this->db->single();
    }

    public function checkMemberByPIN($data)
    {
        $this->db->query("SELECT COUNT(id) as total FROM kp_member WHERE id=:id && pinNumber=:pin");
        $this->db->bind('id', $data['id']);
        $this->db->bind('pin', $data['oldPIN']);
        return $this->db->single();
    }

    public function checkMemberByPassword($data)
    {
        $password = hash('sha256', $data['password']);
        $this->db->query("SELECT COUNT(id) as total FROM kp_member WHERE id=:id && password='$password'");
        $this->db->bind('id', $data['id']);
        return $this->db->single();
    }

    public function getDebetThisMonthByMemberID($id)
    {
        $thisMonth = date('m');
        $this->db->query("SELECT SUM(nominal) as total FROM kp_historyTx WHERE memberID=:id && nominal < 0 && MONTH(txDate) = '$thisMonth' && jenisTabungan='simpanan'");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getKreditThisMonthByMemberID($id)
    {
        $thisMonth = date('m');
        $this->db->query("SELECT SUM(nominal) as total FROM kp_historyTx WHERE memberID=:id && nominal > 0 && MONTH(txDate) = '$thisMonth' && jenisTabungan='simpanan'");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
    // END COUNT DATA ==============================================================================================

    // UPDATE DATA =================================================================================================
    public function updateLastLoginByID($id)
    {
        $this->db->query("UPDATE kp_member SET lastLogin=NOW() WHERE id=:id");
        $this->db->bind('id', $id);
        $this->db->execute();
    }

    public function newPassword($data)
    {
        $password = hash('sha256', $data['newPWD']);
        $this->db->query("UPDATE kp_member SET password='$password' WHERE id=:id");
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function newPIN($data)
    {
        $this->db->query("UPDATE kp_member SET pinNumber=:pin WHERE id=:id");
        $this->db->bind('id', $data['id']);
        $this->db->bind('pin', $data['newPIN']);
        $this->db->execute();
        return $this->db->rowCount();
    }
    // END UPDATE DATA =============================================================================================


    // CREATE ======================================================================================================
    // END CREATE ==================================================================================================

    // DELETE DATA =================================================================================================
    // END DELETE DATA =============================================================================================
}
