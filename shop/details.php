<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @Modified by Eko Muhammad Isa from Shopping cart Anatoly Khelmer
 */

class Module_Shop extends Module {

    public $version = "0.7";

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Shop',
                'he' => 'חנות'
            ),
            'description' => array(
                'en' => 'Easy e-commerce module',
                'he' => 'מודול להצבת חנות באתר'
            ),
            'frontend' => true,
            'backend'  => true,
            'menu'     => 'content',
            
            
            'roles' => array(
				'show_product', 'add_product', 'edit_product', 'delete_product', 'setting_options'
			)
        );
    }

    
    public function install()
    {
        $this->db->trans_start();
        
                $query = "create table if not exists `".$this->db->dbprefix('shop_categories')."` (
                            `id` int auto_increment,
                            `name` varchar(20) not null,
                            primary key (`id`),
                            UNIQUE KEY `name` (`name`)
                            ) engine = InnoDB DEFAULT CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `".$this->db->dbprefix('shop_items')."` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `name` varchar(255) NOT NULL,
						  `manufacturer` varchar(100) NOT NULL,
						  `category` int(11) NOT NULL,
						  `gallery` int(11) DEFAULT NULL,
						  `description` text,
						  `price` double NOT NULL,
						  `options` tinyint(1) DEFAULT '0',
						  `status` tinyint(1) NOT NULL,
						  `postdate` datetime DEFAULT NULL,
						  PRIMARY KEY (`id`),
						  FOREIGN KEY (`category`) REFERENCES `".$this->db->dbprefix('shop_categories')."` (`id`) ON DELETE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `".$this->db->dbprefix('shop_item_options')."` (
                            `id` int auto_increment,
                            `item_id` int not null,
                            `name` varchar(20) not null,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`item_id`) REFERENCES ".$this->db->dbprefix('shop_items')."(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB DEFAULT CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `".$this->db->dbprefix('shop_item_option_values')."` (
                            `id` int auto_increment,
                            `option_id` int not null,
                            `value` varchar(20) not null,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`option_id`) REFERENCES ".$this->db->dbprefix('shop_item_options')."(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB DEFAULT CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `".$this->db->dbprefix('cart')."` (
                            `id` int auto_increment,
                            `customer` smallint unsigned not null,
                            `date` timestamp,
                            `cancelled` bool not null DEFAULT 0,
                            `new` bool not null DEFAULT 1,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`customer`) REFERENCES ".$this->db->dbprefix('users')."(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `".$this->db->dbprefix('cart_items')."` (
                            `id` int auto_increment,
                            `name` varchar(50) not null,
                            `price` double not null,
                            `qty` smallint unsigned not null,
                            `cart` int not null,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`cart`) REFERENCES ".$this->db->dbprefix('cart')."(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `".$this->db->dbprefix('cart_item_options')."` (
                            `id` int auto_increment,
                            `name` varchar(20) not null,
                            `value` varchar(20) not null,
                            `cart_item_id` int not null,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`cart_item_id`) REFERENCES ".$this->db->dbprefix('cart_items')."(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "CREATE TABLE `".$this->db->dbprefix('shop_images')."` (
                            `id_shop_images` INT( 5 ) NOT NULL AUTO_INCREMENT,
                            `id_item` INT( 11 ) NOT NULL ,
                            `image_name` VARCHAR( 150 ) NULL ,
                            `image_originalname` VARCHAR( 150 ) NULL ,
                            `is_default` BOOLEAN NULL DEFAULT '0',
                            `publish` TINYINT( 1 ) NULL DEFAULT '0',
                            PRIMARY KEY ( `id_shop_images` )
                            ) ENGINE = InnoDB CHARSET utf8;";
                $sql = $this->db->query($query);
                
                $query = "CREATE TABLE `".$this->db->dbprefix('shop_setting')."` (
                            `id_shop_setting` INT( 5 ) NOT NULL AUTO_INCREMENT,
                            `setting_name` VARCHAR( 150 ) NULL ,
                            `setting_value` TEXT NULL ,
                            PRIMARY KEY ( `id_shop_option` )
                            ) ENGINE = InnoDB CHARSET utf8;";
                $sql = $this->db->query($query);
                
                $path = UPLOAD_PATH . "shop/";
                if (!is_file($path) && !is_dir($path)) {
                    @mkdir($path, 0777, TRUE); //create the directory
                    @chmod($path, 0777); //make it writable
                }
                
                $path = UPLOAD_PATH . "shop/thumb/";
                if (!is_file($path) && !is_dir($path)) {
                    @mkdir($path, 0777, TRUE); //create the directory
                    @chmod($path, 0777); //make it writable
                }
                
        $this->db->trans_complete();

        if($this->db->trans_status() === false) return FALSE;   
        
        return TRUE;
    }

    public function uninstall()
    {
        $query = "drop table if exists  ".$this->db->dbprefix('shop_item_option_values').",
                                        ".$this->db->dbprefix('shop_item_options').",
                                        ".$this->db->dbprefix('shop_items').",
                                        ".$this->db->dbprefix('shop_images').",
                                        ".$this->db->dbprefix('shop_options').",
                                        ".$this->db->dbprefix('shop_categories').",
                                        ".$this->db->dbprefix('cart_item_options').",
                                        ".$this->db->dbprefix('cart_items').",
                                        ".$this->db->dbprefix('cart').";";
        $sql = $this->db->query($query);
        return $sql;
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function help()
    {
        return '<h2>help yourself!!!</h2>';
    }
}
/* End of file details.php */
