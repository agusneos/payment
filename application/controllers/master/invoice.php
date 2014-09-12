<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_invoice','record');
    }
    
    function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('master/v_invoice');        
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
    
    function update($InvoiceId=null)
    {
        if(!isset($_POST))	
            show_404();

        if($this->record->update($InvoiceId))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal mengubah data'));
    }
    
    function update2()
    {
        if(!isset($_POST))	
            show_404();
        
        $InvoiceId         = intval(addslashes($_POST['InvoiceId']));
        $checkdate  = addslashes($_POST['checkdate']);        
        
        if($this->record->update2($InvoiceId))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
        
    function delete()
    {
        if(!isset($_POST))	
            show_404();

        $InvoiceId = intval(addslashes($_POST['InvoiceId']));
        if($this->record->delete($InvoiceId))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
    
    function upload($file)
    {
        $this->load->library('excel_reader');
        $this->excel_reader->setOutputEncoding('CP1251');
        $this->excel_reader->read('./assets/temp_upload/'.$file);
        error_reporting(E_ALL ^ E_NOTICE);
        
        // Get the contents of the first worksheet
        $data = $this->excel_reader->sheets[0];
        
        // jumlah baris
        $baris  = $data['numRows'];
        $kolom  = $data['numCols'];
        $sukses = 0;
        $gagal = 0;
        
        for ($i = 2; $i <= $baris; $i++)
        {
           $nama    = $data['cells'][$i][1];
           $kelas   = $data['cells'][$i][2];
           $matkul  = $data['cells'][$i][3];
           
           $query = $this->record->upload($nama, $kelas, $matkul);
           if ($query)
           {
               $sukses++;
           }
           else
           {
               $gagal++;
           }
        }
    }

}

/* End of file invoice.php */
/* Location: ./application/controllers/master/invoice.php */