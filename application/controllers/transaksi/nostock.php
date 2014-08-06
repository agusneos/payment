<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nostock extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_nostock','record');
    }
    
    function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('transaksi/nostock/v_nostock');        
    }   
    
    function update()
    {
        if(!isset($_POST))	
            show_404();
        
        $id         = intval(addslashes($_POST['id']));
        $no_stock   = addslashes($_POST['no_stock']);
        
        if($this->record->update($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }    
                
}

/* End of file nostock.php */
/* Location: ./application/controllers/transaksi/nostock.php */