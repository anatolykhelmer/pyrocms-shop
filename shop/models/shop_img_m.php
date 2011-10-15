<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author ekoisa@gmail.com
 */
class Shop_img_m extends MY_Model {

    public function  __construct() {
        parent::__construct();
    }

    public function search($word)
    {
        $word = '%'.$word.'%';
        $query = "select * from `".$this->db->dbprefix('shop_items')."` where name like {$this->db->escape($word)};";
        $sql = $this->db->query($query);
        return $sql;
    }

    
    public function del($id)
    {
        $query = "delete from `".$this->db->dbprefix('shop_images')."` where id_shop_images={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        return $sql;
    }


    /**
     * Read Image Max ID
     */
    public function read_maxid()
    {

        // Let's start from items
        $query = "select max(id_shop_images) as maxid from `".$this->db->dbprefix('shop_images')."`;";
        $sql = $this->db->query($query);
        if ($sql == false) return false;
		return $sql->row();
    }

    /**
     * Read Shop Image by Item
     */
    public function read_img($id)
    {
      if ($id <= 0) return false;

        // Let's start from items
        $query = "select * from `".$this->db->dbprefix('shop_images')."`  where publish = 1 and id_item={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        if ($sql == false) return false;
		return $sql->result();
    }


    /**
     * Read Image By Id
     */
    public function read_img_byid($id)
    {
      if ($id <= 0) return false;
        // Let's start from items
        $query = "select * from `".$this->db->dbprefix('shop_images')."`  where id_shop_images=? ;";
        $sql = $this->db->query($query, array($id));
        if ($sql == false) return false;
		return $sql->row();
    }

    /**
     * Set Shop Image Undefault for item id x
     */
    public function setundefault($item)
    {
        // Let's start from items
        $query = "Update  `".$this->db->dbprefix('shop_images')."`  set `is_default` = 0 where id_item = ? ";
        $sql = $this->db->query($query, array($item));
        return $this->db->affected_rows();
    }

    /**
     * Read Shop Image
     */
    public function setdefault($id)
    {
      if ($id <= 0) return false;
        $query = "Update  `".$this->db->dbprefix('shop_images')."`  set `is_default` = 1 where id_shop_images = ? ";
        $sql = $this->db->query($query, array($id));
		return $this->db->affected_rows();
    }

    /**
     * Edit Shop Image
     */
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
        $manufacturer = $this->db->escape($params['manufacturer']);

        // Let's start from items
        $query = "update `".$this->db->dbprefix('shop_items')."`  set name = $name,
                                           price = $price,
                                           category = $category,
                                           gallery = $gallery,
                                           status = $status,
                                           manufacturer = $manufacturer,
                                           description = $description where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        if ($sql == false) return false;

        // Delete all options and write new ones
        $options = $this->get_options($id);
        foreach ($options->result() as $option) {
            $query = "delete from `".$this->db->dbprefix('shop_item_options')."` where id = {$this->db->escape($option->id)};";
            $sql = $this->db->query($query);
        }

        $item_id = $id;

        return TRUE;
    }
    
    public function add($params)
    {
      if ($params['status'] == 'draft') $status = 0;
        else $status = 1;
        $id_shop_images = $this->db->escape($params['id_shop_images']);
        $id_item = $this->db->escape($params['id_item']);
        $image_name = $this->db->escape($params['image_name']);
        $image_originalname = $this->db->escape($params['image_originalname']);
        $status = $this->db->escape($status);

        // Let's start from items
        $query = "insert into `".$this->db->dbprefix('shop_images')."`  set
                    id_shop_images = $id_shop_images,
                    id_item = $id_item,
                    image_name = $image_name,
                    image_originalname = $image_originalname,
                    publish = $status ;";
        $sql = $this->db->query($query);
        if ($sql == false) return false;

        return TRUE;
    }

}
