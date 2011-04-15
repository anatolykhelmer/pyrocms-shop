<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author Anatoly Khelmer
 */
class Cart_m extends MY_Model {

    private $CI;

    public function  __construct()
    {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->library('cart');
    }

    public function get($id=0)
    {
        $query = "select cart.id, cart.date, cart.customer, users.username as username
                from cart, users
                where cart.id = {$this->db->escape($id)} and users.id = cart.customer LIMIT 1";
        $sql = $this->db->query($query);
        $row = $sql->row();
        return $row;
    }


    public function get_all()
    {
        $query = "select * from cart order by date;";
        $sql = $this->db->query($query);
        return $sql;
    }


    
    public function get_customer_info($cust_id)
    {
        $query = "select * from users where id={$this->db->escape($cust_id)};";
        $sql = $this->db->query($query);
        return $sql;
    }


    public function get_items($cart_id=0)
    {
        $query = "select * from cart_items where cart={$this->db->escape($cart_id)};";
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
            return $cart_id;
        }
    }
}
/* End of file cart_m.php */