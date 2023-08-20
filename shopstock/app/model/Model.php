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

    public function getSetting()
    {
        $this->db->query("SELECT * FROM tb_setting LIMIT 1");
        return $this->db->single();
    }

    public function getAppSetting()
    {
        $this->db->query("SELECT * FROM tb_setting_app LIMIT 1");
        return $this->db->single();
    }

    public function getThumbMediaProdukByUUID($data)
    {
        $this->db->query("SELECT * FROM tb_produk_media WHERE id_uniq_produk=:uuid LIMIT 1");
        $this->db->bind('uuid', $data);
        return $this->db->single();
    }

    public function getCategoriesByID($data)
    {
        $this->db->query("SELECT * FROM tb_kategori WHERE id=:id");
        $this->db->bind('id', $data['id']);
        return $this->db->single();
    }

    public function getMerekByID($data)
    {
        $this->db->query("SELECT * FROM tb_brand WHERE id=:id");
        $this->db->bind('id', $data['id']);
        return $this->db->single();
    }

    public function getWarnaByID($data)
    {
        $this->db->query("SELECT * FROM tb_color WHERE id=:id");
        $this->db->bind('id', (string)$data['id']);
        return $this->db->single();
    }

    public function getVarianByUUID($data)
    {
        $this->db->query("SELECT * FROM tb_produk_varian WHERE uuid=:uuid");
        $this->db->bind('uuid', $data);
        return $this->db->single();
    }

    public function getKeranjangByIdProduk($data)
    {
        $this->db->query("SELECT * FROM tb_keranjang WHERE id_customer=:idcustomer AND id_produk=:idproduk AND id_varian=:idvarian");
        $this->db->bind('idcustomer', $data['userid']);
        $this->db->bind('idproduk', $data['idproduk']);
        $this->db->bind('idvarian', $data['idvarian']);
        return $this->db->single();
    }

    // END GET =====================================================================================================

    // GET ALL =====================================================================================================
    public function getAllProductByIDCategories($data)
    {
        $page = $data['page'];
        $limit = $data['limit'];
        $this->db->query("SELECT * FROM tb_produk WHERE publikasi='public' AND (id_kategori_1=:kategori OR id_kategori_2=:kategori OR id_kategori_3=:kategori OR id_kategori_4=:kategori) LIMIT $page,$limit");
        $this->db->bind('kategori', $data['kategori']);
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

    public function getAllSearchProduct($data)
    {
        $this->db->query("SELECT * FROM tb_produk WHERE publikasi='public' AND (id_kategori_1=:kategori OR id_kategori_2=:kategori OR id_kategori_3=:kategori OR id_kategori_4=:kategori) AND nama_produk LIKE :q LIMIT 30");
        $this->db->bind('q', "%" . $data['q'] . "%");
        $this->db->bind('kategori', $data['kategori']);
        return $this->db->resultSet();
    }

    public function getNewestProduct($data)
    {
        $page = $data['page'];
        $limit = $data['limit'];
        $this->db->query("SELECT * FROM tb_produk WHERE publikasi='public' AND tanggal > DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY tanggal DESC LIMIT $page,$limit");
        return $this->db->resultSet();
    }

    public function getAllKeranjangByUUIDCustomer($data)
    {
        $this->db->query("SELECT * FROM tb_keranjang WHERE id_customer=:uuid");
        $this->db->bind('uuid', $data);
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

    public function countCustomerByUUID($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM tb_customer WHERE uuid=:uuid");
        $this->db->bind('uuid', $data['uuid']);
        return $this->db->single();
    }

    public function countKeranjangByIdProduk($data)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM tb_keranjang WHERE id_customer=:idcustomer AND id_produk=:idproduk AND id_varian=:idvarian");
        $this->db->bind('idcustomer', $data['userid']);
        $this->db->bind('idproduk', $data['idproduk']);
        $this->db->bind('idvarian', $data['idvarian']);
        return $this->db->single();
    }
    // END COUNT DATA ==============================================================================================

    // UPDATE DATA =================================================================================================

    public function updateKeranjangByIdProduk($data, $total)
    {
        $this->db->query("UPDATE tb_keranjang SET jumlah='$total', keterangan=:keterangan WHERE id_customer=:idcustomer AND id_produk=:idproduk AND id_varian=:idvarian");
        $this->db->bind('idcustomer', $data['userid']);
        $this->db->bind('idproduk', $data['idproduk']);
        $this->db->bind('idvarian', $data['idvarian']);
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateStokProdukByUUID($data, $stok)
    {
        $this->db->query("UPDATE tb_produk SET stok='$stok' WHERE uniq_id=:uuid");
        $this->db->bind('uuid', $data);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateStokVarianByUUID($data, $stok)
    {
        $this->db->query("UPDATE tb_produk_varian SET stok='$stok' WHERE uuid=:uuid");
        $this->db->bind('uuid', $data);
        $this->db->execute();
        return $this->db->rowCount();
    }
    // END UPDATE DATA =============================================================================================


    // CREATE ======================================================================================================
    public function simpanKeranjang($data)
    {
        $this->db->query("INSERT INTO `tb_keranjang` (tanggal,id_customer,id_produk,varian,id_varian,jumlah,keterangan)
                                                    VALUES (
                                                        NOW(),:idcustomer,:idproduk,:varian,:idvarian,:jumlah,:keterangan
                                                    )");
        $this->db->bind('idcustomer', $data['userid']);
        $this->db->bind('idproduk', $data['idproduk']);
        $this->db->bind('varian', $data['varian']);
        $this->db->bind('idvarian', $data['idvarian']);
        $this->db->bind('jumlah', (string)$data['jumlah']);
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->execute();
        return $this->db->rowCount();
    }
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
