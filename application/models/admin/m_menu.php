<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_menu extends CI_Model
{    
    static $table = 'menu';
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('database');
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
    
    public function getParent()
    {
        $this->db->select('id, name');
        $this->db->order_by('name', 'asc');
        $query  = $this->db->get(self::$table);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);         
    }
    
    public function getUser()
    {
        $this->db->select('id, level, name');
        $this->db->order_by('name', 'asc');
        $query  = $this->db->get('user');
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            $node = array();
            $node['id'] = '+'.$row->id;
            //$node['id'] = $row->level;
            $node['text'] = $row->name;
            $node['iconCls'] = 'icon-user';
            array_push($data, $node); 
        }       
        return json_encode($data);         
    }
    
    public function enumField($table, $field)
    {
        $enums = field_enums($table, $field);
        return json_encode($enums);
    }
    
    public function create()
    {
        return $this->db->insert(self::$table,array(
            'name'=>$this->input->post('name',true),
            'parentId'=>$this->input->post('parentId',true),
            'uri'=>$this->input->post('uri',true),
            'allowed'=>$this->input->post('allowed',true),
            'iconCls'=>$this->input->post('iconCls',true),
            'type'=>$this->input->post('type',true)
        ));
    }
    
    public function update($id)
    {
        $this->db->where('id', $id);
        return $this->db->update(self::$table,array(
            'name'=>$this->input->post('name',true),
            'parentId'=>$this->input->post('parentId',true),
            'uri'=>$this->input->post('uri',true),
            'allowed'=>$this->input->post('allowed',true),
            'iconCls'=>$this->input->post('iconCls',true),
            'type'=>$this->input->post('type',true)
        ));
    }
   
    public function delete($id)
    {
        return $this->db->delete(self::$table, array('id' => $id)); 
    }
    
}

/* End of file m_menu.php */
/* Location: ./application/models/admin/m_menu.php */