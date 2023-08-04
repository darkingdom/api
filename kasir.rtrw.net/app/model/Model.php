<?php
class APIModel
{
    public $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    // GET =========================================================================================================
    // public function getMsgNotSend()
    // {
    //     $this->db->query("SELECT * FROM `pesan` WHERE status='0' LIMIT 1");
    //     return $this->db->single();
    // }
    public function getISPByUsernamePassword($data)
    {
        $password = hash('sha256', $data['password']);
        $this->db->query("SELECT * FROM isp WHERE username=:username && password=:password");
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', $password);
        return $this->db->single();
    }

    public function getISPbyUUID($data)
    {
        $this->db->query("SELECT * FROM isp WHERE uuid=:isp");
        $this->db->bind('isp', $data['uuid']);
        return $this->db->single();
    }

    public function getSetting()
    {
        $this->db->query("SELECT * FROM setting");
        return $this->db->single();
    }

    public function getPPPOEByName($data)
    {
        $this->db->query("SELECT * FROM pppoe WHERE pppoe=:pppoe && isp_uuid=:isp");
        $this->db->bind('pppoe', $data['pppoe']);
        $this->db->bind('isp', $data['uuid']);
        return $this->db->single();
    }
    // END GET =====================================================================================================

    // GET ALL =====================================================================================================
    public function getAllDisconnect($data)
    {
        $this->db->query("SELECT * FROM pppoe WHERE status='OFF' && isp_uuid=:isp");
        $this->db->bind('isp', $data['uuid']);
        return $this->db->resultSet();
    }

    public function getAllCustomer($data)
    {
        $this->db->query("SELECT * FROM pppoe WHERE isp_uuid=:isp ORDER BY pppoe ASC");
        $this->db->bind('isp', $data['uuid']);
        return $this->db->resultSet();
    }

    public function getAllDisconnectToday($data)
    {
        $tahun = date('Y');
        $bulan = date('m');
        $hari = date('d');
        $this->db->query("SELECT DISTINCT * FROM history_disconnect_pppoe WHERE isp_uuid=:isp && DAY(tanggal)='$hari' && MONTH(tanggal)='$bulan' && YEAR(tanggal)='$tahun'");
        $this->db->bind('isp', $data['uuid']);
        return $this->db->resultSet();
    }

    // END GET ALL =================================================================================================

    // COUNT DATA ==================================================================================================
    public function countPPPOEByName($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM pppoe WHERE pppoe=:pppoe && isp_uuid=:isp");
        $this->db->bind('pppoe', $data['pppoe']);
        $this->db->bind('isp', $data['uuid']);
        return $this->db->single();
    }

    public function countPaketInternetByName($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM paket_internet WHERE nama_paket=:paket && isp_uuid=:isp");
        $this->db->bind('paket', $data['profile']);
        $this->db->bind('isp', $data['uuid']);
        return $this->db->single();
    }

    public function countTotalPelanggan($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM pppoe WHERE isp_uuid=:isp");
        $this->db->bind('isp', $data['uuid']);
        return $this->db->single();
    }

    public function countTotalPelangganAktif($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM pppoe WHERE isp_uuid=:isp && status='ON'");
        $this->db->bind('isp', $data['uuid']);
        return $this->db->single();
    }

    public function countTotalPelangganTidakAktif($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM pppoe WHERE isp_uuid=:isp && status='OFF'");
        $this->db->bind('isp', $data['uuid']);
        return $this->db->single();
    }

    public function countTotalPelangganTidakAktifHariIni($data)
    {
        $tahun = date('Y');
        $bulan = date('m');
        $hari = date('d');
        $this->db->query("SELECT COUNT(DISTINCT pppoe) AS total FROM history_disconnect_pppoe WHERE isp_uuid=:isp && DAY(tanggal)='$hari' && MONTH(tanggal)='$bulan' && YEAR(tanggal)='$tahun'");
        $this->db->bind('isp', $data['uuid']);
        return $this->db->single();
    }

    public function countLogin($data)
    {
        $password = hash('sha256', $data['password']);
        $this->db->query("SELECT COUNT(*) AS total FROM isp WHERE username=:username && password=:password");
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', $password);
        return $this->db->single();
    }

    public function countISPByUUID($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM isp WHERE uuid=:isp");
        $this->db->bind('isp', $data['uuid']);
        return $this->db->single();
    }
    // END COUNT DATA ==============================================================================================

    // UPDATE DATA =================================================================================================
    public function updateStatusONPPPOEByName($data)
    {
        $this->db->query("UPDATE pppoe SET tanggal_connect=NOW(), ipv4_address=:ipv4, mac_address=:mac, profile=:profile, status='ON' WHERE pppoe=:pppoe && isp_uuid=:isp");
        $this->db->bind('ipv4', $data['ipv4']);
        $this->db->bind('mac', $data['mac']);
        $this->db->bind('profile', $data['profile']);
        $this->db->bind('pppoe', $data['pppoe']);
        $this->db->bind('isp', $data['uuid']);
        $this->db->execute();
    }

    public function updateStatusONPPPOEByName_2($data)
    {
        $this->db->query("UPDATE pppoe SET status='ON', tanggal_connect=NOW() WHERE pppoe=:pppoe && isp_uuid=:isp");
        $this->db->bind('pppoe', $data['pppoe']);
        $this->db->bind('isp', $data['uuid']);
        $this->db->execute();
    }

    public function updateStatusOFFPPPOEByName($data)
    {
        $this->db->query("UPDATE pppoe SET status='OFF', tanggal_terputus=NOW() WHERE pppoe=:pppoe && isp_uuid=:isp");
        $this->db->bind('pppoe', $data['pppoe']);
        $this->db->bind('isp', $data['uuid']);
        $this->db->execute();
    }

    public function updateInternetMonitor($data)
    {
        $this->db->query("UPDATE isp SET internet_monitor=NOW() WHERE uuid=:isp");
        $this->db->bind('isp', $data['uuid']);
        $this->db->execute();
    }

    public function updateAllPPPOESetOFFByISP($data)
    {
        $this->db->query("UPDATE pppoe SET status='OFF', tanggal_terputus=NOW() WHERE isp_uuid=:isp");
        $this->db->bind('isp', $data['uuid']);
        $this->db->execute();
    }

    // public function updateMsgByID($data)
    // {
    //     $this->db->query("UPDATE `pesan` SET status='1' WHERE id=:id");
    //     $this->db->bind('id', $data['id']);
    //     $this->db->execute();
    //     return $this->db->rowCount();
    // }
    // END UPDATE DATA =============================================================================================


    // CREATE ======================================================================================================
    public function simpanPPPOE($data)
    {
        $uuid = Token::uuid();
        $this->db->query("INSERT INTO `pppoe` (tanggal_daftar, uuid, pppoe, ipv4_address, mac_address, profile, status, isp_uuid ) 
                                                VALUES (
                                                    NOW(), :uuid, :pppoe, :ipv4, :mac, :profile, 'ON', :isp
                                                )");
        $this->db->bind('uuid', $uuid);
        $this->db->bind('pppoe', $data['pppoe']);
        $this->db->bind('ipv4', $data['ipv4']);
        $this->db->bind('mac', $data['mac']);
        $this->db->bind('profile', $data['profile']);
        $this->db->bind('isp', $data['uuid']);
        $this->db->execute();
    }

    public function simpanHistoryDisconnectPPPOE($data)
    {
        $this->db->query("INSERT INTO `history_disconnect_pppoe` (tanggal, pppoe, reason, isp_uuid) VALUES (NOW(), :pppoe, :reason, :isp)");
        $this->db->bind('pppoe', $data['pppoe']);
        $this->db->bind('isp', $data['uuid']);
        $this->db->bind('reason', $data['reason']);
        $this->db->execute();
    }

    public function simpanPaketInternet($data)
    {
        $this->db->query("INSERT INTO paket_internet (nama_paket, isp_uuid)VALUES(:paket, :isp)");
        $this->db->bind('isp', $data['uuid']);
        $this->db->bind('paket', $data['profile']);
        $this->db->execute();
    }

    public function simpanLatihan($data)
    {
        $this->db->query("INSERT INTO tb_latihan (pesan)VALUES(:pesan)");
        $this->db->bind('pesan', $data['pesan']);
        $this->db->execute();
    }

    // public function createMsg($data)
    // {
    //     $this->db->query("INSERT INTO `pesan` (date_create,receiver,message,app_id,status)VALUES(NOW(),:receiver,:message,:appID,'0')");
    //     $this->db->bind('receiver', $data['receiver']);
    //     $this->db->bind('message', $data['message']);
    //     $this->db->bind('appID', $data['appID']);
    //     $this->db->execute();
    //     return $this->db->rowCount();
    // }
    // END CREATE ==================================================================================================

    // DELETE DATA =================================================================================================
    // public function deleteMsgByID($data)
    // {
    //     $this->db->query("DELETE FROM `pesan` WHERE id=:id");
    //     $this->db->bind('id', $data['id']);
    //     $this->db->execute();
    //     return $this->db->rowCount();
    // }

    public function deleteOldHistoryDisconnectPPPOE()
    {
        $today = date('Y-m-d');
        $this->db->query("SELECT * FROM history_disconnect_pppoe WHERE tanggal < DATE_SUB('$today', INTERVAL 1 MONTH)");
        $this->db->execute();
        return $this->db->rowCount();
    }
    // END DELETE DATA =============================================================================================
}
