<?php defined('BASEPATH') or exit('No direct script access allowed');

class Shop_cat_m extends MY_Model {

    public function  __construct() {
        parent::__construct();
    }

    /**
     *
     * @return all shop categories
     */
    public function get_all()
    {
        $query = "select * from ".$this->db->dbprefix('shop_categories').";";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function get($id)
    {
        $query = "select * from `".$this->db->dbprefix('shop_categories')."` where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        $row = $sql->row();
        return $row;
    }

    /**
     *
     * @param int $id
     * @return string - category name
     */
    public function get_name( $id )
    {
        $query = "select name from ".$this->db->dbprefix('shop_categories')." where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        if ($sql->num_rows() == 0) return FALSE;
        $row = $sql->row();
        $name = $row->name;
        return $name;
    }

    /**
     *
     * @param string $name - the name of category to add
     * @return bool
     */
    public function create( $name )
    {
        $query = "insert into ".$this->db->dbprefix('shop_categories')." (name) values ({$this->db->escape($name)});";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function edit( $name, $id )
    {
        $query = "update `".$this->db->dbprefix('shop_categories')."` set name={$this->db->escape($name)} where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function delete( $id )
    {
        $query = "delete from `".$this->db->dbprefix('shop_categories')."` where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function check_name( $name )
    {
        $query = "select * from `".$this->db->dbprefix('shop_categories')."` where name={$this->db->escape($name)};";
        $sql = $this->db->query($query);
        if ($sql->num_rows()) return TRUE;
        else return false;
    }
}
/* End of file shop_cat_m.php */