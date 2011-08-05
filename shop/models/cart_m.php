<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author Anatoly Khelmer
 */
class Cart_m extends MY_Model {

    private $CI;

    public function __construct()
    {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->library('cart');
    }

    public function get($id=0)
    {
        $query = "select cart.id,
                         cart.date,
                         cart.customer,
                         cart.new as is_new,
                         cart.cancelled as is_cancelled,
                         default_users.username as username,
                         default_users.email as email,
                         default_profiles.first_name as first_name,
                         default_profiles.last_name as last_name,
                         default_profiles.phone as phone,
                         default_profiles.address_line1 as address_line1,
                         default_profiles.address_line2 as address_line2,
                         default_profiles.address_line3 as address_line3
                from cart, default_users, default_profiles
                where cart.id = {$this->db->escape($id)} and  default_users.id = cart.customer and default_profiles.user_id = cart.customer LIMIT 1";
        $sql = $this->db->query($query);
        $row = $sql->row();
        return $row;
    }


    public function get_all($base_where = array())
    {
      
        $where = '';
        if (!empty($base_where)) {
            $where = 'where ';
            $i = 0;
         
            foreach($base_where as $name => $value) {
                $where .= ($i) ? ' and ' : '';
                if ($name == 'canceled') $name = 1;
                else $name = 0;
                $where .= "{$name} = {$this->db->escape($value)}";
                $i++;
            }

        }
        
        $query = "select * from cart $where order by date desc;";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function get_by_customer($cust_id)
    {
        $query = "select id from cart where customer={$this->db->escape($cust_id)};";
        $sql = $this->db->query($query);
        return $sql;
    }


    
    public function get_customer_info($cust_id)
    {
        $query = "select * from default_users where id={$this->db->escape($cust_id)};";
        $sql = $this->db->query($query);
        return $sql;
    }


    public function get_items($cart_id=0)
    {
        $query = "select * from cart_items where cart={$this->db->escape($cart_id)};";
        $sql = $this->db->query($query);
        return $sql;
    }


    public function get_item_options($item_id)
    {
        $query = "select * from cart_item_options where cart_item_id={$this->db->escape($item_id)};";
        $sql = $this->db->query($query);
        return $sql;
    }


    /**
     * Inserts shopping cart from session to db (performing checkout)
     *
     * @return boolean
     */
    public function insert()
    {
        if($this->CI->cart->total_items() == 0) return false;
        $query = "insert into cart (customer) values ({$this->db->escape($this->user->id)});";
        if ($this->db->query($query)) {
            $cart_id = $this->db->insert_id();
        }
        else return false;

        foreach ($this->CI->cart->contents() as $item) {
            $name = $item['name'];
            $qty = $item['qty'];
            $price = $item['price'];

            $query = "insert into cart_items (name, qty, price, cart) values (
                                                                    {$this->db->escape($name)},
                                                                    {$this->db->escape($qty)},
                                                                    {$this->db->escape($price)},
                                                                    {$this->db->escape($cart_id)});";
            if ($this->db->query($query) == false) return false;
            
            $cart_item_id = $this->db->insert_id();
            
            if (count($item['options']) != 0) {
                foreach ($item['options'] as $name => $value) {
                    $query = "insert into cart_item_options (name, value, cart_item_id) values (
                                                                    {$this->db->escape($name)},
                                                                    {$this->db->escape($value)},
                                                                    {$this->db->escape($cart_item_id)});";
                    $sql = $this->db->query($query);
                    if ($sql == false) return false;
                }
            }
        }
        return $cart_id;
    }

    public function set_old($id)
    {
        $query = "update cart set new=0 where id={$this->db->escape($id)};";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function cancel_order($id)
    {
        if (is_array($id)) {
            foreach ($id as $id_to_cancel) {
                $query = "update cart set cancelled=1 where id={$this->db->escape($id_to_cancel)};";
                $sql = $this->db->query($query);
                if ($sql == false) return FALSE;
            }
        }
        else {
            $query = "update cart set cancelled=1 where id={$this->db->escape($id)};";
            $sql = $this->db->query($query);
            if ($sql == false) return FALSE;
        }
        return TRUE;
    }
}
/* End of file cart_m.php */