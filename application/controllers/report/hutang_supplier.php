<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hutang_supplier extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/m_hutang_supplier','record');
    }
    
    function index2()
    {
        $auth = new Auth();

        $auth->restrict();
        //$auth->cek_menu(14);
        $this->load->view('report/hutang_supplier/v_dialog_hutang_supplier.php');
    }

    function get_dept()
    {
        echo $this->record->get_dept();
    }
    
    function index()
    {
        $auth = new Auth();

        $auth->restrict();
        //$auth->cek_menu(14);
        
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        //$id = $this->uri->segment(4);
        $data['rows'] = $this->record->cetak_hutang_supplier();
        $this->load->view('report/hutang_supplier/v_hutang_supplier.php',$data);
    }
    
}

/*
* End of file hutang_supplier.php
* Location: ./application/controllers/report/hutang_supplier.php
*/