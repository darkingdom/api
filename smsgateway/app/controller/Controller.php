<?php
class APIController
{
    public $model;
    public function __construct()
    {
        $this->model = new APIModel();
    }

    //Variable standart RECEIVER
    //$data['receiver']
    //$data['message']
    //$data['appID']
    //$data['id']

    //variable start SENDER
    //$result['receiver']
    //$result['message']


    public function createMsg($data)
    {
        if ($data['receiver'] != '' &&  $data['message'] != '' && $data['appID'] != '') :
            $this->model->createMsg($data);
        endif;
    }

    public function readMsg()
    {
        $msg = $this->model->getMsgNotSend();
        if (!empty($msg->receiver)) :
            $result['id']       = $msg->id;
            $result['receiver'] = $msg->receiver;
            $result['message']  = $msg->message;
        else :
            $result['message']   = "no message";
        endif;
        return $result;
    }

    public function updateMsg($data)
    {
        $update = $this->model->updateMsgByID($data);
        if ($update > 0) :
            $result['result'] = "berhasil";
        else :
            $result['result'] = "gagal";
        endif;
        return $result;
    }

    public function deleteMsg($data)
    {
        $delete = $this->model->deleteMsgByID($data);
        if ($delete > 0) :
            $result['result'] = "berhasil";
            return $result;
        endif;
    }
}
