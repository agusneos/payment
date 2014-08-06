<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_persiapan extends CI_Model
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
        
        $this->db->where($cond, NULL, FALSE);
        //$this->db->having($cond, NULL, FALSE);
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();
       
        $this->db->select('wmsordertrans.*, IFNULL(SUM(lot.qty),0) AS qty', FALSE);
        $this->db->join(self::$lot, 'wmsordertrans.id=lot.wmsordertrans_id', 'left');
        $this->db->group_by('wmsordertrans.id');
        //$this->db->where($cond, NULL, FALSE);
        $this->db->having($cond, NULL, FALSE);
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
            'box'       =>$this->input->post('box',true),
            'urgent'    =>$this->input->post('urgent',true),
            'no_stock'  =>$this->input->post('no_stock',true),
            'picked'    =>$this->input->post('picked',true),
            'close'     =>$this->input->post('close',true)
        ));
    }
    
    public function delete($id)
    {
        return $this->db->delete(self::$table, array('id' => $id)); 
    }
    
    function lot($id)
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'wmsordertrans_id';
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
                 ->order_by('time', 'asc');
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$lot);
                
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
        
        $this->db->where($cond, NULL, FALSE)
                 ->where('wmsordertrans_id', $id);
        $this->db->select_sum('qty');
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
    
    function lot_create()
    {
        return $this->db->insert(self::$lot,array(
            'wmsordertrans_id'=>$this->input->post('wmsordertrans_id',true),
            'lot'=>$this->input->post('lot',true),
            'qty'=>$this->input->post('qty',true),            
        ));
    }
    
    function lot_delete($id, $time)
    {
        return $this->db->delete(self::$lot, array('wmsordertrans_id' => $id, 'time' => $time)); 
    }
    
}

/* End of file m_persiapan.php */
/* Location: ./application/models/transaksi/m_persiapan.php */