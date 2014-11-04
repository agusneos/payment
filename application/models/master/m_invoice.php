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
        
        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, Qty, SalesBalance, CurrencyCode, 
                            IF(Tax = "PPN", SalesBalance * 0.1, "") AS Ppn,
                            IF(Tax = "PPN", SalesBalance * 1.1, SalesBalance) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE);
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();

        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, Qty, SalesBalance, CurrencyCode,
                            IF(Tax = "PPN", SalesBalance * 0.1, "") AS Ppn,
                            IF(Tax = "PPN", SalesBalance * 1.1, SalesBalance) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE);
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
        date_default_timezone_set('Asia/Jakarta');
        $InvoiceDate = date("Y-m-d",($InvoiceDate - 25569)*86400);
        
        return $this->db->simple_query('INSERT INTO vendinvoicejour (OrderAccount, InvoiceId, InvoiceDate,
                Qty, SalesBalance, Tax, CurrencyCode, ExchRate, CheckDate, PaymentSisa) 
                SELECT "'.$OrderAccount.'","'.$InvoiceId.'","'.$InvoiceDate.'",'.$Qty.','
                    .$SalesBalance.',Tax,"'.$CurrencyCode.'",'.$ExchRate.',
                    "0000-00-00 00:00:00", '.$SalesBalance.' 
                FROM Vendor WHERE Id = "'.$OrderAccount.'"');

    }
        
    function updateRate()
    {
        $bulan  = $this->input->post('bulan1',true);
        $tahun  = $this->input->post('tahun1',true);
        $rate   = $this->input->post('rate1',true);
        
        $this->db->where('CurrencyCode', 'USD')
                 ->where('MONTH(InvoiceDate)', $bulan)
                 ->where('YEAR(InvoiceDate)', $tahun);
        return $this->db->update(self::$table,array(
            'ExchRate'  => $rate
        ));
 
    }
}

/* End of file m_invoice.php */
/* Location: ./application/models/master/m_invoice.php */