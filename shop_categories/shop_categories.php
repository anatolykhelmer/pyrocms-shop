<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package 		PyroCMS
 * @subpackage 		Shop Categories Widget
 * @author              Anatoly Khelmer
 *
 * Show shop categories in your site
 */

class Widget_Shop_categories extends Widgets
{
	public $title = 'Shop Categories';
	public $description = 'Display Shop Categories.';
	public $author = 'Anatoly Khelmer';
	public $website = 'anatoly';
	public $version = '0.4';


        public function run()
        {
            $this->load->model('shop/shop_cat_m');
            $categories = $this->shop_cat_m->get_all();
            return array('categories' => $categories);
        }
}