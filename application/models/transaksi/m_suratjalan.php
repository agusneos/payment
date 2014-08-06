<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_suratjalan extends CI_Model
{    
    static $table   = 'wmsordertrans';
    static $lot     = 'lot';
     
    public function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
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
        
        $this->db->where($cond, NULL, FALSE)
                 ->where('picked', 'picked')
                 ->where('close', ' ');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();
       
        $this->db->select('wmsordertrans.*, IFNULL(SUM(lot.qty),0) AS qty, COUNT(DISTINCT box) AS box', FALSE);
        $this->db->join(self::$lot, 'wmsordertrans.id=lot.wmsordertrans_id', 'left');
        $this->db->group_by('wmsordertrans.id');
        $this->db->having($cond, NULL, FALSE)
                 ->having('picked', 'picked')
                 ->having('close', ' ');
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result["total"] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);          
    }   
    
    public function update($id)
    {    
        $this->db->where('id', $id);
        return $this->db->update(self::$table,array(
            'close'     =>$this->input->post('close',true)
        ));
    }
        
    function lotsj($id)
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
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
        
        $this->db->where($cond, NULL, FALSE)
                 ->where('wmsordertrans_id', $id);
        $this->db->from(self::$lot);
        $total  = $this->db->count_all_results();
        
        $this->db->where($cond, NULL, FALSE)
                 ->where('wmsordertrans_id', $id);
        $this->db->order_by($sort, $order)
                 ->order_by('id', 'asc');
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$lot);
                
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
        
        $this->db->select('SUM(qty) AS qty, COUNT(DISTINCT box) AS box');
        $this->db->where($cond, NULL, FALSE)
                 ->where('wmsordertrans_id', $id);         
        $query2  = $this->db->get(self::$lot);
        
        $data2 = array();
        foreach ( $query2->result() as $row2 )
        {
            array_push($data2, $row2); 
        }        
        
        $result = array();
	$result['total'] = $total;
	$result['rows'] = $data;
        $result['footer'] = $data2;
        
        return json_encode($result);
    }    
    
}

/* End of file m_suratjalan.php */
/* Location: ./application/models/transaksi/m_suratjalan.php */