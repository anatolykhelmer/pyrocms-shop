<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Anatoly Khelmer
 */

class Admin extends Admin_Controller {


    private $cat_validation_rules = array(
        array(
            'field' => 'title',
            'label' => 'lang:shop.categories_title_label',
            'rules' => 'trim|required|max_length[20]|callback_check_title'
        ),
    );

    private $item_validation_rules = array(
        array(
            'field' => 'title',
            'label' =>'lang:shop.item_title_label',
            'rules' => 'trim|required|max_length[100]|callback_check_title'
        ),
        array(
            'field' => 'category',
            'label' => 'lang:shop.item_category_label',
            'rules' => 'trim'
        ),
        array(
            'field' => 'price',
            'label' => 'lang:shop.item_price_label',
            'rules' => 'trim|required|numeric'
        ),
        array(
            'field' => 'gallery',
            'label' => 'lang:shop.item_gallery_label',
            'rules' => 'trim'
        ),
        array(
            'field' => 'status',
            'label' => 'lang:shop.item_status_label',
            'rules' => 'trim|alpha'
        ),
        array(
            'field' => 'description',
            'label' => 'lang:shop.item_description_label',
            'rules' => 'trim|max_length[255]'
        ),
        array(
            'field' => 'manufacturer',
            'label' => 'lang:shop.item_manufacturer_label',
            'rules' => 'trim|required|max_length[100]'
        ),
        array(
            'field' => 'option1_value[]',
            'label' => 'lang:shop.item_option_value_label',
            'rules' => 'trim|max_length[20]'
        ),
        array(
            'field' => 'option2_value[]',
            'label' => 'lang:shop.item_option_value_label',
            'rules' => 'trim|max_length[20]'
        ),
        array(
            'field' => 'option_name[]',
            'label' => 'lang:shop.item_option_name_label',
            'rules' => 'trim'
        )
    );


    public function __construct()
    {
        parent::__construct();
        $this->load->model('shop_cat_m');
        $this->load->model('shop_items_m');
        $this->load->model('cart_m');
        $this->load->library('form_validation');
        $this->load->helper('html');
        $this->lang->load('shop');
	$this->template->set_partial('shortcuts', 'admin/partials/shortcuts');

        $this->data->categories = array();
	if ($categories = $this->shop_cat_m->order_by('name')->get_all()) {
            foreach ($categories->result() as $category) {
                    $this->data->categories[$category->id] = $category->name;
            }
	}
    }

    

    /**
     * View all categories
     */
    public function index()
    {
        $all_cat = $this->shop_cat_m->get_all();

        $this->template
                ->title($this->module_details['name'])
                ->set('all_cat', $all_cat)
                ->build('admin/index');
    }



    public function create_item()
    {

        $this->load->model('galleries/galleries_m');

        // All galleries names to array
        $galleries = $this->galleries_m->get_all(); // Very problematic!!!!
        $gal = array();
        foreach ($galleries as $gallery) {
            $gal[$gallery->id] = $gallery->title;
        }
        
        $this->form_validation->set_rules($this->item_validation_rules);

        if($this->form_validation->run()) {
    
            if ($this->shop_items_m->create($this->input->post())) {
               
                $this->session->set_flashdata('success', sprintf( lang('shop.item_add_success'), $this->input->post('title')) );
                redirect('admin/shop/list_items');
            }
            // if not
            $this->session->set_flashdata('error', sprintf( lang('shop.item_add_error'), $this->input->post('title')));
        }

        // Loop through each validation rule
        foreach($this->item_validation_rules as $rule)
        {
                $post->{$rule['field']} = set_value($rule['field']);
        }

        // Render the view
        $this->data->post =& $post;
        $this->template
                        ->title($this->module_details['name'], lang('shop.item_create_title'))
                        ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
                        ->append_metadata(css('shop-style.css', 'shop'))
                        ->append_metadata(js('shop.js', 'shop'))
                        ->set('galleries', $gal)
                        ->build('admin/create_item', $this->data);
    }





    public function list_items()
    {
        $base_where = array();

        //add post values to base_where if f_module is posted
        $base_where = $this->input->post('f_category') ? $base_where + array('category' => $this->input->post('f_category')) : $base_where;

        $base_where = $this->input->post('f_status') ? $base_where + array('status' => $this->input->post('f_status')) : $base_where;

        $base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;


        $all_items = $this->shop_items_m->get_all($base_where);
        $data['all_items'] = $all_items;

        $total_rows = $all_items->num_rows();
        $pagination = create_pagination('/admin/shop/list_items', $total_rows);

        //do we need to unset the layout because the request is ajax?
	$this->is_ajax() ? $this->template->set_layout(FALSE) : '';

        $this->template
                        ->title($this->module_details['name'], lang('shop.item_list_title'))
                        ->set('categories', $this->data->categories)
                        ->set('pagination', $pagination)
                        ->append_metadata(js('admin/filter.js'))
                        ->set_partial('filters', 'admin/partials/filters')
                        ->build('admin/list_items', $data);
    }




    public function delete_item($id=0)
    {
        $id_array = (!empty($id)) ? array($id) : $this->input->post('action_to');

        if (!empty($id_array)) {
            $deleted = 0;
            $to_delete = 0;
            
            foreach ($id_array as $id) {
                if ($this->shop_items_m->delete($id)) {
                    $deleted++;
                }
                else {
                    $this->session->set_flashdata('error', sprintf($this->lang->line('shop.item_mass_delete_error'), $id));
                }
                $to_delete++;
            }
            if ($deleted > 0) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('shop.item_mass_delete_success'), $deleted, $to_delete));
            }
        }
        else {
            $this->session->set_flashdata('error', $this->lang->line('shop.item_no_select_error'));
        }

        redirect('admin/shop/list_items');
    }


    /**
     * Edit item
     *
     * @param int $id - the item id
     */
    public function edit_item($id=0)
    {
        $item = $this->shop_items_m->get($id);
        $item->title = $item->name;
        $item->status = ($item->status == 0) ? 'Draft' : 'Live';

        $item or redirect('admin/shop/list_items');

        // Get all options
        $item_options = $this->shop_items_m->get_options($id);

        // For each option we need to get an array of values and all this
        // we will save in array (item_option_id => sql result)
        $options_values_array = array();
        $k = 0;
        foreach ($item_options->result() as $item_option) {
            $k++;
            $item->option_name[] = $item_option->name;
            $item_option_values = $this->shop_items_m->get_option_values($item_option->id);

            $value_name = 'option' .$k. '_value';
            $item->{$value_name} = array();
            $i = 1;
            foreach ($item_option_values->result() as $value) {
                $item->{$value_name}[$i++] = $value->value;
            }
            $options_values_array += array($item_option->id => $item_option_values);
        }
//die(var_dump($item));
        // Get all galleries
        $this->load->model('galleries/galleries_m');
        $galleries = $this->galleries_m->get_all();

        // Save all galleries titles in array
        foreach($galleries as $gallery) {
            $gal[$gallery->id] = $gallery->title;
        }

        $this->form_validation->set_rules($this->item_validation_rules);

        if ($this->form_validation->run()) {
            $this->shop_items_m->edit($id, $this->input->post());
        }


        // Loop through each validation rule
        foreach($this->item_validation_rules as $rule)
        {
            if ($this->input->post($rule['field']) !== false) {
                $item->{$rule['field']} = set_value($rule['field']);
            }
        }


        // Render the view
        $this->data->item_options = $item_options;
        $this->data->items_options_array = $options_values_array;
        $this->data->post =& $item;
        $this->template->title($this->module_details['name'], lang('shop.item_edit_title'))
                                        ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
                                        ->set('galleries', $gal)
                                        ->append_metadata(js('shop.js', 'shop'))
                                        ->append_metadata(css('shop-style.css', 'shop'))
                                        ->build('admin/edit_item', $this->data);
    }



    
    public function create_category()
    {
        $this->form_validation->set_rules($this->cat_validation_rules);
        if ($this->form_validation->run()) {
            $post = $this->input->post();
            if ($this->shop_cat_m->create($post['title'])) {
                $this->session->set_flashdata('success', sprintf( lang('shop.cat_add_success'), $this->input->post('title')) );
                redirect('admin/shop');
            }
            $this->session->set_flashdata(array('error'=> lang('cat_add_error')));
        }

        // Loop through each validation rule
        foreach($this->cat_validation_rules as $rule)
        {
                $category->{$rule['field']} = set_value($rule['field']);
        }

        // Render the view
        $this->data->category =& $category;
        $this->template->title($this->module_details['name'], lang('shop.cat_create_title'))
                                        ->build('admin/create_category', $this->data);
    }




    public function delete_category($id=0)
    {
        $id_array = (!empty($id)) ? array($id) : $this->input->post('action_to');

        // Delete multiple
        if (!empty($id_array)) {
            
                $deleted = 0;
                $to_delete = 0;
                
                foreach ($id_array as $id) {
                        if($this->shop_cat_m->delete($id)) {
                                $deleted++;
                        }
                        else {
                                $this->session->set_flashdata('error', sprintf($this->lang->line('shop.cat_mass_delete_error'), $id));
                        }
                        $to_delete++;
                }

                if( $deleted > 0 ) {
                        $this->session->set_flashdata('success', sprintf($this->lang->line('shop.cat_mass_delete_success'), $deleted, $to_delete));
                }
        }
        else {
                $this->session->set_flashdata('error', $this->lang->line('shop.cat_no_select_error'));
        }

        redirect('admin/shop/index');
    }




    public function edit_category($id=0)
    {
        $category = $this->shop_cat_m->get($id);

		// ID specified?
		$category or redirect('admin/shop/index');

                $this->form_validation->set_rules($this->cat_validation_rules);

		// Validate the results
		if ($this->form_validation->run())
		{
			$this->shop_cat_m->edit($_POST['title'], $id)
				? $this->session->set_flashdata('success', sprintf( lang('shop.cat_edit_success'), $this->input->post('title')) )
				: $this->session->set_flashdata(array('error'=> lang('shop.cat_edit_error')));

			redirect('admin/shop/index');
		}

		// Loop through each rule
		foreach($this->cat_validation_rules as $rule)
		{
			if($this->input->post($rule['field']) !== FALSE)
			{
				$category->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		// Render the view
		$this->data->category =& $category;
		$this->template->title($this->module_details['name'], sprintf(lang('shop.cat_edit_title'), $category->name))
						->build('admin/edit_category', $this->data);
    }




    public function list_orders()
    {
        $base_where = array();

        //add post values to base_where if f_module is posted
        $base_where = $this->input->post('f_status') ? $base_where + array('cancelled' => $status) : $base_where;

        
        $orders = $this->cart_m->get_all($base_where);
        $this->data->orders = $orders;

        // Find all cart info for each cart and store it in array (cart_id => cart_info(array from db))
        $info_array = array();
        foreach ($orders->result() as $order) {
            $cart_info = $this->cart_m->get($order->id);
            $info_array += array($order->id => $cart_info);
        }
        $this->data->info_array = $info_array;

        // Is AJAX
        $this->is_ajax() ? $this->template->set_layout(false) : '';


        // Render the view
        $this->template
                        ->title($this->module_details['name'])
                        ->set('categories', $this->data->categories)
                        ->append_metadata(css('shop-style.css', 'shop'))
                        ->append_metadata(js('admin/filter.js'))
                        ->set_partial('filters', 'admin/partials/filterorders')
                        ->build('admin/list_orders', $this->data);
    }




    public function view_order($id=0)
    {   
        $cart = $this->cart_m->get($id);
        $items = $this->cart_m->get_items($id);

        $item_options = array();

        // Now for each item - its options
        // Save it in array item_options ( item_id => item_options )
        foreach($items->result() as $item) {
            $item_options[$item->id] = $this->cart_m->get_item_options($item->id);
        }

        $customer_id = $cart->customer;

        $this->data->cart = $cart;
        $this->data->items = $items;
        $this->data->item_options = $item_options;
        $this->cart_m->set_old($id);

        // Render the view
        $this->lang->load('users/profile');
        $this->template
                        ->title($this->module_details['name'])
                        ->append_metadata(css('shop-style.css', 'shop'))
                        ->build('admin/view_order', $this->data);
    }

    public function cancel_order($id=0)
    {
        $this->cart_m->cancel_order($id); // need to check if returns false

        $this->list_orders();
    }


    





    /**
     * Callback method that checks the title of the category
     * @access public
     * @param string title The title to check
     * @return bool
     */
    public function _check_title($title = '')
    {
            if ($this->shop_cat_m->check_name($title))
            {
                    $this->form_validation->set_message('_check_title', sprintf($this->lang->line('cat_already_exist_error'), $title));
                    return FALSE;
            }

            return TRUE;
    }
}