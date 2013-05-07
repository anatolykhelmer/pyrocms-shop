<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Libaccess
*
* Author: Eko muhammad isa
* 		  ekoisa@gmail.com
*         @eko_isa
*
* Location: http://www.eNotes.web.id/
*
* Created:  30 September 2011
*
* Description:  Easy access authorization system PyroCMS
*
*/

class Libaccess
{
	/*
	 * 
	 * $param = array(
     * 'file_post',     // name file input
	 * 'file_target',   // list file target contains array
	 * )
	 * 
     * 
	 * */
    private $arAccess;
    
	public function __construct(){
        $CI =& get_instance();
        $CI->load->model('shop/shop_permission_m', 'shopaccess');
        $CI->load->model('module/module_m');
        
        $this->arAccess = $this->module_m->roles($this->module_details['slug']);
        
    }

     
    public function get_access(){
/*
        if(!isset($param)){
        return;
        }
        if(!isset($param['file_post'])){
        return;
        }
*/
        print_r($this->arAccess);
        //$accesslist = $this->shopaccess->get_access($this->user->group_id, $this->module_details['slug']);
        echo "<br/>".$this->user->group_id. ":group<br/>".$this->module_details['slug'].":mod<br/>";
        
/*
        $arResult = array();
        foreach($this->arAccess as $v){
             $arResult[$v]=
        }
        
        $accesslist = $this->shopaccess->get_access($this->user->group_id, $this->module_details['slug'], $arAccess);
        if($accesslist == false or $accesslist->add_product == '1'){
            $this->template
                        ->title($this->module_details['name'], lang('shop.item_create_title'))
                        ->build('admin/unauthorized', '');
            return;
        }
        return $file_result;
*/
    }

    
}
