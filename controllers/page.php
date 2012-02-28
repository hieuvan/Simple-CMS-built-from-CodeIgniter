<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller 
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('Page_model');
		$this->load->model('Gallery_model');
	}
	function index()
	{
		redirect('home');
	}
	function content($category_name="",$page_name="") {
		# First determine the category
		$category_name = str_replace("_","-",$category_name);
		$category = $this->Page_model->get_category_by_name($category_name);
		$category_id = $category['id'];
		
		# Second is the language
		$language_id = 1; // English, will use session to switch between languages
		
		# Get the page
		if ($page_name == "") {
			$page_name = $category['default_page'];
			
		}
		$page = $this->Page_model->get_page_by_name($category_id,$language_id,$page_name);
	
		
		# If not found, display not found page
		if (!$page) {
			show_404('page');
		}
		
		# Otherwise, get the neccessary information
		
		# Title
		$title = ucwords($category['title']);
		
		# Photos of gallery if exists
		$photos = false;
		
		if ($page['gallery_id'] > 0) {
			$photos = $this->Gallery_model->get_photos($page['gallery_id']);
		}
		
		# Pass all data to view
		$header['title'] = $title;		
		$data['photos'] = $photos;
		$data['page'] = $page;
		$data['category'] = $category;
		$header['page'] = $page;
		$this->load->view('header',$header);
		$this->load->view('content',$data);
	}
}
?>
