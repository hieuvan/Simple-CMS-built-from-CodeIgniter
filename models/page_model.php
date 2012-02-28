<?php
class Page_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	
	function get_page($id) {
		$this->db->where('id',$id);
		$query = $this->db->get('pages');
		return $query->first_row('array');
	}	
	function get_page_by_name($cid,$lid,$name) {
		$this->db->where('category_id',$cid);
		$this->db->where('language_id',$lid);
		$this->db->where('name',$name);
		$query = $this->db->get('pages');
		return $query->first_row('array');
	}
	
	function get_pages($cid,$lid) {
		$this->db->where('category_id',$cid);
		$this->db->where('language_id',$lid);
		$this->db->order_by('title','asc');
		$query = $this->db->get('pages');
		return $query->result_array();
	}
	
	function update_page($id,$data) {
		$this->db->where('id',$id);
		return $this->db->update('pages',$data);
	}
	
	function add_page($data) {
		$this->db->insert('pages',$data);
		return $this->db->insert_id();
	}
	
	function delete_page($id) {
		$this->db->where('id',$id);
		return $this->db->delete('pages');
	}
	
	function get_category($id) {
		$this->db->where('id',$id);
		$query = $this->db->get('categories');
		return $query->first_row('array');
	}
	function get_category_by_name($name) {
		$this->db->where('name',$name);
		$query = $this->db->get('categories');
		return $query->first_row('array');
	}
	function get_categories() {
		$this->db->order_by('id','asc');
		$query = $this->db->get('categories');
		return $query->result_array();
	}
	function get_sub_categories($parent) {
		$this->db->where('parent',$parent);
		$query = $this->db->get('categories');
		return $query->result_array();
	}
	
	function get_languages() {
		$query = $this->db->get('languages');
		return $query->result_array();
	}
	function get_templates() {
		$query = $this->db->get('templates');
		return $query->result_array();
	}
	function get_template($id) {
		$this->db->where('id',$id);
		$query = $this->db->get('templates');
		return $query->first_row('array');
	}
	function reset_menu($id) {
		$this->db->where('menu_id',$id);
		$query = $this->db->get('pages');
		foreach($query->result_array() as $page) {
			$this->update_page($page['id'],array('menu_id' => 0));
		}
	}
	function reset_gallery($id) {
		$this->db->where('gallery_id',$id);
		$query = $this->db->get('pages');
		foreach($query->result_array() as $page) {
			$this->update_page($page['id'],array('gallery_id' => 0));
		}
	}
	
	function update_home_box_bg($box,$data)
	{
		$page = $this->get_page(1);
		$content = explode("~",$page['content']);
		if($box == 'left-box')
		{
		   $content[0] = $data['name'];
		}
		else if($box == 'middle-box')
		{
		   $content[1] = $data['name'];
		}
		else
		{
		   $content[2] = $data['name'];
		}
		$newcontent = $content[0].'~'.$content[1].'~'.$content[2].'~'.$content[3].'~'.$content[4].'~'.$content[5];
		$newdata = array(
		'content' => $newcontent,
		'modified' => $data['modified']
		);
		$isUpdated = $this->update_page(1,$newdata);
		return $isUpdated;
	}
	function update_home_page($data)
	{
		$page = $this->get_page(1);
		$content = explode("~~",$page['content']);
		$data_array = explode("~~",$data['content']);
		$content[0] = $data_array[0];
		$content[1] = $data_array[1];
		$content[2] = $data_array[2];
		$newcontent = $content[0].'~~'.$content[1].'~~'.$content[2];
		$newdata = array(
		'content' => $newcontent,
		'modified' => $data['modified']
		);
		$isUpdated = $this->update_page(1,$newdata);
		return $isUpdated;
	}
	
}
?>