<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_invoice','record');
        $this->load->library("PHPExcel");
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
    
    function upload()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        move_uploaded_file($_FILES["file"]["tmp_name"],
                "assets/temp_upload/" . $_FILES["file"]["name"]);
        ini_set('memory_limit', '-1');
        $inputFileName = 'assets/temp_upload/' . $_FILES["file"]["name"];
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        }
        catch(Exception $e) {
            die('Error loading file :' . $e->getMessage());
        }
        $worksheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        $baris  = count($worksheet);
        $ok     = 0;
        $ng     = 0;
        
        for ($i = 1; $i < ($baris+1); $i++)
        {
            $tgl_asli = str_replace('/', '-', $worksheet[$i]['C']);
            $exp_tgl_asli = explode('-', $tgl_asli);  
            $exp_tahun = explode(' ', $exp_tgl_asli[2]);
            $tgl_sql = $exp_tahun[0].'-'.$exp_tgl_asli[0].'-'.$exp_tgl_asli[1]; // pERUBAHAN FORMAT TANGGAL KE MYSQL

            $OrderAccount        = $worksheet[$i]['A'];
            $InvoiceId           = $worksheet[$i]['B'];
            $InvoiceDate         = $tgl_sql;
            $Qty                 = $worksheet[$i]['D'];
            $SalesBalance        = $worksheet[$i]['E'];
            $CurrencyCode        = $worksheet[$i]['F'];
            $ExchRate            = $worksheet[$i]['G'];
            $query = $this->record->upload($OrderAccount, $InvoiceId, $InvoiceDate, $Qty,
                                         $SalesBalance, $CurrencyCode, $ExchRate);
            if ($query)
            {
                $ok++;
            }
            else
            {
                $ng++;
            }
        }
        unlink('assets/temp_upload/' . $_FILES["file"]["name"]);
        echo json_encode(array('success'=>true,
                                'total'=>'Total Data: '.($baris),
                                'ok'=>'Data OK: '.$ok,
                                'ng'=>'Data NG: '.$ng));
    }
    
    function delete()
    {
        $auth       = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        $InvoiceId          = addslashes($_POST['InvoiceId']);
        
        echo $this->record->delete($InvoiceId);
        
    }
    
    function updateRate()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();

        if($this->record->updateRate())
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }

}

/* End of file invoice.php */
/* Location: ./application/controllers/master/invoice.php */