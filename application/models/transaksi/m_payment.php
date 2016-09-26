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
        
        $this->db->select('Id, OrderAccount, InvoiceId, InvoiceDate, Qty, SalesBalance, 
                           ExchRate, CurrencyCode, CheckDate, SalesBalance', FALSE);
        $this->db->where($cond, NULL, FALSE)       
                 ->where('CheckDate !=', '0000-00-00 00:00:00')
                 ->where('PayDate', '0000-00-00');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();
        
        $this->db->select('Id, OrderAccount, InvoiceId, InvoiceDate, Qty, SalesBalance, 
                           ExchRate, CurrencyCode, CheckDate, SalesBalance', FALSE);
        $this->db->where($cond, NULL, FALSE)
                 ->where('CheckDate !=', '0000-00-00 00:00:00')
                 ->where('PayDate', '0000-00-00');
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
    
    function update($InvoiceId, $PayDate)
    {    
        $this->db->where('Id', $InvoiceId);
        return $this->db->update(self::$table,array(
            'PayDate'         => $PayDate
        ));
    }        
    
    function createVoucher()
    {
        $Id                 = $this->input->post('Id',true);
        $CurrencyCode       = $this->input->post('CurrencyCode',true);
        $PayNum             = $this->input->post('PaymentNumber',true);
        $Nt                 = $this->input->post('Note',true);
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
                $PaymentNumber  = $PayNum;
                $Note           = $Nt;
                $KreditIDR      = $InvoiceAmount * -1;    
            }
            else
            {
                $PaymentNumber  = $PayNum;
                $Note           = $Nt;
                $KreditUSD      = round($InvoiceAmount, 2) * -1;
                $KreditIDR      = $KreditUSD * $ExchRate;
            }
        }
        
        return $this->db->insert(self::$voucher,array(
            'VendInvoiceJour_id'=> $Id,
            'OrderAccount'      => $this->input->post('OrderAccount',true),
            'PaymentNumber'     => $PaymentNumber,
            'PaymentDate'       => $this->input->post('PaymentDate',true),
            'Note'              => $Note,
            'DebetUSD'          => round($DebetUSD, 2),
            'DebetIDR'          => round($DebetIDR, 0),
            'KreditUSD'         => round($KreditUSD, 2),
            'KreditIDR'         => round($KreditIDR, 0)
        ));
    }
    
    function split($id, $sum, $sisa){
        $this->db->where('Id', $id);
        $query  = $this->db->get(self::$table);
        $rowa = $query->row();
        if($rowa){
            if($rowa->CurrencyCode == 'IDR'){
                $KreditUSD = 0;
                $KreditIDR = $sum;
                $KreditUSD_sisa = 0;
                $KreditIDR_sisa = $sisa;
            }
            else{
                $KreditUSD = $sum;
                $KreditIDR = round($sum*$rowa->ExchRate, 0);
                $KreditUSD_sisa = $sisa;
                $KreditIDR_sisa = round($sisa*$rowa->ExchRate, 0);
            }
            $querya = $this->db->insert(self::$table,array(     // Create Invoice Sisa Pecah
                'OrderAccount'  => $rowa->OrderAccount,
                'InvoiceId'     => $rowa->InvoiceId,
                'InvoiceDate'   => $rowa->InvoiceDate,
                'Qty'           => $rowa->Qty,
                'SalesBalance'  => $sisa,
                'CurrencyCode'  => $rowa->CurrencyCode,
                'ExchRate'      => $rowa->ExchRate,
                'CheckDate'     => $rowa->CheckDate,
				'AcceptDate'     => $rowa->AcceptDate
            ));
            $this->db->where('Id', $id);
            $queryb = $this->db->update(self::$table,array(     // Update Invoice Pecah
                'SalesBalance'  => $sum
            ));
            $this->db->where('VendInvoiceJour_Id', $id);
            $queryc = $this->db->update(self::$voucher,array(     // Update Voucher Sisa Pecah
                'KreditUSD'             => $KreditUSD,
                'KreditIDR'             => $KreditIDR
            ));
            $this->db->where('InvoiceId', $rowa->InvoiceId);
            $this->db->order_by('Id', 'desc');
            $this->db->limit(1);
            $query_b  = $this->db->get(self::$table);
            $row_b = $query_b->row();
            $queryd = $this->db->insert(self::$voucher,array(     // Create Voucher Sisa Pecah
                'VendInvoiceJour_id'    => $row_b->Id,
                'OrderAccount'          => $rowa->OrderAccount,
                'PaymentNumber'         => $rowa->InvoiceId,
                'PaymentDate'           => $rowa->InvoiceDate,
                'KreditUSD'             => $KreditUSD_sisa,
                'KreditIDR'             => $KreditIDR_sisa
            ));
            if($querya && $queryb && $queryc && $queryd){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
    }
    
    function delete($id){
        $query_a = $this->db->delete(self::$table, array('Id' => $id)); //tabel custinvoicejour
        $query_b = $this->db->delete(self::$voucher, array('VendInvoiceJour_Id' => $id)); //tabel voucher
        if($query_a && $query_b){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>'Gagal'));
        }
    }
}

/* End of file m_payment.php */
/* Location: ./application/models/transaksi/m_payment.php */