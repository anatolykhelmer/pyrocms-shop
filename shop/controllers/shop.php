<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Anatoly Khelmer
 */

class Shop extends Public_Controller {

    public function  __construct()
    {
        parent::__construct();
        $this->load->model('shop_cat_m');
        $this->load->model('shop_items_m');
        $this->load->model('galleries/galleries_m');
        $this->lang->load('shop');
    }

    public function index()
    {
        $cat = $this->shop_cat_m->get_all();
        $data['shop_categories'] = $cat;
        $this->template
                        ->title($this->module_details['name'])
                        ->build('index', $data);
    }

    public function view_category($id)
    {
        $items = $this->shop_items_m->get_all_in_cat($id);
        $data['items'] = $items;
        // Get all items in category
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

}