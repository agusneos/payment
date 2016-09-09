<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Saldo_supplier extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/m_saldo_supplier','record');
    }
    
    function index()
    {
        $auth = new Auth();

        $auth->restrict();
        //$auth->cek_menu(14);
        $this->load->view('report/saldo_supplier/v_dialog_saldo_supplier');
    }

    function get_supp()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        echo $this->record->get_supp();
    }
    
    function cetak_saldo_supplier()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        $vendor = addslashes($_GET['vend']);
        $from   = addslashes($_GET['from']);
        $to     = addslashes($_GET['to']);
        
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        //$vt = $this->uri->segment(4);
        //$data['rows'] = $this->record->cetak_saldo_supplier($vt);
        $data = $this->record->cetak_saldo_supplier_date($vendor, $from, $to);
        $this->load->view('report/saldo_supplier/v_saldo_supplier.php',$data);
    }
    
}

/*
* End of file saldo_supplier.php
* Location: ./application/controllers/report/saldo_supplier.php
*/