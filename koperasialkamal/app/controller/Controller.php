<?php
class APIController
{
    public $model;
    public function __construct()
    {
        $this->model = new APIModel();
    }

    // ================= HALAMAN LOGIN =============================================//
    public function login($data)
    {
        $username = $this->model->checkUsername($data['username'])->total;
        if ($username > 0) {
            $login = $this->model->login($data)->total;
            if ($login == 1) {
                $member = $this->model->getMemberByUsername($data['username']);
                $result['id'] = $member->id;
                $result['status'] = "successful";
            } else {
                $result['status'] = "failed";
                $result['message'] = "Password salah";
            }
        } else {
            $result['status'] = "failed";
            $result['message'] = "Username tidak tersedia";
        }
        return $result;
    }

    public function loginPIN($data)
    {
        $pin = $this->model->loginPIN($data)->total;
        if ($pin > 0) {
            $result['status'] = 'successful';
        } else {
            $result['status'] = "failed";
            $result['message'] = "PIN yang anda masukan salah";
        }
        return $result;
    }
    // ================= END HALAMAN LOGIN =========================================//

    // ================= HALAMAN AUTH ==============================================//
    public function auth($data)
    {
        $member = $this->model->checkMemberByID($data['id'])->total;
        if ($member > 0) {
            $this->model->updateLastLoginByID($data['id']);
            $result = 'successful';
        } else {
            $result = 'failed';
        }
        return $result;
    }
    // ================= END HALAMAN AUTH ==========================================//

    // ================= HALAMAN DASHBOARD =========================================//
    public function dashboard($data)
    {
        $member = $this->model->getMemberByID($data['id']);
        $result['memberName']   = $member->memberName;
        $result['memberNumber'] = $member->memberNumber;
        $result['saldo']        = $member->saldoSimpanan;
        return $result;
    }
    // ================= END HALAMAN DASHBOARD =====================================//

    // ================= HALAMAN PROFILE ===========================================//
    public function profile($data)
    {
        $member = $this->model->getMemberByID($data['id']);
        $result['memberName']   = $member->memberName;
        $result['memberNumber'] = $member->memberNumber;
        return $result;
    }

    public function getProfile($data)
    {
        $member = $this->model->getMemberByID($data['id']);
        $result['memberName']       = $member->memberName;
        $result['homeAddress']      = $member->homeAddress;
        $result['homeCity']         = $member->homeCity;
        $result['memberNumber']     = $member->memberNumber;
        $result['hpNumber']         = $member->hpNumber;
        $result['lastLogin']        = $member->lastLogin;
        $result['email']            = $member->email;
        return $result;
    }

    public function newPassword($data)
    {
        if ($data['newPWD'] == $data['repeatPWD']) {
            $password = $this->model->newPassword($data);
            if ($password > 0) {
                $result['status'] = 'successful';
                $result['message'] = 'Ganti password berhasil';
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Ganti password gagal';
            }
        } else {
            $result['status'] = 'failed';
            $result['message'] = 'Password baru tidak sama';
        }
        return $result;
    }

    public function newPIN($data)
    {
        if ($data['newPIN'] == $data['repeatPIN']) {
            $member = $this->model->checkMemberByPIN($data)->total;
            if ($member > 0) {
                $password = $this->model->checkMemberByPassword($data)->total;
                if ($password > 0) {
                    $pin = $this->model->newPIN($data);
                    if ($pin > 0) {
                        $result['status'] = 'successful';
                        $result['message'] = 'Ganti PIN Berhasil';
                    } else {
                        $result['status'] = 'failed';
                        $result['message'] = 'Ganti PIN gagal. Ulangi sekali lagi';
                    }
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Password salah';
                }
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'PIN lama salah';
            }
        } else {
            $result['status'] = 'failed';
            $result['message'] = 'PIN baru tidak sama';
        }
        return $result;
    }
    // ================= END HALAMAN PROFILE =======================================//

    // ================= HALAMAN HISTORY ===========================================//
    public function history($data)
    {
        $member = $this->model->getMemberByID($data['id']);
        // $result_a['id'] = $data['id'];
        $result_a['saldo'] = $member->saldoSimpanan;
        $result_a['thisMonth'] = date('Y-m-d');
        $debet = $this->model->getDebetThisMonthByMemberID($data['id'])->total;
        if ($debet != NULL) {
            $result_a['debet'] = abs($debet);
        } else {
            $result_a['debet'] = 0;
        }
        $kredit = $this->model->getKreditThisMonthByMemberID($data['id'])->total;
        if ($kredit != NULL) {
            $result_a['kredit'] = $kredit;
        } else {
            $result_a['kredit'] = 0;
        }
        $history = $this->model->getHistoryThisMonthByMemberID($data['id']);
        $result_b = [];
        foreach ($history as $history) :
            $set['historyID'] = $history->id;
            $set['date'] = $history->txDate;
            $set['transaction'] = $history->txName;
            $set['nominal'] = $history->nominal;
            array_push($result_b, $set);
        endforeach;
        $result = array_merge($result_a, ["history" => $result_b]);
        return $result;
    }
    // ================= END HALAMAN HISTORY =======================================//

    // ================= HALAMAN TABUNGAN LEBARAN =======================================//
    public function pageTabunganLebaran($data)
    {
        $member = $this->model->getMemberByID($data['id']);
        $result['tabunganLebaran'] = $member->tabunganLebaran;
        $result['saldoLebaran'] = $member->saldoLebaran;
        return $result;
    }
    // ================= END HALAMAN TABUNGAN LEBARAN ===================================//

    // ================= HALAMAN TABUNGAN DEPOSITO =======================================//
    public function pageTabunganDeposito($data)
    {
        $member = $this->model->getMemberByID($data['id']);
        $result['tabunganDeposito'] = $member->tabunganDeposito;
        $result['saldoDeposito'] = $member->saldoDeposito;
        return $result;
    }
    // ================= END HALAMAN TABUNGAN DEPOSITO ===================================//
}
