<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_payment extends CI_Model
{    
    static $table   = 'VendInvoiceJour';
    static $voucher = 'Voucher';
    static $vendor  = 'Vendor';
     
    public function __construct() {
        parent::__construct();
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'InvoiceId';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        
        $filterRules = isset($_POST['filterRules']) ? ($_POST['filterRules']) : '';
	$cond = '1=1';
	if (!empty($filterRules)){
            $filterRules = json_decode($filterRules);
            foreach($filterRules as $rule){
                $rule = get_object_vars($rule);
                $field = $rule['field'];
                $op = $rule['op'];
                $value = $rule['value'];
                if (!empty($value)){
                    if ($op == 'contains'){
                        $cond .= " and ($field like '%$value%')";
                    } else if ($op == 'beginwith'){
                        $cond .= " and ($field like '$value%')";
                    } else if ($op == 'endwith'){
                        $cond .= " and ($field like '%$value')";
                    } else if ($op == 'equal'){
                        $cond .= " and $field = $value";
                    } else if ($op == 'notequal'){
                        $cond .= " and $field != $value";
                    } else if ($op == 'less'){
                        $cond .= " and $field < $value";
                    } else if ($op == 'lessorequal'){
                        $cond .= " and $field <= $value";
                    } else if ($op == 'greater'){
                        $cond .= " and $field > $value";
                    } else if ($op == 'greaterorequal'){
                        $cond .= " and $field >= $value";
                    } 
                }
            }
	}        
        
        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, Qty, PaymentSisa, CurrencyCode, ExchRate, Tax,
                            IF(Tax = "PPN", PaymentSisa * 0.1, "") AS Ppn,
                            IF(Tax = "PPN", PaymentSisa * 1.1, PaymentSisa) AS InvoiceAmount', FALSE);        
        $this->db->where($cond, NULL, FALSE)       
                 ->where('CheckDate !=', '0000-00-00 00:00:00')
                 ->where('PaymentSisa !=', 0);
      //  $this->db->join(self::$vendor, self::$table.'.OrderAccount='.self::$vendor.'.Id', 'left');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();
        
        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, Qty, PaymentSisa, CurrencyCode, ExchRate, Tax,
                            IF(Tax = "PPN", PaymentSisa * 0.1, "") AS Ppn,
                            IF(Tax = "PPN", PaymentSisa * 1.1, PaymentSisa) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE)
                 ->where('CheckDate !=', '0000-00-00 00:00:00')
                 ->where('PaymentSisa !=', 0);
       // $this->db->join(self::$vendor, self::$table.'.OrderAccount='.self::$vendor.'.Id', 'left');
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
        
        /*$this->db->select('SUM(Qty) AS Qty, SUM(SalesBalance) AS SalesBalance, 
                        SUM(0) AS InvoiceAmount, SUM(0) AS ExchRate, 
                        SUM(InvoiceRoundOff) AS InvoiceRoundOff, SUM(SumTax) AS SumTax, 
                        SUM(InvoiceAmountMST) AS InvoiceAmountMST');
         $this->db->where($cond, NULL, FALSE)
                 ->where('CheckDate !=', '0000-00-00 00:00:00')
                 //->where('PaymentNumber', '');
                 ->where('PaymentSisa !=', 0);
        $query2  = $this->db->get(self::$table);
        
        $data2 = array();
        foreach ( $query2->result() as $row2 )
        {
            array_push($data2, $row2); 
        }   
         * 
         */
 
        $result = array();
	$result['total']    = $total;
	$result['rows']     = $data;
        //$result['footer']   = $data2;
        
        return json_encode($result);          
    }
    
    function update($InvoiceId)
    {    
        $this->db->where('InvoiceId', $InvoiceId);
        return $this->db->update(self::$table,array(
            'PaymentSisa'         => $this->input->post('PaymentSisa',true)
        ));
    }        
    
    function createVoucher()
    {
        $CurrencyCode       = $this->input->post('CurrencyCode',true);
        $PayNum             = $this->input->post('PaymentNumber',true);
        $Nt                 = $this->input->post('Note',true);
        $ExchRate           = $this->input->post('ExchRate',true);
        $InvoiceAmount      = $this->input->post('InvoiceAmount',true);
        
        $DebetUSD           = 0;
        $DebetIDR           = 0;
       // $KreditUSD          = 0;
       // $KreditIDR          = 0;
        
        if ( $InvoiceAmount >= 0 )
        {
            if ( $CurrencyCode == 'IDR')
            {
                $PaymentNumber  = $PayNum;
                $Note           = $Nt;
                $DebetIDR       = $InvoiceAmount;                
            }
            else
            {
                $PaymentNumber  = $PayNum;
                $Note           = $Nt;
                $DebetUSD       = round($InvoiceAmount, 2);
                $DebetIDR       = $DebetUSD * $ExchRate;
            }
        }
        else
        {
            if ( $CurrencyCode == 'IDR')
            {
                //$KreditIDR  = $InvoiceAmount;
                $PaymentNumber  = '';
                $Note           = $Nt;
                $DebetIDR       = $InvoiceAmount * -1;    
            }
            else
            {
               // $KreditUSD  = round($InvoiceAmount, 2);
                //$KreditIDR  = $KreditUSD * $ExchRate;
                $PaymentNumber  = '';
                $Note           = $Nt;
                $DebetUSD       = round($InvoiceAmount, 2) * -1;
                $DebetIDR       = $DebetUSD * $ExchRate * -1;
            }
        }
        
        return $this->db->insert(self::$voucher,array(
            'OrderAccount'      => $this->input->post('OrderAccount',true),
            'PaymentNumber'     => $PaymentNumber,
            'PaymentDate'       => $this->input->post('PaymentDate',true),
            'Note'              => $Note,
            'DebetUSD'          => round($DebetUSD, 2),
            'DebetIDR'          => round($DebetIDR, 0),
          //  'KreditUSD'         => round($KreditUSD, 2),
           // 'KreditIDR'         => round($KreditIDR, 0),            
        ));
    }
    
    /*function createVoucher()
    {
        return $this->db->insert(self::$voucher,array(
            'OrderAccount'      =>  $this->input->post('OrderAccount',true),
            'PaymentNumber'     =>  $this->input->post('PaymentNumber',true),
            'PaymentDate'       =>  $this->input->post('PaymentDate',true),
            'CurrencyCode'      =>  $this->input->post('CurrencyCode',true),
            'ExchRate'          =>  $this->input->post('ExchRate',true),
            'InvoiceAmount'     =>  $this->input->post('InvoiceAmount',true),
            'InvoiceAmountMST'  =>  $this->input->post('InvoiceAmountMST',true),
            'PaymentCreateDate' =>  $this->input->post('PaymentCreateDate',true),
        ));
    }
     * 
     */
    
    function createVoucherInvoice()
    {
        return $this->db->insert(self::$voucher,array(
            'OrderAccount'      =>  $this->input->post('OrderAccount',true),
            'PaymentNumber'     =>  $this->input->post('PaymentNumber',true),
            'PaymentDate'       =>  $this->input->post('PaymentDate',true),
            'CurrencyCode'      =>  $this->input->post('CurrencyCode',true),
            'ExchRate'          =>  $this->input->post('ExchRate',true),
            'InvoiceAmount'     =>  $this->input->post('InvoiceAmount',true),
            'InvoiceAmountMST'  =>  $this->input->post('InvoiceAmountMST',true),
            'PaymentCreateDate' =>  $this->input->post('PaymentCreateDate',true),
        ));
    }
        
}

/* End of file m_payment.php */
/* Location: ./application/models/transaksi/m_payment.php */