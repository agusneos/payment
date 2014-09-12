<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_invoice extends CI_Model
{    
    static $table = 'VendInvoiceJour';
     
    public function __construct() {
        parent::__construct();
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
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
        
        $this->db->select('SUM(Qty) AS Qty, SUM(0) AS SalesBalance, 
                        SUM(0) AS InvoiceAmount, SUM(0) AS ExchRate, 
                        SUM(0) AS InvoiceRoundOff, SUM(SumTax) AS SumTax, 
                        SUM(InvoiceAmountMST) AS InvoiceAmountMST');
        $this->db->where($cond, NULL, FALSE);         
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
    
    function update($InvoiceId)
    {    
        $this->db->where('InvoiceId', $InvoiceId);
        return $this->db->update(self::$table,array(            
            'CheckDate' =>$this->input->post('CheckDate',true)
        ));
    }
    
    function update2($id)
    {    
        $this->db->where('InvoiceId', $InvoiceId);
        return $this->db->update(self::$table,array(            
            'CheckDate' =>$this->input->post('checkdate',true)
        ));
    }
    
    function delete($InvoiceId)
    {
        return $this->db->delete(self::$table, array('InvoiceId' => $InvoiceId)); 
    }
    
    function upload($nama, $kelas, $matkul)
    {
        return $this->db->insert('test', array(
            'nama'  => $nama,
            'kelas' => $kelas,
            'matkul'=> $matkul
        ));
    }
    
}

/* End of file m_invoice.php */
/* Location: ./application/models/master/m_invoice.php */