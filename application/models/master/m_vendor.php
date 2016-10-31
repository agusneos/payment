<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_vendor extends CI_Model
{    
    static $table = 'vendor';
     
    public function __construct() {
        parent::__construct();
        $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }

    function index()
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
        
        $this->db->where($cond, NULL, FALSE);
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();
        
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
	$result["total"] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);          
    }   
        
    function create()
    {
        return $this->db->insert(self::$table,array(
            'Id'=>$this->input->post('Id',true),
            'Name'=>$this->input->post('Name',true),
            'PayTerm'=>$this->input->post('PayTerm',true),
            'VendGroup'=>$this->input->post('VendGroup',true),
            'Tax'=>$this->input->post('Tax',true),
            'Round'=>$this->input->post('Round',true)
        ));
    }
    
    function update($Id)
    {
        $this->db->where('Id', $Id);
        return $this->db->update(self::$table,array(
            'Name'=>$this->input->post('Name',true),
            'PayTerm'=>$this->input->post('PayTerm',true),
            'VendGroup'=>$this->input->post('VendGroup',true),
            'Tax'=>$this->input->post('Tax',true),
            'Round'=>$this->input->post('Round',true)
        ));
    }
    
    function delete($Id)
    {
        return $this->db->delete(self::$table, array('Id' => $Id)); 
    }
    
    function enumField($field)
    {
        $enums = field_enums(self::$table, $field);
        return json_encode($enums);
    }
    
}

/* End of file m_vendor.php */
/* Location: ./application/models/master/m_vendor.php */