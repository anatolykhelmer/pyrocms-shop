<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author Anatoly Khelmer
 */
class Shop_Items_m extends MY_Model {

    public function  __construct() {
        parent::__construct();
    }

    public function get_all($base_where = array())
    {
        $where = '';
        if (!empty($base_where)) {
            $where = 'where ';
            $i = 0;
            foreach($base_where as $name => $value) {
                $where .= ($i) ? ' and ' : '';
                if ($name == 'status') {
                    if ($value == 'draft') $value = 0;
                    else $value = 1;
                }
                $where .= "{$name} = {$this->db->escape($value)}";
                $i++;
            }
        }
        $query = "select * from `shop_items` $where;";
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
        $query = "select * from `shop_items` where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        $row = $sql->row();
        return $row;
    }

    public function search($word)
    {
        $word = '%'.$word.'%';
        $query = "select * from `shop_items` where name like {$this->db->escape($word)};";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function create($params)
    {
        if ($params['status'] == 'draft') $status = 0;
        else $status = 1;
        $name = $this->db->escape($params['title']);
        $price = $this->db->escape($params['price']);
        $category = $this->db->escape($params['category']);
        $gallery = $this->db->escape($params['gallery']);
        $status = $this->db->escape($status);
        $description = $this->db->escape($params['description']);
        

        $query = "insert into `shop_items` (name, price, category, gallery, status, description)
                    values ($name, $price, $category, $gallery, $status, $description);";
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
        if ($params['status'] == 'draft') $status = 0;
        else $status = 1;
        $name = $this->db->escape($params['title']);
        $price = $this->db->escape($params['price']);
        $category = $this->db->escape($params['category']);
        $gallery = $this->db->escape($params['gallery']);
        $status = $this->db->escape($status);
        $description = $this->db->escape($params['description']);

        $query = "update `shop_items` set
                                        name = $name,
                                        price = $price,
                                        category = $category,
                                        gallery  = $gallery,
                                        status = $status,
                                        description = $description where
                    id = {$this->db->escape($id)};";
        $sql = $this->db->query($query);
        return $sql;
    }

}