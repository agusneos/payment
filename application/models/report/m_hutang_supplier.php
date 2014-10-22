<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_hutang_supplier extends CI_Model
{    
    static $table1 = 'VendInvoiceJour';
    static $table2 = 'Vendor';
    
    public function __construct() {
        parent::__construct();
        
    }
      
    function cetak_hutang_supplier_summary()
    {       
        $sql        = 'SELECT Name,
                        SUM(IF(CurrencyCode = "IDR","",IF (Tax = "PPN",  PaymentSisa * 1.1, PaymentSisa))) AS InvoiceAmount,
                        SUM(IF (Tax = "PPN",  PaymentSisa * 1.1, PaymentSisa) * ExchRate) AS InvoiceAmountIdr
                       
                       FROM '.self::$table1.'
                
                       LEFT JOIN '.self::$table2.'
                       ON '.self::$table1.'.OrderAccount = '.self::$table2.'.Id
                       
                       WHERE PaymentSisa != 0
                                 
                       GROUP BY Name
                             
                       ORDER BY Name ASC';
        return $this->db->query($sql);
        /*
        $this->db->select('SUM(VendInvoiceJour.SalesBalance) AS SalesBalance,                        
                        SUM(VendInvoiceJour.SumTax) AS SumTax,
                        SUM(VendInvoiceJour.InvoiceAmount) AS InvoiceAmount,
                        VendInvoiceJour.InvoiceDate,
                        Vendor.*');
        $this->db->join('Vendor', 'VendInvoiceJour.OrderAccount = Vendor.Id', 'left');
        $this->db->where('Vendor.VendGroup', $group)
                 ->where('MONTH(VendInvoiceJour.InvoiceDate)', $bulan)
                 ->where('YEAR(VendInvoiceJour.InvoiceDate)', $tahun);
        $this->db->group_by('VendInvoiceJour.OrderAccount');  
        $this->db->order_by('Vendor.Name', 'ASC');
        return $this->db->get(self::$table);
         * 
         */
    }
    
    function cetak_hutang_supplier_detail()
    {       
        $sql        = 'SELECT Name, MONTH(InvoiceDate) AS Bulan,
                        YEAR(InvoiceDate) AS Tahun,
                        SUM(IF(CurrencyCode = "IDR","",IF (Tax = "PPN",  PaymentSisa * 1.1, PaymentSisa))) AS InvoiceAmount,
                        SUM(IF (Tax = "PPN",  PaymentSisa * 1.1, PaymentSisa) * ExchRate) AS InvoiceAmountIdr
                       
                       FROM '.self::$table1.'
                
                       LEFT JOIN '.self::$table2.'
                       ON '.self::$table1.'.OrderAccount = '.self::$table2.'.Id
                       
                       WHERE PaymentSisa != 0
                                 
                       GROUP BY Name, Bulan, Tahun
                             
                       ORDER BY Name ASC, Tahun ASC, Bulan ASC';
        return $this->db->query($sql);        
    }
       
    
}

/*
 * End of file m_hutang_supplier.php
 * Location: ./models/report/m_hutang_supplier.php
 */