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
        $this->db->join(self::$vendor, self::$table.'.OrderAccount='.self::$vendor.'.Id', 'left');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();

        $this->db->select('OrderAccount, InvoiceId, InvoiceDate, Qty, SalesBalance, CurrencyCode,
                            IF(Tax = "PPN", SalesBalance * 0.1, "") AS Ppn,
                            IF(Tax = "PPN", SalesBalance * 1.1, SalesBalance) AS InvoiceAmount', FALSE);
        $this->db->where($cond, NULL, FALSE);
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
    
    function update($InvoiceId)
    {    
        $this->db->where('InvoiceId', $InvoiceId);
        return $this->db->update(self::$table,array(            
            'CheckDate' =>$this->input->post('CheckDate',true)
        ));
    }
        
    function delete($InvoiceId)
    {
        return $this->db->delete(self::$table, array('InvoiceId' => $InvoiceId)); 
    }
    
    function upload($OrderAccount, $InvoiceId, $InvoiceDate, $Qty,
                    $SalesBalance, $CurrencyCode, $ExchRate)
    {     
    /*    return $this->db->simple_query('INSERT INTO vendinvoicejour 
            VALUES ("'.$OrderAccount.'","'.$InvoiceId.'","'.$InvoiceDate.'",'.$Qty.','
                .$SalesBalance.','.$InvoiceAmount.',"'.$CurrencyCode.'",'.$ExchRate.','
                .$InvoiceRoundOff.',"'.$TaxGroup.'",'.$SumTax.','.$InvoiceAmountMST.',
                "0000-00-00 00:00:00", "0000-00-00", "", "0000-00-00 00:00:00")');
     * return $this->db->simple_query('INSERT INTO vendinvoicejour 
            VALUES ("'.$OrderAccount.'","'.$InvoiceId.'","'.$InvoiceDate.'",'.$Qty.','
                .$SalesBalance.','.$InvoiceAmount.',"'.$CurrencyCode.'",'.$ExchRate.','
                .$InvoiceRoundOff.',"'.$TaxGroup.'",'.$SumTax.','.$InvoiceAmountMST.',
                "0000-00-00 00:00:00", '.$InvoiceAmount.')');
     * 
     */
        
        return $this->db->simple_query('INSERT INTO vendinvoicejour 
            VALUES ("'.$OrderAccount.'","'.$InvoiceId.'","'.$InvoiceDate.'",'.$Qty.','
                .$SalesBalance.',"'.$CurrencyCode.'",'.$ExchRate.',
                "0000-00-00 00:00:00", '.$SalesBalance.')');
    }
    
}

/* End of file m_invoice.php */
/* Location: ./application/models/master/m_invoice.php */