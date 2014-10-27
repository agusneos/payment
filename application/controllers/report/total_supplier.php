<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Total_supplier extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/m_total_supplier','record');
    }
    
    function index()
    {
        $auth = new Auth();

        $auth->restrict();
        //$auth->cek_menu(14);
        $this->load->view('report/total_supplier/v_dialog_total_supplier.php');
    }

    function get_dept()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        echo $this->record->get_dept();
    }
    
    function cetak_total_supplier()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        //$auth->cek_menu(14);
        
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $id = $this->uri->segment(4);
        $data['rows'] = $this->record->cetak_total_supplier($id);
        $this->load->view('report/total_supplier/v_total_supplier.php',$data);
    }
    
}

/*
* End of file total_supplier.php
* Location: ./application/controllers/report/total_supplier.php
*/