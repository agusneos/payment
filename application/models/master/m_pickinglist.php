<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_pickinglist extends CI_Model
{    
    static $table = 'wmsordertrans';
     
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
        
    public function create()
    {
        return $this->db->insert(self::$table,array(
            'id'=>$this->input->post('id',true),
            'activation_date'=>$this->input->post('activation_date',true),
            'picking_route'=>$this->input->post('picking_route',true),
            'customer_requisition'=>$this->input->post('customer_requisition',true),
            'customer_account'=>$this->input->post('customer_account',true),
            'name'=>$this->input->post('name',true),
            'delivery_date'=>$this->input->post('delivery_date',true),
            'item_number'=>$this->input->post('item_number',true),
            'item_name'=>$this->input->post('item_name',true),
            'external'=>$this->input->post('external',true),
            'ca_number'=>$this->input->post('ca_number',true),
            'quantity'=>$this->input->post('quantity',true),
            'actual_qty'=>$this->input->post('actual_qty',true),
            'box'=>$this->input->post('box',true),
            'urgent'=>$this->input->post('urgent',true),
            'no_stock'=>$this->input->post('no_stock',true),
            'close'=>$this->input->post('close',true),
            'upload_time'=>$this->input->post('upload_time',true)
            
        ));
    }
    
    public function update($id)
    {
        $this->db->where('id', $id);
        return $this->db->update(self::$table,array(
            'id'=>$this->input->post('id',true),
            'activation_date'=>$this->input->post('activation_date',true),
            'picking_route'=>$this->input->post('picking_route',true),
            'customer_requisition'=>$this->input->post('customer_requisition',true),
            'customer_account'=>$this->input->post('customer_account',true),
            'name'=>$this->input->post('name',true),
            'delivery_date'=>$this->input->post('delivery_date',true),
            'item_number'=>$this->input->post('item_number',true),
            'item_name'=>$this->input->post('item_name',true),
            'external'=>$this->input->post('external',true),
            'ca_number'=>$this->input->post('ca_number',true),
            'quantity'=>$this->input->post('quantity',true),
            'actual_qty'=>$this->input->post('actual_qty',true),
            'box'=>$this->input->post('box',true),
            'urgent'=>$this->input->post('urgent',true),
            'no_stock'=>$this->input->post('no_stock',true),
            'close'=>$this->input->post('close',true),
            'upload_time'=>$this->input->post('upload_time',true)
        ));
    }
    
    public function update2($id)
    {    
        $this->db->where('id', $id);
        return $this->db->update(self::$table,array(            
            'actual_qty'=>$this->input->post('actual_qty',true),
            'box'       =>$this->input->post('box',true),
            'urgent'    =>$this->input->post('urgent',true),
            'no_stock'  =>$this->input->post('no_stock',true),
            'picked'  =>$this->input->post('picked',true),
            'close'     =>$this->input->post('close',true)
        ));
    }
    
    public function delete($id)
    {
        return $this->db->delete(self::$table, array('id' => $id)); 
    }
    
}

/* End of file m_pickinglist.php */
/* Location: ./application/models/master/m_pickinglist.php */