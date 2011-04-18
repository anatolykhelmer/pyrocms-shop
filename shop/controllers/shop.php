<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Anatoly Khelmer
 */

class Shop extends Public_Controller {

    private $cart_validation_rules = array();
    private $add_to_cart_rules = array();

    public function  __construct()
    {
        parent::__construct();
        
        $this->load->model('shop_cat_m');
        $this->load->model('shop_items_m');
        $this->load->model('cart_m');
        $this->load->model('galleries/galleries_m');
        $this->load->model('galleries/gallery_images_m');
        $this->lang->load('shop'); 
        $this->load->library('cart');
        $this->load->library('form_validation');

        $this->cart_validation_rules = array(
            array(
                'field' => 'qty[]',
                'label' => 'lang:shop.qty_label',
                'rules' => 'trim|required|numeric'
            ),
            array(
                'field' => 'rowid[]',
                'label' => 'hidden',
                'rules' => 'required'
            )
        );

        $this->add_to_cart_rules = array(
            array(
                'field' => 'item_options[]',
                'label' => 'lang:shop.item_options_label',
                'rules' => 'trim'
            )
        );
    }

    /**
     * Index (view all categories)
     */
    public function index()
    {
        $cat = $this->shop_cat_m->get_all();
        $data['shop_categories'] = $cat;
        $this->template
                        ->title($this->module_details['name'])
                        ->build('index', $data);
    }

    /**
     * View all shop items that belong to category
     *
     * @param int $id - category id
     */
    public function view_category($id)
    {
        $search = $this->input->post('search');
        if ($search) {
            $items =$this->shop_items_m->search($search);
        }
        else {
            $items = $this->shop_items_m->get_all_in_cat($id);
        }
        $data['items'] = $items;
        $cat = $this->shop_cat_m->get($id);
        $data['cat_name'] = $cat->name;
        $data['cat_id'] = $cat->id;
        
        $thumbs = array();

        
        foreach ($items->result() as $item) {
            $gallery = $this->galleries_m->get_all_with_filename('g.id', $item->gallery);

            $gallery = $gallery[0];

            $thumbs[$item->id] = site_url() . 'files/thumb/' . $gallery->file_id;
        }
        $data['thumbs'] = $thumbs;

        $this->template
                        ->title($this->module_details['name'])
                        ->build('view_category', $data);
    }

    

    /**
     *
     * @param int $id - the item id
     */
    public function view_item($id)
    {
        $item = $this->shop_items_m->get($id);
        $data['item'] = $item;

        $item_options = $this->shop_items_m->get_options($id);

        // If it has options
        if ($item_options && $item_options->num_rows()) {
            
            $data['item_options'] = $item_options;

            // For each option - option values
            foreach ($item_options->result() as $item_option) {
                
                $option_values = $this->shop_items_m->get_option_values($item_option->id);

                $values = array();
                foreach ($option_values->result() as $option_value) {
                    $values[$option_value->id] = $option_value->value;
                }
                // Options_values_array contain ( item_option_id => array (option_value_id => option_value_value))
                $options_values_array[$item_option->id] = $values;
            }
            $data['options_values_array'] = $options_values_array;
        }
        else {
            // No options - no need to print ;)
            $data['item_options'] = $item_options;
            $data['options_values_array'] = array();
        }


        $gallery = $this->galleries_m->get_all_with_filename('g.id', $item->gallery);
        $gallery = $gallery[0];
        $gallery_images = $this->gallery_images_m->get_images_by_gallery($item->gallery);
        $data['gallery'] = $gallery;
        $data['item_images'] = $gallery_images;

        $this->template
                        ->title($this->module_details['name'])
                        ->append_metadata(css('shop-style.css', 'shop'))
                        ->build('view_item', $data);
    }


    /**
     *
     * @param int $id - the item id
     */
    public function add_to_cart($id=0)
    {
        $options = array();
        $this->form_validation->set_rules($this->add_to_cart_rules);
        
        if ($this->form_validation->run()) { // If were options and they are clear
            
            // Get an item and its options (without values) from db
            $item = $this->shop_items_m->get($id);
            $item_options = $this->shop_items_m->get_options($id);

            // For each option name (from db) get a value (from post-form)
            $options = array();
            if ($this->input->post('item_options')) {
                foreach ($item_options->result() as $item_option) {

                    // value is array( item_option_id => item_option_value_id )
                    $value = $this->input->post('item_options');
                    // We need just item_option_value_id
                    $value = $value[$item_option->id];
                    $options += array($item_option->name => $value);
                }

                // In $options we have an array of item_option_name => item_option_value_id
                // Now from ids to real values
                foreach($options as $option_name => $value_id){
                    // Get its value from db
                    $option_value = $this->shop_items_m->get_option_value($value_id);
                    $option_value_name = $option_value->value;
                    // Insert to options
                    $options[$option_name] = $option_value_name;
                }
            }
            $data = array(
                   'id'      => $item->id,
                   'qty'     => 1,
                   'price'   => $item->price,
                   'name'    => $item->name,
                   'options' => $options
                );
            $this->cart->product_name_rules	= '\.\:\-_ a-z0-9א-ת';

            if ($this->cart->insert($data) == false) die('Can not insert data to cart: ' .var_dump($data));
        }
        else if ($this->input->post('item_options') == false) {
            $item = $this->shop_items_m->get($id);

            $data = array(
                   'id'      => $item->id,
                   'qty'     => 1,
                   'price'   => $item->price,
                   'name'    => $item->name,
                   'options' => $options
                );
            $this->cart->product_name_rules	= '\.\:\-_ a-z0-9א-ת';


            if ($this->cart->insert($data) == false) die('Can not insert data to cart: ' .var_dump($data));

        }

        // Build the view no matter clear or not
        $this->template
                            ->title($this->module_details['name'])
                            ->build('view_cart');

    }


    public function update_cart()
    {
        $this->form_validation->set_rules($this->cart_validation_rules);

        if ($this->form_validation->run()) {
            // update
          $qty_array = $this->input->post('qty');
          foreach($this->input->post('rowid') as $i => $row_id) {
              $qty = $qty_array[$i];
              $data = array('rowid' => $row_id, 'qty' => $qty);
              $this->cart->update($data);
          }
        }
        // Render the view
        $this->template
                        ->title($this->module_details['name'])
                        ->build('view_cart');
    }


    public function view_cart()
    {
        // Render the view
        $this->template
                        ->title($this->module_details['name']. ' - ' .lang('shop.cart_title'))
                        ->build('view_cart');
    }


    public function check_out()
    {
        if ($this->user == false) redirect('/users/login');
        if ($this->cart->total_items() == 0) redirect('/shop');

        if ($cart_id = $this->cart_m->insert()) {
            $this->cart->destroy();
        }

        $cart = $this->cart_m->get($cart_id);
        $data['cart'] = $cart;

        $items = $this->cart_m->get_items($cart_id);
        $data['items'] = $items;

        // Render the view
        $this->template
                        ->title($this->module_details['name'])
                        ->build('invoice', $data);
    }

}