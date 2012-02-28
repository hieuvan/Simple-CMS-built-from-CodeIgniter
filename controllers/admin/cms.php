<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms extends CI_Controller 
{

	function __construct()
	{
		parent::__construct();             		
	}
	
	#Dashboard
	function index()
	{
		$this->check_authentication();
		$this->load->view('admin/header');
		$this->load->view('admin/dashboard');
		$this->load->view('admin/navigation');
		$this->load->view('admin/footer');
	}
	
	# Check authentication and load neccessary models
	function check_authentication() 
	{
		if(!$this->session->userdata('adminLoggedIn')) 
		{
			redirect('login');
		}
		$this->load->model('Page_model');
		$this->load->model('Gallery_model');
	}
	
	# Log in
	function login() 
	{
		if (isset($_POST['username']) && isset($_POST['password'])) {
			$this->load->model('User_model');
			$user = $this->User_model->authenticate($_POST);
			if ($user) {
				$this->session->set_userdata('adminLoggedIn', $user['id']);
				redirect('admin');
			}
			else
			{
				
				$this->session->set_flashdata('error_authentication','Wrong username/password');
			}
		}
		
		$this->load->view('admin/login');
		$this->load->view('admin/footer');
	}
	
	# Log out and delete session data
	function logout() 
	{
		$this->session->sess_destroy();
		redirect('admin');
	}
	
	/** PAGE MANAGER SECTION **/
	function page_manager() 
	{
		$this->check_authentication();
		$categories = $this->Page_model->get_categories();
		$categories_list = '';
		foreach($categories as $category) {
	      if($category['id']!=6)
		  {
			if ($category['parent'] == 0) {
				$categories_list .= '<option value="'.$category['id'].'">'.$category['title'].'</option>';
			}
			else {
				$parent = $this->Page_model->get_category($category['parent']);
				$categories_list .= '<option value="'.$category['id'].'">'.$parent['title'].' -- '.$category['title'].'</option>';
			}
		  }
		}
		
		$data['categories_list'] = $categories_list;
		$data['languages'] = $this->Page_model->get_languages();
		$data['templates'] = $this->Page_model->get_templates();
		$data['galleries'] = $this->Gallery_model->get_galleries();
		$data['menus'] = $this->Page_Menu_model->get_page_menus();
		$this->load->model('Cute_model');
		$this->Cute_model->init();
		$this->load->view('admin/header');
		$this->load->view('admin/cms/page-manager',$data);
		$this->load->view('admin/footer');
	}
	
	# Get pages list by category and language
	function get_pages_list() 
	{
		$this->check_authentication();
		$cid = $_POST['cid'];
		$lid = $_POST['lid'];
		$pages = $this->Page_model->get_pages($cid,$lid);
		if (count($pages) == 0) {
			print '<p>There is no page in this section yet! Click the <b><u>Add page</u></b> button above to create new page for this section.</p>';
		}
		else {
			$output = '';
			foreach($pages as $page) {
				$category = $this->Page_model->get_category($page['category_id']);
				$view_link = base_url().$category['name'].'/'.$page['name'];
				
				$output .= '<div class="page-row" id="page-row-'.$page['id'].'">';
				$output .= '<div class="page-name">'.$page['title'].'</div>';
				$output .= '<div class="page-view"><a href="'.$view_link.'" target="_blank"><img src="'.base_url().'images/icon-view.png" /></a></div>';
				$output .= '<div class="page-view"><a href="#"><img src="'.base_url().'images/icon-properties.png" onClick="page_properties('.$page['id'].');" title="Update Properties" /></a></div>';
				$output .= '<div class="page-view"><a href="#"><img src="'.base_url().'images/icon-edit.png" onClick="page_content('.$page['id'].');" title="Update Content" /></a></div>';
				$output .= '<div class="page-view"><a href="#"><img src="'.base_url().'images/icon-delete.png" onClick="return page_delete('.$page['id'].');" title="Delete" /></a></div>';
				$is_published = $page['published'];
				$published = "";
				if ($is_published == 1) {
					$published = "published";
				} else {
					$published = "unpublished";
				}
				$output .= '<div class="page-view"><img src="'.base_url().'images/icon-'.$published.'.png" /></div>';
				$time = strtotime($page['modified']);
				$time = date("j-m-Y",$time);
				$output .= '<div class="page-view">'.$time.'</div>';
				$output .= '</div>';
			}
			print $output;
		}
	}	
	
	# Add new page within a category and a language
	function add_page($cid=null,$lid=null) 
	{
		$this->check_authentication();
		$data = array(
			'category_id' => $cid,
			'title' => 'New Blank Page',
			'user_id' => $this->session->userdata('adminLoggedIn'),
			'created' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s'),
			'language_id' => $lid
		);
		if ($this->Page_model->add_page($data)) {
			print "Ok";
		} else {
			print "Error";
		}
	}	
	
	# Delete page
	function delete_page($id) 
	{
		$this->check_authentication();		
		if ($this->Page_model->delete_page($id)) {
			print "Ok";
		} else {
			print "Error";
		}
	}
	
	# Get page properties
	function get_page_properties($id=null) 
	{
		$this->check_authentication();
		$page = $this->Page_model->get_page($id);
		$output = "";
		$output .= $page['name']."~";
		$output .= $page['title']."~";
		$output .= $page['category_id']."~";
		$output .= $page['description']."~";
		$output .= $page['keywords']."~";
		$output .= $page['template_id']."~";
		$output .= $page['gallery_id']."~";
		$output .= $page['menu_id']."~";
		$output .= $page['published']."~";
		$output .= $page['searchable'];
		print $output;
	}
	
	# Get page content
	function get_page_content($id=null) 
	{
		$this->check_authentication();
		$page = $this->Page_model->get_page($id);
		$output = $page['content'];
		print $output;
	}
	
	# Update page properties
	function update_page_properties() 
	{
		$this->check_authentication();
		$name = $_POST['name'];
		$name = str_replace(" ","-",$name);
		$published = 0;
		
		$data = array(
				'name' => $name,
				'title' => ucwords($_POST['title']),
				'category_id' => $_POST['category_id'],
				'description' => $_POST['description'],
				'keywords' => $_POST['keywords'],
				'published' => $_POST['published'],
				'searchable' => '1',
				'modified' => date('Y-m-d H:i:s')
			);	
		$data['gallery_id'] = $_POST['gallery_id'];
		$this->Page_model->update_page($_POST['page_id'],$data);
		$this->session->set_userdata('cid',$_POST['category_id']);
	    if (isset($_POST['browser'])) 
		{
			redirect($_POST['browser']);
		} 
		else 
		{
			redirect('admin/cms/page-manager');
		}		
	}
	
	# Update page content
	function update_page_content() 
	{
		$this->check_authentication();
		$data = array(
				'content' => $_POST['content_text'],
				'modified' => date('Y-m-d H:i:s')
			);
		$this->Page_model->update_page($_POST['page_id2'],$data);
		$this->session->set_userdata('cid',$_POST['category_id2']);
		if (isset($_POST['browser'])) {
			redirect($_POST['browser']);
		} else {
			redirect('admin/cms/page-manager');
		}
	}
	
	/** PHOTO GALLERIES SECTION **/
	function galleries($id=null,$pid=null) 
	{
		# Check authentication and load models
		$this->check_authentication();
		
		# load normal header view
		$this->load->view('admin/header');
		
		# if not a particular gallery
		if ($id == null) {
			# Get all galleries
			$galleries = $this->Gallery_model->get_galleries();
			# Determine the thumbnail
			$thumbnails = array();
			foreach($galleries as $gallery) {
				$photos = $this->Gallery_model->get_photos($gallery['id']);
				if (count($photos) == 0) {
					$thumbnails[$gallery['id']] = '<a href="'.base_url().'admin/cms/galleries/'.$gallery['id'].'"><img src="'.base_url().'images/thumbnail-no-image.jpg" title="'.$gallery['title'].'" /></a>';
				} else {
					$thumbnail = $this->Gallery_model->get_gallery_thumbnail($gallery['id']);
					$thumbnails[$gallery['id']] = '<a href="'.base_url().'admin/cms/galleries/'.$gallery['id'].'"><img src="'.base_url().'uploads/galleries/'.md5("cdkgallery".$gallery['id']).'/thumbnails/'.$thumbnail.'" title="'.$gallery['title'].'" /></a>';
				}
			}
			
			# Pass data to the view
			$data['galleries'] = $galleries;
			$data['thumbnails'] = $thumbnails;			
			$this->load->view('admin/cms/galleries',$data);
		} 
		
		# Viewing a particular gallery
		else {
			# Get the gallery
			$data['gallery'] = $this->Gallery_model->get_gallery($id);
			if(!$data['gallery'])
			{
				redirect('admin/cms/galleries/');
			}
			# Get all photos in the gallery
			$data['photos'] = $this->Gallery_model->get_photos($id);
			# If no photo yet
			if ($pid == null) {
				$this->load->view('admin/cms/gallery',$data);
			} else {
				$data['photo'] = $this->Gallery_model->get_photo($pid);
				if($data['photo'])
				{
				$this->load->view('admin/cms/photo',$data);
				}
				else
				{
					redirect('admin/cms/galleries/'.$id);
				}
			}		
		}
		
		$this->load->view('admin/navigation');
		$this->load->view('admin/footer');
	}
	
	# Reorder image within a gallery
	function reorder($id=null,$move=null) 
	{
		$this->check_authentication();
		$photo = $this->Gallery_model->get_photo($id);
		$gallery = $this->Gallery_model->get_gallery($photo['gallery_id']);
		
		if ($move == 1) {
			$next_photo = $this->Gallery_model->get_next_photo($photo['order'],$gallery['id']);
			$this->Gallery_model->swap_order($photo,$next_photo);
			
		} else if ($move == -1) {
			$previous_photo = $this->Gallery_model->get_previous_photo($photo['order'],$gallery['id']);
			$this->Gallery_model->swap_order($photo,$previous_photo);
		}		
		
		redirect('admin/cms/galleries/'.$gallery['id']);
	}
	
	# Create a new gallery
	function create_gallery() 
	{
		$this->check_authentication();
		if (trim($_POST['title']) == "") {
			$this->session->set_flashdata('error_cg',true);
			redirect('admin/cms/galleries');
		}
		$data = array(
			'title' => $_POST['title'],
			'created' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s')
		);
		$gid = $this->Gallery_model->create_gallery($data);
		
		$path = "./uploads/galleries";
		$newfolder = md5('cdkgallery'.$gid);
		$dir = $path."/".$newfolder;
		if(!is_dir($dir))
		{
		  mkdir($dir);
		  chmod($dir,0777);
		  $fp = fopen($dir.'/index.html', 'w');
		  fwrite($fp, '<html><head>Permission Denied</head><body><h3>Permission denied</h3></body></html>');
		  fclose($fp);
		}
		
		$dir .= "/thumbnails";
		if(!is_dir($dir))
		{
		  mkdir($dir);
		  chmod($dir,0777);
		  $fp = fopen($dir.'/index.html', 'w');
		  fwrite($fp, '<html><head>Permission Denied</head><body><h3>Permission denied</h3></body></html>');
		  fclose($fp);
		}		
		redirect('admin/cms/galleries');
	}
	
	# Add photo to a gallery, resize, crop image
	function add_photo()
	{
		$this->check_authentication();
		$gid = $_POST['gallery_id'];		
		$config['upload_path'] = "./uploads/galleries/".md5('cdkgallery'.$gid);
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '4096'; // 4 MB
		$config['max_width']  = '2000';
		$config['max_height']  = '2000';
		$config['overwrite'] = FALSE;
		$config['remove_space'] = TRUE;
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{
			$this->session->set_flashdata('error_addphoto',$this->upload->display_errors());			
		}	
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$file_name = $data['upload_data']['file_name'];
			$width = $data['upload_data']['image_width'];
			$height = $data['upload_data']['image_height'];
			$photo = array(
				'name' => $file_name,
				'created' => date('Y-m-d H:i:s'),
				'modified' => date('Y-m-d H:i:s'),
				'gallery_id' => $gid,
				'order' => 0
			);
			$pid = $this->Gallery_model->add_photo($photo);
			$this->Gallery_model->update_photo($pid,array('order'=>$pid));
			
			/* note: this is the best version working in all images, resize then crop */
			if ($width <= 138 && $height <= 112) 
			{
				copy("./uploads/galleries/".md5('cdkgallery'.$gid).'/'.$file_name,"./uploads/galleries/".md5('cdkgallery'.$gid).'/thumbnails/'.$file_name);
			
			}
			else 
			{
				if ($width > 800 || $height > 600)
				{
				$config = array();
				// Resize image
				$config['source_image'] = "./uploads/galleries/".md5('cdkgallery'.$gid)."/".$file_name;
				$config['create_thumb'] = TRUE;
				$config['maintain_ratio'] = TRUE;
				$config['quality'] = 100;
				$config['width'] = 800;
				$config['height'] = 600;
				$config['master_dim'] = 'auto';
				$this->load->library('image_lib');
				$this->image_lib->clear();
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
				unlink("./uploads/galleries/".md5('cdkgallery'.$gid)."/".$file_name);
				rename("./uploads/galleries/".md5('cdkgallery'.$gid)."/".$data['upload_data']['raw_name']."_thumb".$data['upload_data']['file_ext'],"./uploads/galleries/".md5('cdkgallery'.$gid)."/".$file_name);
				$this->image_lib->clear();
			    }	
			
			// Thumbnail creation
			$config = array();
			$config['source_image'] = "./uploads/galleries/".md5('cdkgallery'.$gid)."/".$file_name;
			$config['create_thumb'] = TRUE;
			$config['new_image'] = "./uploads/galleries/".md5('cdkgallery'.$gid)."/thumbnails/".$file_name;
			$config['maintain_ratio'] = TRUE;
			$config['quality'] = 100;
			  if ($width < $height) 
			  {		
			    if(($height/$width) < (112/138))
				{
					$config['height'] = 138;
				$config['width'] = intval(138 * ($height/$width));
				$config['master_dim'] = 'height';
				}
				else
				{
				$config['width'] = 138;
				$config['height'] = intval(112 * ($height/$width));
				$config['master_dim'] = 'width';
				}
				
			  } 
			  else if($width > $height)
			  {		
			   
					
				if(($width/$height) < (138/112))
				{
					$config['width'] = 138;
					$config['height'] = intval(112 * ($width/$height));
					$config['master_dim'] = 'width';
				}
				else
				{
					$config['width'] = intval(138 * ($width/$height));
					
				$config['height'] = 112;
				$config['master_dim'] = 'height';
				}
				
				
			  }
			  else  // for square image
			  {		
			  
				$config['width'] = 138;
				$config['height'] = intval(138 * ($height/$width));
				// if the thumbnail width is longer set to width otherwise set to height
				$config['master_dim'] = 'width';
				
			  }
			
			$this->load->library('image_lib');
			$this->image_lib->clear();
			$this->image_lib->initialize($config);
			if(!$this->image_lib->resize())
			{
				$this->session->set_flashdata('error_addphoto',$this->upload->display_errors());	
			}
			
			rename("./uploads/galleries/".md5('cdkgallery'.$gid)."/thumbnails/".$data['upload_data']['raw_name']."_thumb".$data['upload_data']['file_ext'],"./uploads/galleries/".md5('cdkgallery'.$gid)."/thumbnails/".$file_name);
			$this->image_lib->clear();
			
			// Crop thumbnail			
			$config['image_library'] = 'GD2';
			$config['source_image'] = "./uploads/galleries/".md5('cdkgallery'.$gid)."/thumbnails/".$file_name;
			
			$config['width'] = 138;
			$config['height'] = 112;
		    // really important shoud be crop from top 0 left 0
				$config['x_axis'] = 0;
				$config['y_axis'] = 0;
			$config['maintain_ratio'] = FALSE;
			
			$this->image_lib->initialize($config);
			$crop_thumbnail = $this->image_lib->crop();
			if ( ! $crop_thumbnail)
			{
				$this->session->set_flashdata('error_addphoto',$this->upload->display_errors());
			}
			unlink("./uploads/galleries/".md5('cdkgallery'.$gid)."/thumbnails/".$file_name);
			rename("./uploads/galleries/".md5('cdkgallery'.$gid)."/thumbnails/".$data['upload_data']['raw_name']."_thumb".$data['upload_data']['file_ext'],"./uploads/galleries/".md5('cdkgallery'.$gid)."/thumbnails/".$file_name);
		  }
			$this->session->set_flashdata('addphoto_id',$pid);
			$this->session->set_flashdata('addphoto_src',$file_name);
		}
		redirect('admin/cms/galleries/'.$gid);
	}
	
	# Delete a gallery and all images within that gallery
	function delete_gallery($id) 
	{
		$this->check_authentication();
		$photos = $this->Gallery_model->get_photos($id);
		foreach($photos as $photo) {
			if ($this->Gallery_model->delete_photo($photo['id'])) {
				unlink("./uploads/galleries/".md5("cdkgallery".$id)."/".$photo['name']);
				unlink("./uploads/galleries/".md5("cdkgallery".$id)."/thumbnails/".$photo['name']);				
			}
		}
		unlink("./uploads/galleries/".md5("cdkgallery".$id)."/index.html");
		unlink("./uploads/galleries/".md5("cdkgallery".$id)."/thumbnails/index.html");
		if ($this->Gallery_model->delete_gallery($id)) {
			rmdir("./uploads/galleries/".md5("cdkgallery".$id)."/thumbnails");
			rmdir("./uploads/galleries/".md5("cdkgallery".$id));
			$this->Page_model->reset_gallery($id);
			print "Ok";
		} else {
			print "Error";
		}		
		
	}	
	
	# Delete a photo  
	function delete_photo($id) 
	{
		$this->check_authentication();
		$photo = $this->Gallery_model->get_photo($id);
		
		if ($this->Gallery_model->delete_photo($id)) {
			$this->Gallery_model->reset_thumbnail($id);
			unlink("./uploads/galleries/".md5("cdkgallery".$photo['gallery_id'])."/".$photo['name']);
			unlink("./uploads/galleries/".md5("cdkgallery".$photo['gallery_id'])."/thumbnails/".$photo['name']);
			
		} else {
			
		}
		redirect('admin/cms/galleries/'.$photo['gallery_id']);
	}
	
	# Add caption title for a photo
	function add_photo_title()
	{
		$this->check_authentication();
		$id = $_POST['photo_id'];
		$data = array(
			'title' => $_POST['title'],			
			'modified' => date('Y-m-d H:i:s')
			);
		$this->Gallery_model->update_photo($id,$data);
		redirect('admin/cms/galleries/'.$_POST['gallery_id']);
	}
}
?>