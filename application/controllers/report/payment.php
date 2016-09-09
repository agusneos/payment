<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/m_payment','record');
    }
    
    function index()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        //$auth->cek_menu(14);
        $this->load->view('report/payment/v_dialog_payment.php');
    }
    
    function cetak_payment()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        //$auth->cek_menu(14);
        
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $data = $this->record->cetak_payment($_GET['nilai']);
        $this->load->view('report/payment/v_payment.php',$data);
    }
    
}

/*
* End of file payment.php
* Location: ./application/controllers/report/payment.php
*/