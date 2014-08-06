<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/m_menu','record');
    }
    
    public function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('admin/v_menu');        
    }
    
    public function getParent()
    {        
        echo $this->record->getParent();       
      
    }
    
    public function getUser()
    {        
        echo $this->record->getUser();       
      
    }
    
    public function enumType()
    {
        echo $this->record->enumField('menu', 'type');
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

/* End of file menu.php */
/* Location: ./application/controllers/admin/menu.php */