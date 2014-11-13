<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_check extends CI_Model
{    
    static $table   = 'VendInvoiceJour';
    static $vendor  = 'Vendor';
    static $voucher = 'Voucher';
     
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
        
        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, Qty, SalesBalance, CurrencyCode, ExchRate, 
                            IF(Tax = "PPN", SalesBalance * 0.1, "") AS Ppn,
                            IF(Tax = "PPN", SalesBalance * 1.1, SalesBalance) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE)
                    ->where('CheckDate', '0000-00-00 00:00:00');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();
        
        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, Qty, SalesBalance, CurrencyCode, ExchRate,
                            IF(Tax = "PPN", SalesBalance * 0.1, "") AS Ppn,
                            IF(Tax = "PPN", SalesBalance * 1.1, SalesBalance) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE)
                    ->where('CheckDate', '0000-00-00 00:00:00');
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
 
        $result = array();
	$result['total']    = $total;
	$result['rows']     = $data;
        
        return json_encode($result);          
    }
    
    function update($InvoiceId)
    {

        $this->db->where('InvoiceId', $InvoiceId);
        return $this->db->update(self::$table,array(            
            'CheckDate'     => $this->input->post('checkdate',true)
        ));
    }
    
    function createVoucher()
    {
        $OA                 = $this->input->post('OrderAccount',true);
        $PD                 = $this->input->post('PaymentDate',true);
        $Ket                = $this->input->post('PaymentNumber',true);
        $CurrencyCode       = $this->input->post('CurrencyCode',true);
        $ExchRate           = $this->input->post('ExchRate',true);
        $InvoiceAmount      = $this->input->post('InvoiceAmount',true);
     
        $PaymentNumber      = '';
        $Note               = '';
        $KreditUSD          = 0;
        $KreditIDR          = 0;
        $DebetUSD           = 0;
        $DebetIDR           = 0;
        
        if ( $InvoiceAmount > 0 )
        {
            if ( $CurrencyCode == 'IDR')
            {
                $OrderAccount   = $OA;
                $PaymentDate    = $PD;
                $PaymentNumber  = $Ket;
                $KreditIDR      = $InvoiceAmount;                
            }
            else
            {
                $OrderAccount   = $OA;
                $PaymentDate    = $PD;
                $PaymentNumber  = $Ket;
                $KreditUSD      = round($InvoiceAmount, 2);
                $KreditIDR      = $KreditUSD * $ExchRate;                
            }
        }
        else
        {
            if ( $CurrencyCode == 'IDR')
            {
                $OrderAccount   = $OA;
                $PaymentDate    = $PD;
                //$Note           = $Ket;
                $PaymentNumber  = $Ket;
                $DebetIDR       = $InvoiceAmount * -1;                
            }
            else
            {
                $OrderAccount   = $OA;
                $PaymentDate    = $PD;
                //$Note           = $Ket;
                $PaymentNumber  = $Ket;
                $DebetUSD       = round($InvoiceAmount, 2) * -1;
                $DebetIDR       = $DebetUSD * $ExchRate;                
            }
        }
               
        return $this->db->insert(self::$voucher,array(
            'OrderAccount'  => $OrderAccount,
            'PaymentDate'   => $PaymentDate,
            'PaymentNumber' => $PaymentNumber,
            'Note'          => $Note,
            'DebetUSD'      => round($DebetUSD, 2),
            'DebetIDR'      => round($DebetIDR, 0),
            'KreditUSD'     => round($KreditUSD, 2),
            'KreditIDR'     => round($KreditIDR, 0)
        ));
    }
    
}

/* End of file m_check.php */
/* Location: ./application/models/transaksi/m_check.php */