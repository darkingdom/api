<?php

use chillerlan\QRCode\Data\Number;

class APIController
{
    public $model;
    public function __construct()
    {
        $this->model = new APIModel();
    }

    //---------------------------------------------------------------------- START LOGIN
    public function login($data)
    {
        if ($data['username'] != '' && $data['password'] != '') :
            $login = $this->model->countLogin($data)->total;
            if ($login > 0) :
                $customer = $this->model->getCustomerByUsernamePassword($data);
                $result['status'] = "OK";
                $result['username'] = $customer->username;
            else :
                $result['status'] = "BAD_REQUEST";
            endif;
        else :
            $result['status'] = "BAD_REQUEST";
        endif;
        return $result;
    }

    public function version($data)
    {
        $setting = $this->model->getSetting();
        if ($data['versi'] != $setting->version) :
            $result['update'] = "UPDATE_READY";
            $result['urlFile'] = $setting->url_update;
        else :
            $result['update'] = "NOT_AVAILABLE";
        endif;
        return $result;
    }

    public function auth($data)
    {
        //$this->internetDelay($data);
        $isp = $this->model->countISPByUUID($data)->total;
        if ($isp > 0) :
            $result['status'] = "OK";
        endif;
        return $result;
    }
    //---------------------------------------------------------------------- END LOGIN

    //---------------------------------------------------------------------- START DASHBOARD
    public function dashboard($data)
    {
        $this->deleteOldHistoryDisconnectPPPOE();

        $result['serverName'] = $this->model->getISPbyUUID($data)->nama_isp;
        $result['pelanggan'] = $this->model->countTotalPelanggan($data)->total;
        $result['pelanggan_aktif'] = $this->model->countTotalPelangganAktif($data)->total;
        $result['pelanggan_tidak_aktif'] = $this->model->countTotalPelangganTidakAktif($data)->total;
        $result['pelanggan_tidak_aktif_hari_ini'] = $this->model->countTotalPelangganTidakAktifHariIni($data)->total;
        return $result;
    }
    //---------------------------------------------------------------------- END DASHBOARD

    //---------------------------------------------------------------------- START LIST PRODUCT
    public function listProduct($data)
    {
        if ($data['part'] == "index") :
            $result['produk'] = $this->model->getAllProductByIDCategories($data);
            $result['total'] = $this->model->countAllProductByIDCategories($data)->total;
        elseif ($data['part'] == "prize") :
            $result['harga'] = $this->model->getHargaByUniqID($data)->harga;
        elseif ($data['part'] == "varian-prize") :
            $countVarian = $this->model->countVarianByUniqID($data)->total;
            if ($countVarian > 1) :
                $termurah = $this->model->getHargaMurahVarianByUniqID($data)->harga;
                $termahal = $this->model->getHargaMahalVarianByUniqID($data)->harga;
                $result['harga'] = number_format($termurah, 0, '', '.') . " - " . number_format($termahal, 0, '', '.');
            else :
                $result['harga'] = $this->model->getHargaVarianByUniqID($data)->harga;
            endif;
        endif;
        return $result;
    }
    //---------------------------------------------------------------------- END LIST PRODUCT

    //---------------------------------------------------------------------- START PRODUCT
    public function product($data)
    {
        if ($data['part'] == "index") :
            $result['product'] = $this->model->getProdukByUUID($data);
            $result['image'] = $this->model->getAllMediaProdukByUUID($data);
            $result['varian'] = $this->model->getAllVarianByUUID($data);
        elseif ($data['part'] == "imageProduct") :
            $result['image'] = $this->model->getAllMediaProdukByUUID($data);
        endif;
        return $result;
    }
    //---------------------------------------------------------------------- END PRODUCT





}
