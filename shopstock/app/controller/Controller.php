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
                $result['uuid'] = $customer->uuid;
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
            $result['urlFile'] = $setting->url_file;
            $result['appName'] = $setting->app_name;
        else :
            $result['update'] = "NOT_AVAILABLE";
        endif;
        return $result;
    }

    public function auth($data)
    {
        $isp = $this->model->countCustomerByUUID($data)->total;
        if ($isp > 0) :
            $result['status'] = "OK";
        endif;
        return $result;
    }
    //---------------------------------------------------------------------- END LOGIN

    //---------------------------------------------------------------------- START DASHBOARD
    public function dashboard($data)
    {
        $result['appSetting'] = $this->model->getAppSetting();
        return $result;
    }
    //---------------------------------------------------------------------- END DASHBOARD

    //---------------------------------------------------------------------- START LIST PRODUCT
    public function listProduct($data)
    {
        if ($data['part'] == "index") :
            $result['total'] = $this->model->countAllProductByIDCategories($data)->total;
            $produk = $this->model->getAllProductByIDCategories($data);

            $item = [];
            foreach ($produk as $produk) :
                $set['id'] = $produk->id;
                $set['uuid'] = $produk->uniq_id;
                $set['title'] = $produk->nama_produk;
                $set['varian'] = $produk->varian;
                $set['image'] = @$this->model->getThumbMediaProdukByUUID($produk->uniq_id)->url_image;
                array_push($item, $set);
            endforeach;
            $result = array_merge($result, ["produk" => $item]);

        elseif ($data['part'] == "prize") :
            $result['harga'] = Numeric::numberFormat($this->model->getHargaByUniqID($data)->harga);
        elseif ($data['part'] == "varian-prize") :
            $countVarian = $this->model->countVarianByUniqID($data)->total;
            if ($countVarian > 1) :
                $termurah = $this->model->getHargaMurahVarianByUniqID($data)->harga;
                $termahal = $this->model->getHargaMahalVarianByUniqID($data)->harga;
                $result['harga'] = Numeric::numberFormat($termurah) . " - " . Numeric::numberFormat($termahal);
            else :
                $result['harga'] = Numeric::numberFormat($this->model->getHargaVarianByUniqID($data)->harga);
            endif;
        elseif ($data['part'] == "search") :
            $produk = $this->model->getAllSearchProduct($data);

            $item = [];
            foreach ($produk as $produk) :
                $set['id'] = $produk->id;
                $set['uuid'] = $produk->uniq_id;
                $set['title'] = $produk->nama_produk;
                $set['varian'] = $produk->varian;
                $set['image'] = @$this->model->getThumbMediaProdukByUUID($produk->uniq_id)->url_image;
                array_push($item, $set);
            endforeach;
            $result['produk'] = $item;

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

//EXAMPLE======================

// $result_b = [];
// foreach ($history as $history) :
//     $set['historyID'] = $history->id;
//     $set['date'] = $history->txDate;
//     $set['transaction'] = $history->txName;
//     $set['nominal'] = $history->nominal;
//     array_push($result_b, $set);
// endforeach;
// $result = array_merge($result_a, ["history" => $result_b]);