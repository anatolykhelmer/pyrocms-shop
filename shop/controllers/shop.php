<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Anatoly Khelmer
 */

class Shop extends Public_Controller {

    private $cart_validation_rules = array();

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
        $items = $this->shop_items_m->get_all_in_cat($id);
        $data['items'] = $items;
        $cat = $this->shop_cat_m->get($id);
        $data['cat_name'] = $cat->name;
        
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
    public function add_to_cart($id)
    {

        $item = $this->shop_items_m->get($id);

        $data = array(
               'id'      => $item->id,
               'qty'     => 1,
               'price'   => $item->price,
               'name'    => $item->name,
               'options' => array()
            );
        $this->cart->product_name_rules	= '\.\:\-_ a-z0-9א-ת';

        if ($this->cart->insert($data) == false) die('Can not insert data to cart: ' .var_dump($data));

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