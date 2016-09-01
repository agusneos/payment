<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_hutang_supplier extends CI_Model
{    
    static $table1 = 'VendInvoiceJour';
    static $table2 = 'Vendor';
    
    public function __construct() {
        parent::__construct();
        
    }
      
    function cetak_hutang_supplier_summary($date)
    {       
        $sql        = 'SELECT Name,
                        SUM(IF(CurrencyCode = "IDR","",IF ('.self::$table1.'.Tax = "PPN",  SalesBalance * 1.1, SalesBalance))) AS InvoiceAmount,
                        SUM(IF ('.self::$table1.'.Tax = "PPN",  SalesBalance * 1.1, SalesBalance) * ExchRate) AS InvoiceAmountIdr
                       
                       FROM '.self::$table1.'
                
                       LEFT JOIN '.self::$table2.'
                       ON '.self::$table1.'.OrderAccount = '.self::$table2.'.Id
                       
                       WHERE PayDate >"'.$date.'" OR PayDate ="0000-00-00"
                                 
                       GROUP BY Name
                             
                       ORDER BY Name ASC';
        //return $this->db->query($sql);
        $detail = $this->db->query($sql);
        
        $tgl = $this->db->query('SELECT "'.$date.'" as Tanggal');
        
        $result = array();
	$result['date'] = $tgl;
	$result['rows'] = $detail;
        
        return $result;
    }
    
    function cetak_hutang_supplier_detail($date)
    {       
        $sql        = 'SELECT Name, MONTH(InvoiceDate) AS Bulan,
                        YEAR(InvoiceDate) AS Tahun,
                        SUM(IF(CurrencyCode = "IDR","",IF ('.self::$table1.'.Tax = "PPN",  SalesBalance * 1.1, SalesBalance))) AS InvoiceAmount,
                        SUM(IF ('.self::$table1.'.Tax = "PPN",  SalesBalance * 1.1, SalesBalance) * ExchRate) AS InvoiceAmountIdr
                       
                       FROM '.self::$table1.'
                
                       LEFT JOIN '.self::$table2.'
                       ON '.self::$table1.'.OrderAccount = '.self::$table2.'.Id
                       
                       WHERE PayDate >"'.$date.'" OR PayDate ="0000-00-00"
                                 
                       GROUP BY Name, Bulan, Tahun
                             
                       ORDER BY Name ASC, Tahun ASC, Bulan ASC';
        //return $this->db->query($sql);
        $detail = $this->db->query($sql);
        
        $tgl = $this->db->query('SELECT "'.$date.'" as Tanggal');
        
        $result = array();
	$result['date'] = $tgl;
	$result['rows'] = $detail;
        
        return $result;
    }
       
    
}

/*
 * End of file m_hutang_supplier.php
 * Location: ./models/report/m_hutang_supplier.php
 */