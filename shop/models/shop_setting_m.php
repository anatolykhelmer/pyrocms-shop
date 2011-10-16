<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author Eko Muhammad Isa
 */
class Shop_setting_m extends MY_Model {

    public function  __construct() {
        parent::__construct();
    }

    public function get_setting($name = '')
    {
        $where = '';
        if (!empty($name)) {
            $where = "where ";
            $where .= "setting_name = '".$name."'";
        }else{
            return;
        }
        $query = "select setting_value from `".$this->db->dbprefix('shop_setting')."` $where;";
        $sql = $this->db->query($query);
        //$this->db->affected_rows();
        if ($sql->num_rows() == 0)
        {
            $query = "insert into `".$this->db->dbprefix('shop_setting')."`(setting_name, setting_value) values (?, ?) ";
            $sql = $this->db->query($query, array($name, ''));
            $retval = '';
        }else{
            $hsl = $sql->row();
            $retval = $hsl->setting_value;
        }
        
        return $retval;
    }
    
    public function set_setting($name = '', $value='')
    {
        if (empty($name)) {
            return;
        }
        $query = "update `".$this->db->dbprefix('shop_setting')."` set setting_value =? where setting_name =? ;";
        $sql = $this->db->query($query, array($value, $name));
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

}
