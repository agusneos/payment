<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hutang_supplier extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/m_hutang_supplier','record');
    }
    
    function index()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        //$auth->cek_menu(14);
        $this->load->view('report/hutang_supplier/v_dialog_hutang_supplier.php');
    }
    
    function cetak_hutang_supplier_summary()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        //$auth->cek_menu(14);
        
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $data['rows'] = $this->record->cetak_hutang_supplier_summary();
        $this->load->view('report/hutang_supplier/v_hutang_supplier_summary.php',$data);
    }
    
    function cetak_hutang_supplier_detail()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        //$auth->cek_menu(14);
        
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $data['rows'] = $this->record->cetak_hutang_supplier_detail();
        $this->load->view('report/hutang_supplier/v_hutang_supplier_detail.php',$data);
    }
    
}

/*
* End of file hutang_supplier.php
* Location: ./application/controllers/report/hutang_supplier.php
*/