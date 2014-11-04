<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/m_voucher','record');
    }
    
    function index()
    {
        $auth = new Auth();

        $auth->restrict();
        //$auth->cek_menu(14);
        $this->load->view('report/voucher/v_dialog_voucher.php');
    }

    function get_dept()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        echo $this->record->get_dept();
    }
    
    function cetak_voucher()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        //$auth->cek_menu(14);
        
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $id = $this->uri->segment(4);
        $data['rows'] = $this->record->cetak_voucher($id);
        $this->load->view('report/voucher/v_voucher.php',$data);
    }
    
}

/*
* End of file voucher.php
* Location: ./application/controllers/report/voucher.php
*/