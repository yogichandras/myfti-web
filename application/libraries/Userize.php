<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * .com
 */
class Userize {


  public function init()
  {
    $CI =& get_instance();
    // $registered = array_intersect($this->_role(), $this->_controllerList());
    $access_all = array('dashboard','core');
    $registered = array_merge($access_all,$this->_role());
    $uri_segment = strtolower($CI->router->class);
    if (in_array($uri_segment,$registered)) {
      return true;
    }else {
      return false;
    }
  }


  public function _controllerList() {
		$CI =& get_instance();
		$CI->load->helper('directory');
		$rootpath = APPPATH. '/modules/';
		// $fileinfos = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootpath));
    $fileinfos = directory_map(APPPATH. "/modules/", 1);
		foreach($fileinfos as $pathname) {
    		$result[] = str_replace('\\','',$pathname);
		}
		return $result;
	}



  private function _secureReturnedFileName($file) {

		$permitted_file_extension = '.php';

		if (strpos($file, $permitted_file_extension) == TRUE) {

			$secure = str_replace(array('.php',"application/modules/backend/controllers/"),'',strtolower($file));
      $secure2 = str_replace(array('application/modules/backend/controllers\\',''),'',$secure);
			return $secure2;
		}
	}


  private function _role(){
    $CI =& get_instance();
    $id_level = $CI->session->userdata("id_level");
    $qry =  $CI->db->select("id_level, id_main_menu, controller")
                   ->from("rule_level")
                   ->join("main_menu","main_menu.id_menu=id_main_menu")
                   ->where("id_level",$id_level)
                   ->get();
    if ($qry->num_rows() > 0) {
      foreach ($qry->result() as $row) {
        $result[] = $row->controller;
      }

      return $result;
    }else {
      return array();
    }

  }


  function combo_controllerlist()
  {
    $array_remove = array('login','core','wizard');
    $list_controller = $this->_controllerList();
    return array_diff($list_controller,$array_remove);
  }


}
