<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Eko Muhammad Isa
 */
 
class Shop_permission_m extends CI_Model
{
	private $_groups = array();
    // 'show_product', 'add_product', 'edit_product', 'delete_product', 'setting_options'
    
    public function __construct()
	{
        $this->load->model('module/module_m');
        $this->arAccess = $this->module_m->roles($this->module_details['slug']);
	}
    
	/**
	 * Check access authorization
	 **/
	public function get_access()
    {
        $query = "select roles from ".$this->db->dbprefix('permissions')." where group_id=? and module=? ";
        $sql = $this->db->query($query, array($this->user->group_id, $this->module_details['slug']));
        $row = $sql->row();
        
        $hsl = array();
        if(isset($row->roles)){
            $hsl = json_decode($row->roles, true);
        }
            
        $arResult = array();
        foreach($this->arAccess as $v){
            $arResult[$v] = (isset($hsl[$v])) ? $hsl[$v] : 0;
        }
        return (object)$arResult;
    }
    
}
