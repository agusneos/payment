<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/m_user','record');
    }
    
    public function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('admin/v_user');        
    } 
    
    public function create()
    {
        if(!isset($_POST))	
            show_404();

        if($this->record->create())
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal memasukkan data'));
    }     
    
    public function update($id=null)
    {
        if(!isset($_POST))	
            show_404();

        if($this->record->update($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal mengubah data'));
    }
    
    public function reset($id=null)
    {
        if(!isset($_POST))	
            show_404();

        if($this->record->reset($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal mengubah data'));
    }
        
    public function delete()
    {
        if(!isset($_POST))	
            show_404();

        $id = intval(addslashes($_POST['id']));
        if($this->record->delete($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
               
}

/* End of file user.php */
/* Location: ./application/controllers/admin/user.php */