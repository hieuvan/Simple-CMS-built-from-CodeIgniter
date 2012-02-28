<?php
class Gallery_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	function create_gallery($data) 
	{
		$this->db->insert('galleries',$data);
		return $this->db->insert_id();
	}
	function delete_gallery($id) 
	{
		$this->db->where('id',$id);
		return $this->db->delete('galleries');
	}	
	function delete_photo($id) 
	{
		$this->db->where('id',$id);
		return $this->db->delete('photos');
	}	
	function get_gallery_thumbnail($id) 
	{
		$this->db->where('id',$id);
		$query = $this->db->get('galleries');
		$gallery = $query->first_row('array');
		if ($gallery['thumbnail'] == '0') {
			$sql = "SELECT * FROM `photos` WHERE `gallery_id` = '$id' ORDER BY id ASC LIMIT 1";
			$query = $this->db->query($sql);
		}
		else {
			$this->db->where('id',$gallery['thumbnail']);
			$query = $this->db->get('photos');
		}
		$photo = $query->first_row('array');
		return $photo['name'];
	}
	function reset_thumbnail($id) 
	{
		$this->db->where('thumbnail',$id);
		$query = $this->db->get('galleries');
		if ($query->num_rows() > 0) {
			$gallery = $query->first_row('array');
			$gid = $gallery['id'];
			$this->db->where('id',$gid);
			$this->db->update('galleries', array('thumbnail' => '0'));
		}
	}	
	function get_galleries() 
	{
		$query = $this->db->get('galleries');
		return $query->result_array();
	}
	function get_gallery($id) 
	{
		$this->db->where('id',$id);
		$query = $this->db->get('galleries');
		return $query->first_row('array');
	}
	function get_photos($gallery_id) 
	{
		$this->db->where('gallery_id',$gallery_id);
		$this->db->order_by('order','asc');
		$query = $this->db->get('photos');
		return $query->result_array();		
	}
	function get_photo($id) 
	{
		$this->db->where('id',$id);
		$query = $this->db->get('photos');
		return $query->first_row('array');
	}
	function get_next_photo($order,$gid) 
	{
		$sql = "SELECT * FROM `photos` WHERE `gallery_id` = $gid AND `order` > $order ORDER BY `order` ASC LIMIT 1";
		$query = $this->db->query($sql);
		return $query->first_row('array');
	}
	function get_previous_photo($order,$gid) 
	{
		$sql = "SELECT * FROM `photos` WHERE `gallery_id` = $gid AND `order` < $order ORDER BY `order` DESC LIMIT 1";
		$query = $this->db->query($sql);
		return $query->first_row('array');
	}
	function swap_order($one,$two) 
	{
		$order1 = $one['order'];
		$order2 = $two['order'];
		$data1 = array('order' => $order2);
		$data2 = array('order' => $order1);
		$this->db->where('id',$one['id']);
		$this->db->update('photos',$data1);
		
		$this->db->where('id',$two['id']);
		$this->db->update('photos',$data2);
	}
	function add_photo($data) 
	{
		$this->db->insert('photos',$data);
		return $this->db->insert_id();
	}	
	function update_photo($id,$data) 
	{
		$this->db->where('id',$id);
		$this->db->update('photos',$data);
	}
	
}
?>