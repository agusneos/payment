<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_menu','record');
    }
    
    public function index()
    {        
        $id = $this->session->userdata('id');
        echo $this->record->ambil_menu($id);               
    }

}


/* End of file menu.php */
/* Location: ./application/controllers/menu.php */