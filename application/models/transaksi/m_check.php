<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_check extends CI_Model
{    
    static $table = 'vendinvoicetrans';
     
    public function __construct() {
        parent::__construct();
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 100;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
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
        
        $this->db->where($cond, NULL, FALSE)
                    ->where('CheckDate', '0000-00-00 00:00:00');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();
        
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
        
        $this->db->select('SUM(0) AS Rate, SUM(Qty) AS Qty, SUM(0) AS Price, 
                            SUM(Amount) AS Amount, SUM(AmountMST) AS AmountMST');
        $this->db->where($cond, NULL, FALSE)
                    ->where('CheckDate', '0000-00-00 00:00:00');         
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
    
    function update($id)
    {    
        $this->db->where('Id', $id);
        return $this->db->update(self::$table,array(            
            'CheckDate' =>$this->input->post('checkdate',true)
        ));
    }        
    
}

/* End of file m_check.php */
/* Location: ./application/models/transaksi/m_check.php */