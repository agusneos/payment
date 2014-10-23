<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_dashboard extends CI_Model
{
    static $table = 'VendInvoiceJour';
    static $vendor = 'Vendor';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'InvoiceDate';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        
        $filterRules = isset($_POST['filterRules']) ? ($_POST['filterRules']) : '';
	$cond = '1=1';
	if (!empty($filterRules)){
            $filterRules = json_decode($filterRules);
            //print_r ($filterRules);
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
        
        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, DATE_ADD(InvoiceDate,INTERVAL PayTerm DAY) AS JatuhTempo, Qty, PaymentSisa, CurrencyCode, 
                            IF('.self::$table.'.Tax = "PPN", PaymentSisa * 0.1, "") AS Ppn,
                            IF('.self::$table.'.Tax = "PPN", PaymentSisa * 1.1, PaymentSisa) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE)
                 ->where('CheckDate <> "0000-00-00 00:00:00"')
                 ->where('PaymentSisa <> 0')
                 ->where('TIMESTAMPDIFF(DAY,now(),DATE_ADD(InvoiceDate,INTERVAL PayTerm DAY)) <= 0 ');
        $this->db->join(self::$vendor, self::$table.'.OrderAccount='.self::$vendor.'.Id', 'left');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();

        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, DATE_ADD(InvoiceDate,INTERVAL PayTerm DAY) AS JatuhTempo, Qty, PaymentSisa, CurrencyCode,
                            IF('.self::$table.'.Tax = "PPN", PaymentSisa * 0.1, "") AS Ppn,
                            IF('.self::$table.'.Tax = "PPN", PaymentSisa * 1.1, PaymentSisa) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE)
                ->where('CheckDate <> "0000-00-00 00:00:00"')
                 ->where('PaymentSisa <> 0')
                 ->where('TIMESTAMPDIFF(DAY,now(),DATE_ADD(InvoiceDate,INTERVAL PayTerm DAY)) <= 0');
        $this->db->join(self::$vendor, self::$table.'.OrderAccount='.self::$vendor.'.Id', 'left');
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
    
     public function willindex()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'InvoiceDate';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        
        $filterRules = isset($_POST['filterRules']) ? ($_POST['filterRules']) : '';
	$cond = '1=1';
	if (!empty($filterRules)){
            $filterRules = json_decode($filterRules);
            //print_r ($filterRules);
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
        
        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, DATE_ADD(InvoiceDate,INTERVAL PayTerm DAY) AS JatuhTempo, Qty, PaymentSisa, CurrencyCode, 
                            IF('.self::$table.'.Tax = "PPN", PaymentSisa * 0.1, "") AS Ppn,
                            IF('.self::$table.'.Tax = "PPN", PaymentSisa * 1.1, PaymentSisa) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE)
                ->where('CheckDate <> "0000-00-00 00:00:00"')
                 ->where('PaymentSisa <> 0')
                 ->where('TIMESTAMPDIFF(DAY,now(),DATE_ADD(InvoiceDate,INTERVAL PayTerm DAY)) < 7');
        $this->db->join(self::$vendor, self::$table.'.OrderAccount='.self::$vendor.'.Id', 'left');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();

        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, DATE_ADD(InvoiceDate,INTERVAL PayTerm DAY) AS JatuhTempo, Qty, PaymentSisa, CurrencyCode,
                            IF('.self::$table.'.Tax = "PPN", PaymentSisa * 0.1, "") AS Ppn,
                            IF('.self::$table.'.Tax = "PPN", PaymentSisa * 1.1, PaymentSisa) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE)
                ->where('CheckDate <> "0000-00-00 00:00:00"')
                 ->where('PaymentSisa <> 0')
                 ->where('TIMESTAMPDIFF(DAY,now(),DATE_ADD(InvoiceDate,INTERVAL PayTerm DAY)) < 7');
        $this->db->join(self::$vendor, self::$table.'.OrderAccount='.self::$vendor.'.Id', 'left');
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
    
}

/* End of file m_dashboard.php */
/* Location: ./application/models/m_dashboard.php */