<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('field_enums'))
{
    function field_enums($table = '', $field = '')
    {     
        $enums = array();
        if ($table == '' || $field == '') return $enums;
        $CI =& get_instance();
        preg_match_all("/'(.*?)'/", $CI->db->query("SHOW COLUMNS FROM {$table} LIKE '{$field}'")->row()->Type, $matches);
        foreach ($matches[1] as $key => $value) {
            //$enums[$value] = $value; 
            $enums[] = array (
                'data' => $value
            );
        }
        return $enums;         
    }  
}

/* End of file database_helper.php */
/* Location: ./application/helpers/database_helper.php */