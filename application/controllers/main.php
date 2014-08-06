<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct() {
        parent::__construct();

    }
    
    function index()
    {
        if($this->auth->is_logged_in() == false)
        {
           $this->login();
        }
        else
        {
           $this->main();
        }        
    }
         
    function main()
    {
        // mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->restrict();
        $this->load->view('v_main');
    }
    
    function dashboard()
    {
        // mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->restrict();
        $this->load->view('v_dashboard');
    }
 
    function login()
    {
        $this->load->view('v_login');
    }
    
    function logout()
    {
        if($this->auth->is_logged_in() == true)
        {
            $this->auth->do_logout();
        }
        redirect(''); //redirect ke index
    }
       
    function proses_login()
    { 
        $this->output->set_content_type('application/json');
        if (isset($_POST))
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $success = $this->auth->do_login($username,$password);
            if($success)
            {
               // lemparkan ke halaman index user
               echo json_encode(array(
                    'success'=>true, 
                    'auth_id'=>$this->session->userdata('id')
                ));
            }
            else
            {
                echo json_encode(array('success'=>false));  
            }
        }
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */