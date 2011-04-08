<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author Anatoly Khelmer
 */

class Module_Shop extends Module {

    public $version = "0.2";

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
        $query = "create table if not exists `shop_categories` (
                    `id` int auto_increment,
                    `name` varchar(20) not null,
                    primary key (`id`),
                    UNIQUE KEY `name` (`name`)
                    ) engine = InnoDB DEFAULT CHARSET utf8;";
        $sql = $this->db->query($query);

        $query = "create table if not exists `shop_items` (
                    `id` int auto_increment,
                    `name` varchar(20) not null,
                    `category` int not null,
                    `gallery` int,
                    `description` varchar(255),
                    `price` double not null,
                    `options` int,
                    `active` bool not null,
                    PRIMARY KEY (`id`),
                    FOREIGN KEY (`category`) REFERENCES shop_categories(`id`)
                        ON DELETE CASCADE,
                    FOREIGN KEY (`gallery`) REFERENCES galleries(`id`)
                        ON DELETE CASCADE
                    ) engine = InnoDB DEFAULT CHARSET utf8;";
        $this->db->query($query);
        
        return TRUE;
    }

    public function uninstall()
    {
        $query = "drop table if exists shop_items, shop_categories;";
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