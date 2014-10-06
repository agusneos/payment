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
       // $group      = $pecah[0];
        $group      = 'LOKAL';
        $bulan      = $pecah[1];
        $tahun      = $pecah[2];
        
        $sql        = 'SELECT Vendor.Name,
                       Vendor.VendGroup,
                       SUM(VendInvoiceJour.SalesBalance) AS SalesBalance,                        
                       VendInvoiceJour.InvoiceDate,
                       VendInvoiceJour.CurrencyCode
                       
                       FROM '.self::$table.'
                
                       LEFT JOIN Vendor
                       ON VendInvoiceJour.OrderAccount = Vendor.Id
                       
                       WHERE Vendor.VendGroup = "'.$group.'" AND
                             MONTH(VendInvoiceJour.InvoiceDate) = '.$bulan.' AND
                             YEAR(VendInvoiceJour.InvoiceDate) = '.$tahun.'
                                 
                       GROUP BY VendInvoiceJour.OrderAccount,
                             CASE WHEN SalesBalance >= 0 THEN "POS" ELSE "NEG" END
                             
                       ORDER BY Vendor.Name ASC, SalesBalance DESC';
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
       
    
}

/*
 * End of file m_total_supplier.php
 * Location: ./models/report/m_total_supplier.php
 */