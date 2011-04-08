<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author Anatoly Khelmer
 */
class Shop_Items_m extends MY_Model {

    public function  __construct() {
        parent::__construct();
    }

    public function get_all()
    {
        $query = "select * from `shop_items`;";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function get_all_in_cat($cat_id)
    {
        $query = "select * from `shop_items` where category={$this->db->escape($cat_id)};";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function get($id)
    {
        $query = "select * from `shop_items` where $id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        $row = $sql->row();
        return $row;
    }

    public function create($params)
    {
        if ($params['status'] == 'draft') $active = 0;
        else $active = 1;
        $name = $this->db->escape($params['title']);
        $price = $this->db->escape($params['price']);
        $category = $this->db->escape($params['category']);
        $gallery = $this->db->escape($params['gallery']);
        $active = $this->db->escape($active);
        $description = $this->db->escape($params['description']);
        

        $query = "insert into `shop_items` (name, price, category, gallery, active, description)
                    values ($name, $price, $category, $gallery, $active, $description);";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function delete($id)
    {
        $query = "delete from `shop_items` where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function edit($id, $params)
    {
        if ($params['status'] == 'draft') $active = 0;
        else $active = 1;
        $name = $this->db->escape($params['title']);
        $price = $this->db->escape($params['price']);
        $category = $this->db->escape($params['category']);
        $gallery = $this->db->escape($params['gallery']);
        $active = $this->db->escape($active);
        $description = $this->db->escape($params['description']);

        $query = "update `shop_items` set
                                        name = $name,
                                        price = $price,
                                        category = $category,
                                        gallery  = $gallery,
                                        active = $active,
                                        description = $description where
                    id = {$this->db->escape($id)};";
        $sql = $this->db->query($query);
        return $sql;
    }

}