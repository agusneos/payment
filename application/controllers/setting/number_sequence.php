<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Number_sequence extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('setting/m_number_sequence','record');
    }
    
    public function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('setting/v_number_sequence');        
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
}

/* End of file number_sequence.php */
/* Location: ./application/controllers/setting/number_sequence.php */