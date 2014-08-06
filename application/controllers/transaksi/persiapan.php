<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persiapan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_persiapan','record');
    }
    
    function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('transaksi/persiapan/v_persiapan');        
    }   
    
    function update2()
    {
        if(!isset($_POST))	
            show_404();
        
        $id = intval(addslashes($_POST['id']));
    //    $box        = addslashes($_POST['box']);
        $urgent     = addslashes($_POST['urgent']);
        $no_stock   = addslashes($_POST['no_stock']);
        $picked     = addslashes($_POST['picked']);
        $close      = addslashes($_POST['close']);
        
        if($this->record->update2($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
    
    function lot()
    {                
        $su = $this->uri->segment(4);
        $data['nilai'] = $su;        
        
        if (isset($_GET['grid']))          
            echo $this->record->lot($_GET['nilai']); 
        else             
            $this->load->view('transaksi/persiapan/v_lot', $data);    
    }
    
    function lot_create()
    {                
        if(!isset($_POST))	
            show_404();

        if($this->record->lot_create())
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal memasukkan data'));  
    }
    
    function lot_delete()
    {
        if(!isset($_POST))	
            show_404();

        $id     = intval(addslashes($_POST['id']));
      //  $time   = addslashes($_POST['time']);
        if($this->record->lot_delete($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
    
    function last_box($wmsordertrans_id=null)
    {
        echo $this->record->last_box($wmsordertrans_id);
    }
    
    function lot_edit($id=null)
    {
        if(!isset($_POST))	
            show_404();

        if($this->record->lot_edit($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal mengubah data'));
    }
            
}

/* End of file persiapan.php */
/* Location: ./application/controllers/transaksi/persiapan.php */