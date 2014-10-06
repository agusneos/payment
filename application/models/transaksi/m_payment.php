<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_payment extends CI_Model
{    
    static $table   = 'VendInvoiceJour';
    static $voucher = 'Voucher';
     
    public function __construct() {
        parent::__construct();
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
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
        
      /*  $this->db->select('VendorId, InvoiceId, InvoiceDate, Currency, Rate, SUM(Qty) AS Qty, 
                        SUM(Amount) AS Dpp, SUM(AmountMST) AS DppIdr, SUM(AmountMST)*0.1 AS PpnIdr, 
                        SUM(AmountMST)*1.1 AS AmountIdr, MIN(CheckDate) AS CheckDate, PaymentNumber');
        */
        $this->db->where($cond, NULL, FALSE)       
                 ->where('CheckDate !=', '0000-00-00 00:00:00')
                 //->where('PaymentNumber', '');
                 ->where('PaymentSisa !=', 0);
       // $this->db->group_by('InvoiceId');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();
        
      /*  $this->db->select('VendorId, InvoiceId, InvoiceDate, Currency, Rate, SUM(Qty) AS Qty, 
                        SUM(Amount) AS Dpp, SUM(AmountMST) AS DppIdr, SUM(AmountMST)*0.1 AS PpnIdr, 
                        SUM(AmountMST)*1.1 AS AmountIdr, MIN(CheckDate) AS CheckDate, PaymentNumber');
       * 
       */
        $this->db->where($cond, NULL, FALSE)
                 ->where('CheckDate !=', '0000-00-00 00:00:00')
                 //->where('PaymentNumber', '');
                 ->where('PaymentSisa !=', 0);
      //  $this->db->group_by('InvoiceId');
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
        
        $this->db->select('SUM(Qty) AS Qty, SUM(SalesBalance) AS SalesBalance, 
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
 
        $result = array();
	$result['total']    = $total;
	$result['rows']     = $data;
        $result['footer']   = $data2;
        
        return json_encode($result);          
    }
    
    function update($InvoiceId)
    {    
        $this->db->where('InvoiceId', $InvoiceId);
        return $this->db->update(self::$table,array(
            'PaymentCreateDate' => $this->input->post('paymentcreatedate',true),
            'PaymentDate'       => $this->input->post('paymentdate',true),
            'PaymentNumber'     => $this->input->post('paymentnumber',true)
        ));
    }        
    
    function createVoucher()
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
    
    function bedavendor()
    {
        
    }
}

/* End of file m_payment.php */
/* Location: ./application/models/transaksi/m_payment.php */