<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Modified by Eko Muhammad Isa from Shopping cart Anatoly Khelmer
 */

class Admin extends Admin_Controller {


    private $cat_validation_rules = array(
        array(
            'field' => 'title',
            'label' => 'lang:shop.categories_title_label',
            'rules' => 'trim|required|max_length[20]|callback_check_title'
        ),
    );

    private $item_tab_rules = array(
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
            'field' => 'status',
            'label' => 'lang:shop.item_status_label',
            'rules' => 'trim|alpha'
        ),
        array(
            'field' => 'description',
            'label' => 'lang:shop.item_description_label',
            'rules' => 'trim'
        ),
        array(
            'field' => 'manufacturer',
            'label' => 'lang:shop.item_manufacturer_label',
            'rules' => 'trim|required|max_length[100]'
        ),
        array(
            'field' => 'datepost',
            'label' => 'lang:shop.item_date_label',
            'rules' => 'trim'
        ),
        array(
            'field' => 'hourpost',
            'label' => 'lang:shop.item_hour_label',
            'rules' => 'trim'
        ),
        array(
            'field' => 'minutepost',
            'label' => 'lang:shop.item_minute_label',
            'rules' => 'trim'
        )
    );

    private $auth;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('shop_cat_m');
        $this->load->model('shop_items_m');
        $this->load->model('cart_m');
        $this->load->library('form_validation');
        $this->load->helper('html');
        $this->lang->load('shop');
        
        $this->load->model('shop_permission_m', 'shopaccess');
        $this->auth = $this->shopaccess->get_access();
        $data['auth'] = $this->auth;
        
        $this->template->set_partial('shortcuts', 'admin/partials/shortcuts', $data);

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
        // 'show_product', 'add_product', 'edit_product', 'delete_product', 'setting_options'
        if($this->auth->add_product == 0){
            $this->template
                ->title($this->module_details['name'], lang('shop.item_create_title'))
                ->build('admin/unauthorized', '');
            return;
        }
        $this->template
                ->title($this->module_details['name'], lang('shop.item_create_title'))
                ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
                ->append_metadata(css('shop-style.css', 'shop'))
                ->append_metadata(js('shop.js', 'shop'))
                ->set('page_edited', '')
                ->build('admin/create_item', '');
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
		$this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';

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
    public function edit_item($id=0, $tab = '')
    {
        // 'show_product', 'add_product', 'edit_product', 'delete_product', 'setting_options'
        if($this->auth->edit_product == 0){
            $this->template
                ->title($this->module_details['name'], lang('shop.item_edit_title'))
                ->build('admin/unauthorized', '');
            return;
        }
        
        $this->form_validation->set_rules($this->item_tab_rules);

        if ($this->form_validation->run()) {
            $this->shop_items_m->edit_ax_data($id, $this->input->post());
        }


        // Loop through each validation rule
        foreach($this->item_tab_rules as $rule)
        {
            if ($this->input->post($rule['field']) !== false) {
                $item->{$rule['field']} = set_value($rule['field']);
            }
        }


        // Render the view
        //$this->data->item_options = $item_options;
        //$this->data->items_options_array = $options_values_array;
        $this->data->post =& $item;
        $this->template->title($this->module_details['name'], lang('shop.item_edit_title'))
                                        ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
                                        ->append_metadata(js('shop.js', 'shop'))
                                        ->append_metadata(css('shop-style.css', 'shop'))
                                        ->set('page_edited', $id)
                                        ->build('admin/edit_item_ax', $this->data);
    }



    
    public function create_category()
    {
        // 'show_product', 'add_product', 'edit_product', 'delete_product', 'setting_options'
        if($this->auth->add_product == 0){
            $this->template
                ->title($this->module_details['name'], lang('shop.cat_create_title'))
                ->build('admin/unauthorized', '');
            return;
        }
        
        
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
        
        // 'show_product', 'add_product', 'edit_product', 'delete_product', 'setting_options'
        if($this->auth->edit_product == 0){
            $this->template
                ->title($this->module_details['name'], lang('shop.cat_edit_title'))
                ->build('admin/unauthorized', '');
            return;
        }
        
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
        $this->input->is_ajax_request() ? $this->template->set_layout(false) : '';


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
    
    /**
	 * Create method, creates a new category via ajax
	 * @access public
	 * @return void
	 */
	public function ax_read($mode = 'add', $id = 0)
	{
		if($mode == 'add'){
			$this->template
                        ->set_layout(FALSE)
                        ->append_metadata(css('shop-style.css', 'shop'))
                        ->append_metadata(js('shop.js', 'shop'))
                        ->set('gal', '')
                        ->set('categories', $this->data->categories)
                        ->set('tab', '')
                        ->set('tab_act', $mode)
                        ->set('item_id', '')
                        ->build('admin/partials/ax_item_process', '');
                        
		}elseif($mode == 'edit'){
			$item = $this->shop_items_m->get($id);
			$item->title = $item->name;
			$item->status = ($item->status == 0) ? 'Draft' : 'Live';

			$item or redirect('admin/shop/list_items');
			
			$this->data->post =& $item;
			$this->template
                        ->set_layout(FALSE)
                        ->append_metadata(css('shop-style.css', 'shop'))
                        ->append_metadata(js('shop.js', 'shop'))
                        ->set('gal', '')
                        ->set('categories', $this->data->categories)
                        ->set('tab', '')
                        ->set('tab_act', $mode)
                        ->set('item_id', $id)
                        ->build('admin/partials/ax_item_process', $this->data);

		}
		
	}
	
	
	public function ax_image($id = 0)
	{
        $process_msg = "";
        if($id == 0){
            $process_msg = "You must save \'Item\' data before uploading images.";
        }
        $this->template
                    ->set_layout(FALSE)
                    //->append_metadata(css('shop-style.css', 'shop'))
                    //->append_metadata(js('shop.js', 'shop'))
                    ->set('process_msg', $process_msg)
                    ->set('id_item', $id)
                    ->build('admin/partials/ax_img_process', '');

	}
	
	public function ax_delimage($id = 0)
	{
        
        if($id == 0){
            return;
        }
        $this->load->model('shop_img_m');
        $item_img = $this->shop_img_m->read_img_byid($id);
        if($item_img){
            $img_url_base = UPLOAD_PATH.'shop/thumb/'.$item_img->image_name;
            $img_url_base2 = UPLOAD_PATH.'shop/'.$item_img->image_name;
            //echo $img_url_base;
            if(is_file($img_url_base) == true){
                //chmod($img_url_base, 0777);
                @unlink($img_url_base);
                //chmod($img_url_base2, 0777);
                @unlink($img_url_base2);
            }
            
            $del_result = $this->shop_img_m->del($id);
        }

	}
    
	public function ax_listimage($id = 0)
	{
		$this->load->model('shop_img_m');
		$item_img = $this->shop_img_m->read_img($id);
		if($item_img == false){
			$process_msg = "Image not found.";
		}else{
			//$item_img->publish = ($item_img->publish == 0) ? 'Draft' : 'Live';
            $process_msg = "";
		}
		// print_r($item_img);
			$this->data->post =& $item_img;
			$this->template
                        ->set_layout(FALSE)
                        ->set('process_msg', $process_msg)
                        ->build('admin/partials/ax_list_img', $this->data);

	}
    
	public function ax_setdefault($id = 0)
	{
		$this->load->model('shop_img_m');
        $item_img = $this->shop_img_m->read_img_byid($id);
        //print_r($item_img);
        //echo "<br/>print<br/>";

		if($item_img == false){
			$process_msg = "Image not found.";
		}else{
			//$item_img->publish = ($item_img->publish == 0) ? 'Draft' : 'Live';
            $jml1 = $this->shop_img_m->setundefault($item_img->id_item);
            //echo "dbg:".$jml1.":<br/>";
            $jml2 = $this->shop_img_m->setdefault($id);
            //echo "dbg:".$jml2.":<br/>";
            $process_msg = "";
		}

		

	}
    
	public function ax_upload($id = 0)
	{
        if(empty($id) or $id == 0){
            echo '<div class="closable notification information">You must save \'Item\' data before uploading images.</div>';
            return;
        }
        
        if(!isset($_FILES)){
            echo '<div class="closable notification information">No image selected.</div>';
            return;
        }
        
        $this->load->model('shop_img_m');
        $this->load->library('libupload');
        
        $new_file_id = $this->shop_img_m->read_maxid();
        $new_file_id = intval($new_file_id->maxid);
        if($new_file_id > 0){
            $new_file_id = $new_file_id+1;
        }else{
            $new_file_id = 1;
        }
        $param = array(
        'file_post'=>'myfile',     // name file input
        'file_target'=> array(array(
                'new_name'=>'item_img_'.$new_file_id,
                'path'=>UPLOAD_PATH.'shop/',
                'width'=>700,
                'height'=>600,
                ), array(
                'new_name'=>'item_img_'.$new_file_id,
                'path'=>UPLOAD_PATH.'shop/thumb/',
                'width'=>150,
                'height'=>140,
                )));

        $hasil_upload = $this->libupload->upload_img($param);
        $status_hasil = $hasil_upload[0];
        if($status_hasil == true){
            
            $jml_ar = count($hasil_upload);
            $save_sts = true;
            $name_save = '';
            //for($k = 2; $k<$jml_ar; $k++){
                
                $param = array('id_shop_images'=>$new_file_id,'id_item'=>$id,'image_name'=>$hasil_upload[2]['file_new_name'],'image_originalname'=>$hasil_upload[2]['file_orig_name'],'status'=>'');
                $save_result = $this->shop_img_m->add($param);
                if($save_result == false and $save_sts = true){
                    $save_sts = false;
                    $name_save = $hasil_upload[2]['file_orig_name'];
                }
            //}
            if($save_sts){
                $process_msg = '<div class="closable notification success">'.sprintf( lang('shop.upload_success'), $hasil_upload[2]['file_orig_name']).'</div>';
            }else{
                $process_msg = '<div class="closable notification error">'.sprintf( lang('shop.upload_save_error'), $name_save).'</div>';
            }
        }else{
            $process_msg = '<div class="closable notification error">'.sprintf( lang('shop.upload_error'), $hasil_upload[2]['file_orig_name']).'<br/>'.$hasil_upload[1].'</div>';
        }

       
        $this->template->set_layout(false);
        $process_msg = rawurlencode($process_msg);
        //$dbg_show = rawurlencode($dbg_show);
        $retval = json_encode(array(
            'message'=>$process_msg
        ));
        sleep(3);
        
        //echo $retval;
        $this->template->set('process_msg', $retval)->build('admin/partials/ax_upload_ret', '');
    }
        
    
        
    /**
	 * Create method, save item data via ajax
	 * @author : Eko isa
	 * @Modified by : 
	 */
	public function ax_save($tab = '', $tab_act = '')
	{
        // $tab >>>>> data, images, option
        
        $process_sts = false;
        if ($tab == '' or $tab = 'Data'){
            
            $this->form_validation->set_rules($this->item_tab_rules);
			
             if($this->form_validation->run()) {
                
                if($tab_act == 'add' or $tab_act == ''){
					$process_msg = "<div class=\"closable notification error\">".sprintf( lang('shop.item_save_error'), $this->input->post('title'))."</div>";
					$process_sts = false;
					$item_id = '';
					$tab_act = 'add';
					
					$ret_add = $this->shop_items_m->createnew($this->input->post());
					if ($ret_add) {
				   
						$process_msg = "<div class=\"closable notification success\">".sprintf( lang('shop.item_add_success'), $this->input->post('title'))."</div>";
						$process_sts = true;
						$item_id = $ret_add;
						$tab_act = 'edit';
					}
				}elseif($tab_act == 'edit'){
                    $process_msg = "<div class=\"closable notification error\">".sprintf( lang('shop.item_save_error'), $this->input->post('title'))."</div>";
					$process_sts = false;
					$item_id = $this->input->post('item_id');
					$tab_act = 'edit';
					
					$ret_add = $this->shop_items_m->edit_ax_data($this->input->post('item_id'), $this->input->post());
					if ($ret_add) {
						$process_msg = "<div class=\"closable notification success\">".sprintf( lang('shop.item_save_success'), $this->input->post('title'))."</div>";
						$process_sts = true;
						$item_id = $this->input->post('item_id');
						$tab_act = 'edit';
					}
                }
			}else{
				$item_id = '';
				
				$process_msg = "<div class=\"closable notification error\">".sprintf( lang('shop.item_save_error'), $this->input->post('title'))."<br/>".validation_errors()."</div>";
			}
        } 
   
        // Loop through each validation rule
        
        foreach($this->item_tab_rules as $rule)
        {
                $post->{$rule['field']} = set_value($rule['field']);
        }

        // Render the view
        $this->data->post =& $post;

        $this->template->set_layout(FALSE)
                        ->append_metadata(css('shop-style.css', 'shop'))
                        ->append_metadata(js('shop.js', 'shop'))
                        ->set('tab', $tab)
                        ->set('tab_act', $tab_act)
                        ->set('item_id', $item_id)
                        ->set('process_msg', $process_msg)
                        ->build('admin/partials/ax_item_process', $this->data);
        
	}
    
    public function setting()
    {
        // 'show_product', 'add_product', 'edit_product', 'delete_product', 'setting_options'
        if($this->auth->setting_options == 0){
            $this->template
                ->title($this->module_details['name'], lang('shop.setting_title'))
                ->build('admin/unauthorized', '');
            return;
        }
        $this->template
                ->title($this->module_details['name'], lang('shop.setting_title'))
                ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
                ->append_metadata(css('shop-style.css', 'shop'))
                ->append_metadata(js('shop_setting.js', 'shop'))
                ->set('tab_active', 'payinfo')
                ->build('admin/setting', '');
    }

    public function ax_settingread($mode = 'payinfo', $id = 0)
	{
		if($mode == 'payinfo'){
            $this->load->model('shop_setting_m');
			$dtload['payinfo_live'] = $this->shop_setting_m->get_setting('PAYINFO_LIVE');
			$dtload['payinfo_content'] = $this->shop_setting_m->get_setting('PAYINFO_CONTENT');
			
			$this->template
                        ->set_layout(FALSE)
                        ->append_metadata(css('shop-style.css', 'shop'))
                        ->append_metadata(js('shop_setting.js', 'shop'))
                        //->set('gal', '')
                        ->build('admin/partials/ax_setting_pinfo', $dtload);

		}
		
	}
    
    public function ax_settingsave($mode = 'payinfo')
	{
		if($mode == 'payinfo'){
            $this->load->model('shop_setting_m');
			$save_1 = $this->shop_setting_m->set_setting('PAYINFO_LIVE', $this->input->post('pistatus'));
			$save_2 = $this->shop_setting_m->set_setting('PAYINFO_CONTENT', $this->input->post('picontent'));
			
            if($save_1 == true and $save_2 == true){
                $process_msg = "<div class=\"closable notification success\">".lang('shop.setting_save_success')."</div>";
            }elseif($save_1 == false and $save_2 == false){
                $process_msg = "<div class=\"closable notification error\">".lang('shop.setting_save_error')."</div>";
            }else{
                $process_msg = "<div class=\"closable notification information\">".lang('shop.setting_save_info')."</div>";
            }
            
            $dtload['payinfo_live'] = $this->shop_setting_m->get_setting('PAYINFO_LIVE');
			$dtload['payinfo_content'] = $this->shop_setting_m->get_setting('PAYINFO_CONTENT');
            
			$this->template
                        ->set_layout(FALSE)
                        ->append_metadata(css('shop-style.css', 'shop'))
                        ->append_metadata(js('shop_setting.js', 'shop'))
                        ->set('process_msg', $process_msg)
                        ->build('admin/partials/ax_setting_pinfo', $dtload);

		}
		
	}
    
}
