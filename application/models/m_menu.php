<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_menu extends CI_Model
{
    static $table = 'menu';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function ambil_menu($id_user)
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 'id';        

        $this->db->where('parentId',$id);
        $this->db->like('allowed','+'.$id_user.'+');
        
        $rs = $this->db->get(self::$table);
        $result  = array();
        foreach ( $rs->result() as $row )
        {
            $node = array();
            $node['id'] = $row->id;
            $node['text'] = $row->name;
            $node['state'] = $this->has_child($row->id) ? 'closed' : 'open';
            $node['uri'] = $this->link_menu($row->uri);
            $node['allowed'] = $row->allowed;
            $node['iconCls'] = $row->iconCls;
            $node['type'] = $row->type;
            array_push($result, $node);
        }
        return json_encode($result);
    }

    function has_child($id)
    {
        $this->db->where('parentId',$id);
        $this->db->from(self::$table);
        $rs = $this->db->count_all_results();
        return$rs > 0 ? true : false;
    }
    
    function link_menu($link)
    {
        if ($link != '')
        {
            return site_url($link);
        } else
        {
            return 'kosong';
        }
    }
    
}

/* End of file m_menu.php */
/* Location: ./application/models/m_menu.php */