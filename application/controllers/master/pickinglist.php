<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pickinglist extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_pickinglist','record');
    }
    
    function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('master/v_pickinglist');        
    } 
    
    function create()
    {
        if(!isset($_POST))	
            show_404();

        if($this->record->create())
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal memasukkan data'));
    }     
    
    function update($id=null)
    {
        if(!isset($_POST))	
            show_404();

        if($this->record->update($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal mengubah data'));
    }
        
    function delete()
    {
        if(!isset($_POST))	
            show_404();

        $id = intval(addslashes($_POST['id']));
        if($this->record->delete($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
    
    function update2()
    {
        if(!isset($_POST))	
            show_404();
        
        $id = intval(addslashes($_POST['id']));
        $actual_qty = addslashes($_POST['actual_qty']);
        $box        = addslashes($_POST['box']);
        $urgent     = addslashes($_POST['urgent']);
        $no_stock   = addslashes($_POST['no_stock']);
        $picked   = addslashes($_POST['picked']);
        $close      = addslashes($_POST['close']);
        
        if($this->record->update2($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
    
            
}

/* End of file pickinglist.php */
/* Location: ./application/controllers/master/pickinglist.php */