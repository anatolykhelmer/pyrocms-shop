<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Anatoly Khelmer
 */
class Tests extends Admin_Controller {

    public function  __construct() {
        parent::__construct();
        $this->load->model('shop_items_m');
        $this->load->model('shop_cat_m');
        $this->load->model('cart_m');

        $this->load->library('unit_test');
    }


    public function index()
    {
        // Test category create
        $category_name = 'test_category';
        $this->db->trans_start(true);

            $this->shop_cat_m->create($category_name);
            $query = "select count(*) from shop_categories where name={$this->db->escape($category_name)};";
            $sql = $this->db->query($query);
            $this->db->trans_rollback();
        $this->db->trans_complete();

        $res = $sql->row_array();

        $test = $res['count(*)'];
        $expected_result = 1;
        $test_name = 'Create New Category Test';
        $this->unit->run($test, $expected_result, $test_name);


        // Test Edit Category
        $category_name = 'test_category';
        $name_to_set = 'edited test category';

        $this->db->trans_start(true);

            $this->shop_cat_m->create($category_name);
            $category_id = $this->db->insert_id();
            $this->shop_cat_m->edit($name_to_set, $category_id);

            $sql = $this->db->query("select name from shop_categories where id={$this->db->escape($category_id)};");

        $this->db->trans_rollback();
        $this->db->trans_complete();

        $res = $sql->row();
        $test = $res->name;
        $expected_result = $name_to_set;
        $test_name = 'Edit Category Test';
        $this->unit->run($test, $expected_result, $test_name);

        // Test Create Item
        $params = array (
            'title' => 'Unit Test Item',
            'status' => 'draft',
            'price' => '2500',
            'category' => '2',
            'gallery'   => '2',
            'description' => 'Just Unit Test item',
            'option_name' => array(
                                    1 => 'Color',
                                    2 => 'Size'
                                    ),
            'option1_value' => array(
                                      1 => 'Red',
                                      2 => 'Green',
                                      3 => 'White'
                                      ),
            'option2_value' => array(
                                      1 => 'S',
                                      2 => 'L',
                                      3 => 'XL'
                                      )

        );

        $this->db->trans_start();
            $this->_create_item_test($params);
            $this->db->trans_rollback();
        $this->db->trans_complete();
            

        // End Of Tests
        // Render the view
        $this->template
                        ->title('tests')
                        ->build('admin/test');
    }


    public function _create_item_test($params)
    {
        $sql = $this->shop_items_m->create($params);
        $item_id =$this->db->insert_id();
        $query = "select * from shop_items where name={$this->db->escape($params['title'])};";
        $item_sql = $this->db->query($query);
        $row = $item_sql->row();


        $item[] = $params['title'];
        $item[] = $params['price'];
        $item[] = $params['description'];
        $item[] = $params['category'];
        $item[] = $params['gallery'];
        $test = array($row->name, $row->price, $row->description, $row->category, $row->gallery);
        $expected_result = $item;
        $test_name = 'Insert item';
        $this->unit->run($test, $expected_result, $test_name);

        return true;
    }



}
