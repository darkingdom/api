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
        elseif ($data['part'] == "newestProduct") :
            $product = $this->model->getNewestProduct($data);
            $item = [];
            foreach ($product as $produk) :
                $set['id'] = $produk->id;
                $set['uuid'] = $produk->uniq_id;
                $set['title'] = $produk->nama_produk;
                $set['varian'] = $produk->varian;
                $set['image'] = @$this->model->getThumbMediaProdukByUUID($produk->uniq_id)->url_image;
                array_push($item, $set);
            endforeach;
        elseif ($data['part'] == "titleCategories") :
            $result['categories'] = $this->model->getCategoriesByID($data)->kategori;
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
        elseif ($data['part'] == "merek") :
            $result['merek'] = $this->model->getMerekByID($data)->nama_merk;
        elseif ($data['part'] == "varianWarna") :
            $result['varianWarna'] = $this->model->getWarnaByID($data)->nama_color;
        endif;
        return $result;
    }
    //---------------------------------------------------------------------- END PRODUCT

    //---------------------------------------------------------------------- START ORDER
    public function order($data)
    {
        if ($data['part'] == 'index') :
            $result['product'] = $this->model->getProdukByUUID($data);
            $result['image'] = @$this->model->getThumbMediaProdukByUUID($data['uuid'])->url_image;
            if ($data['partVarian'] == "varian") :
                $result['varian'] = $this->model->getVarianByUUID($data['uuidVarian']);
            endif;
        elseif ($data['part'] == "varianWarna") :
            $result['varianWarna'] = $this->model->getWarnaByID($data)->nama_color;
        elseif ($data['part'] == "keranjang") :
            $totalKeranjang = $this->model->countKeranjangByIdProduk($data)->total;
            if ($totalKeranjang > 0) :
                $keranjang = $this->model->getKeranjangByIdProduk($data);
                $total = $keranjang->jumlah + $data['jumlah'];
                $update = $this->model->updateKeranjangByIdProduk($data, $total);
                if ($update) :
                    if ($data['varian'] == '1') :
                        $varian = $this->model->getVarianByUUID($data['idvarian']);
                        $stok = $varian->stok - $data['jumlah'];
                        $update = $this->model->updateStokVarianByUUID($data['idvarian'], $stok);
                    else :
                        $produk = $this->model->getProdukByUUID(['uuid' => $data['idproduk']]);
                        $stok = $produk->stok - $data['jumlah'];
                        $update = $this->model->updateStokProdukByUUID($data['idproduk'], $stok);
                    endif;
                    if ($update) :
                        $result['simpan'] = "Berhasil";
                    endif;
                endif;
            else :
                $simpan = $this->model->simpanKeranjang($data);
                if ($simpan > 0) :
                    $result['simpan'] = "Berhasil";
                endif;
            endif;
        endif;
        return $result;
    }
    //---------------------------------------------------------------------- END ORDER

    //---------------------------------------------------------------------- START KERANJANG
    public function keranjang($data)
    {
        if ($data['part'] == 'index') :
            $keranjang = $this->model->getAllKeranjangByUUIDCustomer($data['userid']);
            $item = [];
            $total = 0;
            foreach ($keranjang as $keranjang) :
                $produk = $this->model->getProdukByUUID(["uuid" => $keranjang->id_produk]);
                $set['id'] = $keranjang->id;
                $set['title'] = $produk->nama_produk;
                if ($keranjang->varian == '1') :
                    $set['harga'] = $this->model->getVarianByUUID($keranjang->id_varian)->harga;
                    $varian = $this->model->getVarianByUUID($keranjang->id_varian);
                    $warna = $this->model->getWarnaByID(['id' => $varian->warna])->nama_color;
                    $set['typeVarian'] = $warna . ' ' . $varian->ukuran . ' ' . $varian->jenis;
                else :
                    $set['harga'] = $produk->harga;
                    $set['typeVarian'] = '';
                endif;
                $total = $total + $set['harga'];
                $set['varian'] = $keranjang->varian;
                $set['jumlah'] = $keranjang->jumlah;
                $set['keterangan'] = $keranjang->keterangan;
                $set['image'] = @$this->model->getThumbMediaProdukByUUID($keranjang->id_produk)->url_image;
                array_push($item, $set);
            endforeach;
            $result['totalHarga'] = $total;
            $result['keranjang'] = $item;
        endif;
        return $result;
    }
    //---------------------------------------------------------------------- END KERANJANG





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