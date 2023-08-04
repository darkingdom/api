<?php
class APIController
{
    public $model;
    public function __construct()
    {
        $this->model = new APIModel();
    }

    // MIKROTIK
    // -----------------------
    //$data['pppoe']
    //$data['ipv4']
    //$data['mac']
    //$data['profile']
    //$data['reason']
    //$data['uuid']

    // APP
    // -----------------
    // $data['uuid']
    // $data['username']
    // $data['password']
    // $data['versi']

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // START BOT MIKROTIK ===============================================================
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    public function pppoeON($data)
    {

        $pppoe = $this->model->countPPPOEByName($data)->total;
        if ($pppoe > 0) :
            $this->model->updateStatusONPPPOEByName($data);
        else :
            $this->model->simpanPPPOE($data);
        endif;

        $paketInternet = $this->model->countPaketInternetByName($data)->total;
        if ($paketInternet == 0) :
            $this->model->simpanPaketInternet($data);
        endif;
    }

    public function pppoeOFF($data)
    {
        $this->model->updateStatusOFFPPPOEByName($data);
        $this->model->simpanHistoryDisconnectPPPOE($data);
    }

    // START MONITOR--------------------------------------
    public function internetMonitor($data)
    {
        $this->model->updateInternetMonitor($data);
    }

    public function internetDelay($data)
    {
        $date1 = $this->model->getISPbyUUID($data);
        $date2 = date('Y-m-d H:m:s');
        $date = new DateDifference($date1->internet_monitor, $date2);
        $minute = $date->minute();
        if ($minute > 10) :
            $this->model->updateAllPPPOESetOFFByISP($data);
        endif;
    }

    public function monitorConnectPPPOE($data)
    {
        $client = $this->model->getPPPOEByName($data);
        if ($client->status == "OFF") :
            $this->model->updateStatusONPPPOEByName_2($data);
        endif;
    }

    public function monitorDisconnectPPPOE($data)
    {
        $client = $this->model->getPPPOEByName($data);
        if ($client->status == "ON") :
            $this->model->updateStatusOFFPPPOEByName($data);
        endif;
    }
    // END MONITOR--------------------------------------

    // START LATIHAN -----------------------------------
    public function latihan($data)
    {
        $this->model->simpanLatihan($data);
    }
    // END LATIHAN--------------------------------------

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // END BOT MIKROTIK ================================================================
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++




    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // START MOBILE APP ================================================================
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    //---------------------------------------------------------------------- START LOGIN
    public function login($data)
    {
        if ($data['username'] != '' && $data['password'] != '') :
            $login = $this->model->countLogin($data)->total;
            if ($login > 0) :
                $isp = $this->model->getISPByUsernamePassword($data);
                $result['status'] = "OK";
                $result['uuid'] = $isp->uuid;
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

    //---------------------------------------------------------------------- START CUSTOMER
    public function allCustomer($data)
    {
        $result['total_pelanggan'] = $this->model->countTotalPelanggan($data)->total;
        $result['pelanggan'] = $this->model->getAllCustomer($data);
        return $result;
    }
    //---------------------------------------------------------------------- END CUSTOMER

    //---------------------------------------------------------------------- START DISCONNECT
    public function disconnect($data)
    {
        $result['pelanggan_tidak_aktif'] = $this->model->countTotalPelangganTidakAktif($data)->total;
        $result['pelanggan'] = $this->model->getAllDisconnect($data);
        return $result;
    }

    public function disconnectToday($data)
    {
        $result['pelanggan_tidak_aktif_hari_ini'] = $this->model->countTotalPelangganTidakAktifHariIni($data)->total;
        $result['pelanggan'] = $this->model->getAllDisconnectToday($data);
        return $result;
    }
    //---------------------------------------------------------------------- END DISCONNECT


    //---------------------------------------------------------------------- START UTILITY
    public function deleteOldHistoryDisconnectPPPOE()
    {
        $this->model->deleteOldHistoryDisconnectPPPOE();
    }
    //---------------------------------------------------------------------- END UTILITY

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // END MOBILE APP ==================================================================
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
}
