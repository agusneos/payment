<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_voucher extends CI_Model
{    
    static $voucher = 'Voucher';
    static $vendor  = 'Vendor';
    
    public function __construct() {
        parent::__construct();
        
    }
    
    function cetak_voucher($vt)
    {
        $pecah      = explode('-', $vt);
        $bulan      = $pecah[0];
        $tahun      = $pecah[1];
        
        $this->db->select('Name, PaymentDate, PaymentNumber, SUM(DebetUSD) AS DebetUSD, SUM(DebetIDR) AS DebetIDR');
        $this->db->join(self::$vendor, self::$voucher.'.OrderAccount='.self::$vendor.'.Id', 'left');
        $this->db->where('PaymentNumber != ""')
                 ->where('DebetIDR > 0')
                 ->where('MONTH(PaymentDate) = '.$bulan)
                 ->where('YEAR(PaymentDate) = '.$tahun);
        $this->db->group_by('OrderAccount, PaymentNumber');
        return $this->db->get(self::$voucher);
    }       
    
}

/*
 * End of file m_voucher.php
 * Location: ./models/report/m_voucher.php
 */