<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_invoice extends CI_Model
{    
    static $table = 'VendInvoiceJour';
    static $vendor = 'Vendor';
     
    public function __construct() {
        parent::__construct();
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'Id';
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
        $this->db->where($cond, NULL, FALSE);
                 //->where('CheckDate', '0000-00-00 00:00:00');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();

        $this->db->select('Id, OrderAccount, InvoiceId, InvoiceDate, Qty, SalesBalance, 
                           ExchRate, CurrencyCode, CheckDate, SalesBalance', FALSE);
        $this->db->where($cond, NULL, FALSE);
                 //->where('CheckDate', '0000-00-00 00:00:00');
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
        
    function upload($OrderAccount, $InvoiceId, $InvoiceDate, $Qty,
                    $SalesBalance, $CurrencyCode, $ExchRate)
    {
        $this->db->select('Tax, Round');
        $this->db->where('Id', $OrderAccount);
        $query  = $this->db->get(self::$vendor);
        $rowa = $query->row();
        
        if($rowa){
            $this->db->select('Id');
            $this->db->where('InvoiceId', $InvoiceId);
            $queryb  = $this->db->get(self::$table);
            $rowb = $queryb->row();
            if($rowb){
                return FALSE;
            }
            else{
                if($rowa->Tax == 'PPN'){
                    if($rowa->Round == 'UP'){
                        $SalesBalance = ceil($SalesBalance*1.1);
                    }
                    elseif ($rowa->Round == 'NORMAL') {
                        $SalesBalance = round($SalesBalance*1.1, 0);
                    }
                    else{
                        $SalesBalance = floor($SalesBalance*1.1);
                    }
                }
                $querya = $this->db->insert(self::$table,array(
                    'OrderAccount'  => $OrderAccount,
                    'InvoiceId'     => $InvoiceId,
                    'InvoiceDate'   => $InvoiceDate,
                    'Qty'           => $Qty,
                    'SalesBalance'  => $SalesBalance,
                    'CurrencyCode'  => $CurrencyCode,
                    'ExchRate'      => $ExchRate
                ));
                if($querya){
                    return TRUE;
                }
                else{
                    return FALSE;
                }
            }            
        }
        else{
            return FALSE;
        }
    }
        
    function delete($InvoiceId)
    {
        $query = $this->db->delete(self::$table, array('InvoiceId' => $InvoiceId));
        if($query)
        {
            return json_encode(array('success'=>true));
        }
        else
        {
            return json_encode(array('success'=>false,'error'=>'Gagal'));
        }
    }
    
    function updateRate()
    {
        $bulan  = $this->input->post('bulan1',true);
        $tahun  = $this->input->post('tahun1',true);
        $rate   = $this->input->post('rate1',true);
        
        $this->db->where('CurrencyCode', 'USD')
                 ->where('AcceptDate', '0000-00-00')
                 ->where('MONTH(InvoiceDate)', $bulan)
                 ->where('YEAR(InvoiceDate)', $tahun);
        return $this->db->update(self::$table,array(
            'ExchRate'  => $rate
        ));
 
    }
}

/* End of file m_invoice.php */
/* Location: ./application/models/master/m_invoice.php */