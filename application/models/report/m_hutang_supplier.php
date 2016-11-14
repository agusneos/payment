<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_hutang_supplier extends CI_Model
{    
    static $table1 = 'Voucher';
    static $table2 = 'Vendor';
    
    public function __construct() {
        parent::__construct();
        
    }
      
    function cetak_hutang_supplier_summary($date)
    {       
        $sql    = 'SELECT c.Name AS Name,
                          SUM(IF(a.debetusd>0, a.debetusd*-1, a.kreditusd)) AS InvoiceAmount,
                          SUM(IF(a.debetidr>0, a.debetidr*-1, a.kreditidr)) AS InvoiceAmountIdr
                   FROM '.self::$table1.' a
                   LEFT JOIN (SELECT vendinvoicejour_id, paymentnumber, note 
                              FROM '.self::$table1.' 
                              WHERE paymentdate <= "'.$date.'" AND note <> "") b 
                        ON a.vendinvoicejour_id = b.vendinvoicejour_id
                   LEFT JOIN '.self::$table2.' c
                        ON a.orderaccount = c.Id
                   WHERE a.note="" AND b.paymentnumber IS NULL AND a.paymentdate <= "'.$date.'"
                   GROUP BY Name
                   ORDER BY Name ASC';
        $detail = $this->db->query($sql);
        
        $tgl = $this->db->query('SELECT "'.$date.'" as Tanggal');
        
        $result = array();
	$result['date'] = $tgl;
	$result['rows'] = $detail;
        
        return $result;
    }
    
    function cetak_hutang_supplier_detail($date)
    {       
        $sql    = 'SELECT c.Name AS Name, MONTH(a.paymentdate) AS Bulan, YEAR(a.paymentdate) AS Tahun,
                          SUM(IF(a.debetusd>0, a.debetusd*-1, a.kreditusd)) AS InvoiceAmount,
                          SUM(IF(a.debetidr>0, a.debetidr*-1, a.kreditidr)) AS InvoiceAmountIdr
                   FROM '.self::$table1.' a
                   LEFT JOIN (SELECT vendinvoicejour_id, paymentnumber, note 
                              FROM '.self::$table1.' 
                              WHERE paymentdate <= "'.$date.'" AND note <> "") b 
                        ON a.vendinvoicejour_id = b.vendinvoicejour_id
                   LEFT JOIN '.self::$table2.' c
                        ON a.orderaccount = c.Id
                   WHERE a.note="" AND b.paymentnumber IS NULL AND a.paymentdate <= "'.$date.'"
                   GROUP BY Name, Bulan, Tahun
                   ORDER BY Name ASC, Tahun ASC, Bulan ASC';
        $detail = $this->db->query($sql);
        
        $tgl = $this->db->query('SELECT "'.$date.'" as Tanggal');
        
        $result = array();
	$result['date'] = $tgl;
	$result['rows'] = $detail;
        
        return $result;
    }
    
    function cetak_hutang_supplier_invoice($date)
    {       
        $sql    = 'SELECT c.Name AS Name, a.paymentnumber AS InvoiceId,
                          IF(a.debetusd>0, a.debetusd*-1, a.kreditusd) AS InvoiceAmount,
                          IF(a.debetidr>0, a.debetidr*-1, a.kreditidr) AS InvoiceAmountIdr
                   FROM '.self::$table1.' a
                   LEFT JOIN (SELECT vendinvoicejour_id, paymentnumber, note 
                              FROM '.self::$table1.' 
                              WHERE paymentdate <= "'.$date.'" AND note <> "") b 
                        ON a.vendinvoicejour_id = b.vendinvoicejour_id
                   LEFT JOIN '.self::$table2.' c
                        ON a.orderaccount = c.Id
                   WHERE a.note="" AND b.paymentnumber IS NULL AND a.paymentdate <= "'.$date.'"
                   ORDER BY Name ASC, InvoiceId ASC';
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