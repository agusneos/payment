<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_saldo_supplier extends CI_Model
{    
    static $table = 'VendInvoiceJour';
    
    public function __construct() {
        parent::__construct();
        
    }
    
    function get_supp()
    {
        $this->db->select('Id, Name');        
        $this->db->order_by('Id', 'ASC');
        $query  = $this->db->get('Vendor');
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
      
    function cetak_saldo_supplier($vt)
    {
        $pecah      = explode('-', $vt);
        $vendor     = $pecah[0];
        $tahun      = $pecah[1];
        
        $this->db->query('SELECT @s:=0');
        $this->db->query('SELECT @t:=0');
        $this->db->query('SELECT OrderAccount, PaymentDate, PaymentNumber, Note,
                    DebetUSD, DebetIDR, KreditUSD, KreditIDR, 
                    @s:=@s+KreditUSD-DebetUSD as saldo_usd, @t:=@t+KreditIDR-DebetIDR as saldo_IDR
                    FROM voucher
                    WHERE OrderAccount = "'.$vendor.'" AND YEAR(PaymentDate) < '.$tahun.'
                    ORDER BY PaymentDate ASC;');     
  
        return $this->db->query('SELECT OrderAccount, PaymentDate, PaymentNumber, Note,
                    DebetUSD, DebetIDR, KreditUSD, KreditIDR, 
                    @s:=@s+KreditUSD-DebetUSD as saldo_usd, @t:=@t+KreditIDR-DebetIDR as saldo_IDR
                    FROM voucher
                    WHERE OrderAccount = "'.$vendor.'" AND YEAR(PaymentDate) = '.$tahun.'
                    ORDER BY PaymentDate ASC;');
    }
       
    
}

/*
 * End of file m_total_supplier.php
 * Location: ./models/report/m_total_supplier.php
 */