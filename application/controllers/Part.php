<?php
use Restserver \Libraries\REST_Controller ;
Class Part extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->Partmodel('Model');
        $this->load->library('form_validation');
    }
    public function index_get(){
        return $this->returnData($this->db->get('sparepart')->result(), false);
    }
    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PartModel->rules();
        if($id == null){
            array_push($rule,[
                    'field' => 'amount',
                    'label' => 'amount',
                    'rules' => 'required'
                ],
                [
                    'field' => 'merk',
                    'label' => 'merk',
                    'rules' => 'required'
                ],
                [
                    'field' => 'created_at',
                    'label' => 'created_at',
                    'rules' => 'required'
                ]
            );
        }
        else{
            array_push($rule,
                [
                    'field' => 'merk',
                    'label' => 'merk',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $part = new PartData();
        $part->namw = $this->post('name');
        $part->merk = $this->post('merk');
        $part->amount = $this->post('amount');
        $part->created_at = $this->post('created_at');
        if($id == null){
            $response = $this->PartModel->store($part);
        }else{
            $response = $this->PartModel->update($part,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
    public function index_delete($id = null){
        if($id == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->PartModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }
    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}
Class PartData{
    public $name;
    public $merk;
    public $amount;
    public $created_at;
}
           