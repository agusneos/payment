<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_payment extends CI_Model
{    
    static $table1 = 'Voucher';
    static $table2 = 'Vendor';
    
    public function __construct() {
        parent::__construct();
        
    }
      
    function cetak_payment($date){
        $this->db->join(self::$table2, 'OrderAccount=Id', 'left');
        $this->db->where('DATE(PaymentCreateDate)',$date)
                 ->where('Note != ""');
        $query  = $this->db->get(self::$table1);
        
        $tgl = $this->db->query('SELECT "'.$date.'" as Tanggal');
        
        $result = array();
	$result['date'] = $tgl;
	$result['rows'] = $query;
        
        return $result;
    }
   
}

/*
 * End of file m_hutang_supplier.php
 * Location: ./models/report/m_hutang_supplier.php
 */