<?php
class APIModel
{
    public $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    // GET =========================================================================================================
    public function getCustomerByUsernamePassword($data)
    {
        $password = hash('sha256', $data['password']);
        $this->db->query("SELECT * FROM tb_customer WHERE username=:username && password=:password");
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', $password);
        return $this->db->single();
    }

    public function getHargaVarianByUniqID($data)
    {
        $this->db->query("SELECT * FROM tb_produk_varian WHERE id_uniq_produk=:uniq");
        $this->db->bind('uniq', $data['uniq_id']);
        return $this->db->single();
    }

    public function getHargaByUniqID($data)
    {
        $this->db->query("SELECT harga FROM tb_produk WHERE uniq_id=:uniq");
        $this->db->bind('uniq', $data['uniq_id']);
        return $this->db->single();
    }

    public function getHargaMurahVarianByUniqID($data)
    {
        $this->db->query("SELECT harga FROM tb_produk_varian WHERE id_uniq_produk=:uniq ORDER BY harga ASC LIMIT 1");
        $this->db->bind('uniq', $data['uniq_id']);
        return $this->db->single();
    }

    public function getHargaMahalVarianByUniqID($data)
    {
        $this->db->query("SELECT harga FROM tb_produk_varian WHERE id_uniq_produk=:uniq ORDER BY harga DESC LIMIT 1");
        $this->db->bind('uniq', $data['uniq_id']);
        return $this->db->single();
    }

    public function getProdukByUUID($data)
    {
        $this->db->query("SELECT * FROM tb_produk WHERE uniq_id=:uuid");
        $this->db->bind('uuid', $data['uuid']);
        return $this->db->single();
    }

    // END GET =====================================================================================================

    // GET ALL =====================================================================================================
    public function getAllProductByIDCategories($data)
    {
        $this->db->query("SELECT * FROM tb_produk WHERE (id_kategori_1=:kategori || id_kategori_2=:kategori || id_kategori_3=:kategori || id_kategori_4=:kategori) LIMIT :page,:limit");
        $this->db->bind('kategori', $data['kategori']);
        $this->db->bind('page', $data['page']);
        $this->db->bind('limit', $data['limit']);
        return $this->db->resultSet();
    }

    public function getAllMediaProdukByUUID($data)
    {
        $this->db->query("SELECT * FROM tb_produk_media WHERE id_uniq_produk=:uuid");
        $this->db->bind('uuid', $data['uuid']);
        return $this->db->resultSet();
    }

    public function getAllVarianByUUID($data)
    {
        $this->db->query("SELECT * FROM tb_produk_varian WHERE id_uniq_produk=:uuid");
        $this->db->bind('uuid', $data['uuid']);
        return $this->db->resultSet();
    }

    // public function getAllDisconnectToday($data)
    // {
    //     $tahun = date('Y');
    //     $bulan = date('m');
    //     $hari = date('d');
    //     $this->db->query("SELECT DISTINCT * FROM history_disconnect_pppoe WHERE isp_uuid=:isp && DAY(tanggal)='$hari' && MONTH(tanggal)='$bulan' && YEAR(tanggal)='$tahun'");
    //     $this->db->bind('isp', $data['uuid']);
    //     return $this->db->resultSet();
    // }

    // END GET ALL =================================================================================================

    // COUNT DATA ==================================================================================================
    public function countAllProductByIDCategories($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM tb_produk WHERE (id_kategori_1=:kategori || id_kategori_2=:kategori || id_kategori_3=:kategori || id_kategori_4=:kategori)");
        $this->db->bind('kategori', $data['kategori']);
        return $this->db->single();
    }

    public function countLogin($data)
    {
        $password = hash('sha256', $data['password']);
        $this->db->query("SELECT COUNT(*) AS total FROM tb_customer WHERE username=:username && password=:password");
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', $password);
        return $this->db->single();
    }

    public function countVarianByUniqID($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM tb_produk_varian WHERE id_uniq_produk=:uniq");
        $this->db->bind('uniq', $data['uniq_id']);
        return $this->db->single();
    }
    // END COUNT DATA ==============================================================================================

    // UPDATE DATA =================================================================================================


    // public function updateMsgByID($data)
    // {
    //     $this->db->query("UPDATE `pesan` SET status='1' WHERE id=:id");
    //     $this->db->bind('id', $data['id']);
    //     $this->db->execute();
    //     return $this->db->rowCount();
    // }
    // END UPDATE DATA =============================================================================================


    // CREATE ======================================================================================================

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

    // END DELETE DATA =============================================================================================
}
