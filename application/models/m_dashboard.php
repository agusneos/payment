<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_dashboard extends CI_Model
{
    static $table = 'pkwt';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'pkwt_id';
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
        
        $this->db->join('emply', 'pkwt.pkwt_nik = emply.emply_nik', 'left')
                 ->join('dept', 'pkwt.pkwt_dept = dept.dept_id', 'left')
                 ->join('post', 'pkwt.pkwt_post = post.post_id', 'left');
        $this->db->where($cond, NULL, FALSE)
                ->where('TIMESTAMPDIFF(month,now(),pkwt_end) < 2')
                ->where('pkwt_process', 'N');
        $this->db->from(self::$table);
        $total  = $this->db->count_all_results();
       
        $this->db->select('pkwt.*, emply.emply_name, dept.dept_name, post.post_name');
        $this->db->join('emply', 'pkwt.pkwt_nik = emply.emply_nik', 'left')
                 ->join('dept', 'pkwt.pkwt_dept = dept.dept_id', 'left')
                 ->join('post', 'pkwt.pkwt_post = post.post_id', 'left');
        $this->db->where($cond, NULL, FALSE)
                ->where('TIMESTAMPDIFF(month,now(),pkwt_end) < 2')
                ->where('pkwt_process', 'N');
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table);
       
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total'] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);          
    }
    
}

/* End of file m_dashboard.php */
/* Location: ./application/models/m_dashboard.php */