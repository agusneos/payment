<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_total_supplier extends CI_Model
{    
    static $table   = 'VendInvoiceJour';
    static $vendor  = 'Vendor';


    public function __construct() {
        parent::__construct();
        
    }
      
    function cetak_total_supplier($id)
    {
        $pecah      = explode('-', $id);
        $bulan      = $pecah[0];
        $tahun      = $pecah[1];
        
        $sql = 'SELECT Name, AcceptDate,
                SUM(IF ( CurrencyCode != "IDR", SalesBalance, 0)) AS DPP_USD,
                SUM(IF ( CurrencyCode = "IDR", SalesBalance, SalesBalance * ExchRate)) AS DPP_IDR

                FROM '.self::$table.'

                LEFT JOIN '.self::$vendor.'
                ON '.self::$table.'.OrderAccount = '.self::$vendor.'.Id

                WHERE MONTH(AcceptDate) = '.$bulan.' AND
                      YEAR(AcceptDate) = '.$tahun.'

                GROUP BY OrderAccount,
                CASE WHEN SalesBalance >= 0 THEN "POS" ELSE "NEG" END

                ORDER BY Name ASC, SalesBalance DESC';
        
        return $this->db->query($sql);
    }
    
   /* function cetak_total_supplier($id)
    {
        $pecah      = explode('-', $id);
        $bulan      = $pecah[0];
        $tahun      = $pecah[1];
        
        $sql = 'SELECT Name, InvoiceDate,
                SUM(IF ( CurrencyCode != "IDR", SalesBalance, 0)) AS DPP_USD,
                SUM(IF ( CurrencyCode = "IDR", SalesBalance, SalesBalance * ExchRate)) AS DPP_IDR,
                SUM(IF ( '.self::$table.'.Tax = "PPN", SalesBalance * 0.1, 0)) AS PPN,
                SUM(IF ( '.self::$table.'.Tax = "PPN", IF ( CurrencyCode = "IDR", SalesBalance, SalesBalance * ExchRate) * 1.1, IF ( CurrencyCode = "IDR", SalesBalance, SalesBalance * ExchRate) )) AS TOTAL

                FROM '.self::$table.'

                LEFT JOIN '.self::$vendor.'
                ON '.self::$table.'.OrderAccount = '.self::$vendor.'.Id

                WHERE MONTH(InvoiceDate) = '.$bulan.' AND
                      YEAR(InvoiceDate) = '.$tahun.'

                GROUP BY OrderAccount,
                CASE WHEN SalesBalance >= 0 THEN "POS" ELSE "NEG" END

                ORDER BY Name ASC, SalesBalance DESC';
        
        return $this->db->query($sql);
    }
    * 
    */
       
    
}

/*
 * End of file m_total_supplier.php
 * Location: ./models/report/m_total_supplier.php
 */