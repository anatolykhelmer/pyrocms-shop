<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author Anatoly Khelmer
    modified by Eko Muhammad Isa
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
                // Need to use "like" if it is a name
                if ($name == 'name') {
                    $value = '%'. $value. '%';
                    $where .= "{$name} like {$this->db->escape($value)}";
                }
                else {
                    $where .= "{$name} = {$this->db->escape($value)}";
                    $i++;
                }
            }
        }
        $query = "select * from `".$this->db->dbprefix('shop_items')."` $where;";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function get_all_in_cat($cat_id)
    {
        $query = "select a.`id`, a.`name`, a.`manufacturer`, a.`category`, a.`description`, a.`price`, a.`options`, a.`status`, a.`postdate`, b.`image_name` from `".$this->db->dbprefix('shop_items')."`  as a 
        left outer join `".$this->db->dbprefix('shop_images')."` as b on a.`id` = b.`id_item` and b.`is_default` = 1 
        where a.category={$this->db->escape($cat_id)};";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function get_lastest($rowreturn = 5)
    {
        $query = "select a.`id`, a.`name`, a.`manufacturer`, a.`category`, a.`description`, a.`price`, a.`options`, a.`status`, a.`postdate`, b.`image_name`, c.`name` as cat_name from `".$this->db->dbprefix('shop_items')."` as a 
        left outer join `".$this->db->dbprefix('shop_images')."` as b on a.`id` = b.`id_item` and b.`is_default` = 1 
        left outer join `".$this->db->dbprefix('shop_categories')."` as c on a.`category` = c.`id` 
        where a.`status` = 1 order by postdate desc limit 0, ".$rowreturn." ";
        $sql = $this->db->query($query);
        if($sql->num_rows() > 0){
            return $sql->result();
        }else{
            return array();
        }
        
    }

    public function get($id)
    {
        $query = "select a.`id`, a.`name`, a.`manufacturer`, a.`category`, a.`description`, a.`price`, a.`options`, a.`status`, a.`postdate`, c.`name` as cat_name from `".$this->db->dbprefix('shop_items')."` as a 
        left outer join `".$this->db->dbprefix('shop_categories')."` as c on a.`category` = c.`id` 
        where a.id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        $row = $sql->row();
        return $row;
    }


    public function get_options($id=0)
    {
        $query = "select * from `".$this->db->dbprefix('shop_item_options')."` where item_id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        return $sql;
    }

    /**
     * Get all values for option with id specified
     *
     * @param int $option_id
     * @return sql query result
     */
    public function get_option_values($option_id=0)
    {
        $query = "select * from `".$this->db->dbprefix('shop_item_option_values')."` where option_id={$this->db->escape($option_id)};";
        $sql = $this->db->query($query);
        return $sql;
    }


    public function get_option_value($value_id=0)
    {
        $query = "select * from `".$this->db->dbprefix('shop_item_option_values')."` where id={$this->db->escape($value_id)};";
        $sql = $this->db->query($query);
        $row = $sql->row();
        return $row;
    }


    public function search($word)
    {
        $word = '%'.$word.'%';
        $query = "select * from `".$this->db->dbprefix('shop_items')."` where name like {$this->db->escape($word)};";
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
        $manufacturer = $this->db->escape($params['manufacturer']);
        
        // Let's start from items
        $query = "insert into `".$this->db->dbprefix('shop_items')."` (name, price, category, gallery, status, description, manufacturer)
                    values ($name, $price, $category, $gallery, $status, $description, $manufacturer);";
        $sql = $this->db->query($query);
        if ($sql == false) return false;

        $item_id = $this->db->insert_id();

        // Now options if we have
        // Option name first of all
        if (isset($params['option_name']) && count($params['option_name']) != 0) {

            // Here we need loop over all options we have
            foreach ($params['option_name'] as $id=>$option_name) {
                    $query = "insert into `".$this->db->dbprefix('shop_item_options')."` (name, item_id) values ({$this->db->escape($option_name)}, {$this->db->escape($item_id)});";
                    $sql = $this->db->query($query);
                    $item_option_id = $this->db->insert_id();
                    if ($sql == false) return false;

                    // And option values
                    if (isset($params['option' .$id. '_value'])) {
                        foreach ($params['option' .$id. '_value'] as $option_value_id => $value) {
                            $query = "insert into `".$this->db->dbprefix('shop_item_option_values')."` (option_id, value) values ({$this->db->escape($item_option_id)}, {$this->db->escape($value)});";
                            $sql = $this->db->query($query);
                            if ($sql == false) return false;
                        }
                    }
            }
        }
        return TRUE;
    }

    public function createnew($params)
    {
        if ($params['status'] == 'draft') $status = 0;
        else $status = 1;
        $name = $params['title'];
        $price = $params['price'];
        $category = $params['category'];
        $status = $status;
        $description = $params['description'];
        $manufacturer = $params['manufacturer'];
        $hourpost = (intval($params['hourpost']) > 23) ? 23 : intval($params['hourpost']);
        $minutepost = (intval($params['minutepost']) > 59) ? 59 : intval($params['minutepost']);
        $datepost = $params['datepost'] . ' ' . $hourpost.':'.$minutepost.':00';
        
        if(strlen($params['datepost']) < 8){
            $datepost = date("YYYY-mm-dd H:i:s");
        }
        
        // Let's start from items
        $arPrm = array($name, $price, $category, $status, $description, $manufacturer, $datepost);
        $query = "insert into `".$this->db->dbprefix('shop_items')."` (name, price, category, status, description, manufacturer, postdate)
                    values (?, ?, ?, ?, ?, ?, ?);";
        $sql = $this->db->query($query, $arPrm);
        if ($sql == false) return false;
        $item_id = $this->db->insert_id();
        return $item_id;
    }
    
    public function delete($id)
    {
        $query = "delete from `".$this->db->dbprefix('shop_items')."` where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        return $sql;
    }


    /**
     * Edit Shop Item
     *
     * @param int $id - the id of the item we need to edit
     * @param array $params - all item parameters
     * @return boolean
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
                                           description = ? where id={$this->db->escape($id)};";
        $sql = $this->db->query($query, array($description));
        if ($sql == false) return false;

        // Delete all options and write new ones
        $options = $this->get_options($id);
        foreach ($options->result() as $option) {
            $query = "delete from `".$this->db->dbprefix('shop_item_options')."` where id = {$this->db->escape($option->id)};";
            $sql = $this->db->query($query);
        }

        $item_id = $id;

        // Now options if we have
        // Option name first of all
        if (isset($params['option_name']) && count($params['option_name']) != 0) {
            // Here we need loop over all options we have
            foreach ($params['option_name'] as $id =>$option_name) {
                $id++;
                    $query = "insert into `".$this->db->dbprefix('shop_item_options')."` (name, item_id) values ({$this->db->escape($option_name)},
                                                                                      {$this->db->escape($item_id)}       );";
                    $sql = $this->db->query($query);
                    if ($sql == false) return false;
                    
                    $item_option_id = $this->db->insert_id();

                    // And option values (option1_value[])
                    if (isset($params['option' .$id. '_value'])) {
                        
                        foreach ($params['option' .$id. '_value'] as $option_value_id => $value) {
                          
                            $query = "insert into `".$this->db->dbprefix('shop_item_option_values')."` (option_id, value) values ({$this->db->escape($item_option_id)},
                                                                                                       {$this->db->escape($value)});";
                            $sql = $this->db->query($query);
                            if ($sql == false) return false;
                        }
                    }
            }
        }
        return TRUE;
    }
    
    public function edit_ax_data($id, $params)
    {
      if ($params['status'] == 'draft') $status = 0;
        else $status = 1;
        $name = $this->db->escape($params['title']);
        $price = $this->db->escape($params['price']);
        $category = $this->db->escape($params['category']);
        $status = $this->db->escape($status);
        $description = $this->db->escape($params['description']);
        $manufacturer = $this->db->escape($params['manufacturer']);
        $hourpost = (intval($params['hourpost']) > 23) ? 23 : intval($params['hourpost']);
        $minutepost = (intval($params['minutepost']) > 59) ? 59 : intval($params['minutepost']);
        $datepost = $params['datepost'] . ' ' . $hourpost.':'.$minutepost.':00';
        
        if(strlen($params['datepost']) < 8){
            $datepost = date("YYYY-mm-dd H:i:s");
        }
        
        $datepost = $this->db->escape($datepost);

        // Let's start from items
        $query = "update `".$this->db->dbprefix('shop_items')."`  set name = $name,
                                           price = $price,
                                           category = $category,
                                           status = $status,
                                           manufacturer = $manufacturer,
                                           description = $description,
                                           postdate = $datepost
                                           where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        if ($sql == false) return false;

        return TRUE;
    }

}
