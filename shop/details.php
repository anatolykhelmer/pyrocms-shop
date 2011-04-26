<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author Anatoly Khelmer
 */

class Module_Shop extends Module {

    public $version = "0.6";

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
        );
    }

    
    public function install()
    {
        $this->db->trans_start();
        
                $query = "create table if not exists `shop_categories` (
                            `id` int auto_increment,
                            `name` varchar(20) not null,
                            primary key (`id`),
                            UNIQUE KEY `name` (`name`)
                            ) engine = InnoDB DEFAULT CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `shop_items` (
                            `id` int auto_increment,
                            `name` varchar(255) not null,
                            `manufacturer` varchar(100) not null,
                            `category` int not null,
                            `gallery` int,
                            `description` varchar(255),
                            `price` double not null,
                            `options` bool DEFAULT 0,
                            `status` bool not null,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`category`) REFERENCES shop_categories(`id`)
                                ON DELETE CASCADE,
                            FOREIGN KEY (`gallery`) REFERENCES galleries(`id`)
                                ON DELETE CASCADE
                            ) engine = InnoDB DEFAULT CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `shop_item_options` (
                            `id` int auto_increment,
                            `item_id` int not null,
                            `name` varchar(20) not null,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`item_id`) REFERENCES shop_items(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB DEFAULT CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `shop_item_option_values` (
                            `id` int auto_increment,
                            `option_id` int not null,
                            `value` varchar(20) not null,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`option_id`) REFERENCES shop_item_options(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB DEFAULT CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `cart` (
                            `id` int auto_increment,
                            `customer` smallint unsigned not null,
                            `date` timestamp,
                            `cancelled` bool not null DEFAULT 0,
                            `new` bool not null DEFAULT 1,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`customer`) REFERENCES users(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `cart_items` (
                            `id` int auto_increment,
                            `name` varchar(50) not null,
                            `price` double not null,
                            `qty` smallint unsigned not null,
                            `cart` int not null,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`cart`) REFERENCES cart(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB CHARSET utf8;";
                $sql = $this->db->query($query);

                $query = "create table if not exists `cart_item_options` (
                            `id` int auto_increment,
                            `name` varchar(20) not null,
                            `value` varchar(20) not null,
                            `cart_item_id` int not null,
                            PRIMARY KEY (`id`),
                            FOREIGN KEY (`cart_item_id`) REFERENCES cart_items(`id`)
                                ON DELETE CASCADE
                            ) ENGINE = InnoDB CHARSET utf8;";
                $sql = $this->db->query($query);

        $this->db->trans_complete();

        if($this->db->trans_status() === false) return FALSE;   
        
        return TRUE;
    }

    public function uninstall()
    {
        $query = "drop table if exists  shop_item_option_values,
                                        shop_item_options,
                                        shop_items,
                                        shop_categories,
                                        cart_item_options,
                                        cart_items,
                                        cart;";
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