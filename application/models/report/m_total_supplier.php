<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_total_supplier extends CI_Model
{    
    static $table = 'VendInvoiceJour';
    
    public function __construct() {
        parent::__construct();
        
    }
      
    function cetak_total_supplier($id)
    {
        $pecah      = explode('-', $id);
        $bulan      = $pecah[0];
        $tahun      = $pecah[1];
        
        $sql        = 'SELECT Vendor.Name,
                       Vendor.VendGroup,
                       VendInvoiceJour.Tax,
                       SUM(VendInvoiceJour.SalesBalance) AS SalesBalance,                        
                       VendInvoiceJour.InvoiceDate,
                       VendInvoiceJour.CurrencyCode
                       
                       FROM '.self::$table.'
                
                       LEFT JOIN Vendor
                       ON VendInvoiceJour.OrderAccount = Vendor.Id
                       
                       WHERE MONTH(VendInvoiceJour.InvoiceDate) = '.$bulan.' AND
                             YEAR(VendInvoiceJour.InvoiceDate) = '.$tahun.'
                                 
                       GROUP BY VendInvoiceJour.OrderAccount,
                             CASE WHEN SalesBalance >= 0 THEN "POS" ELSE "NEG" END
                             
                       ORDER BY Vendor.Name ASC, SalesBalance DESC';
        return $this->db->query($sql);
        
    }
       
    
}

/*
 * End of file m_total_supplier.php
 * Location: ./models/report/m_total_supplier.php
 */