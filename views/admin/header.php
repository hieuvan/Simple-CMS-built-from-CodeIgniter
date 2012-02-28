<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Administrator</title>
    <script type="text/javascript" src="<?=base_url()?>js/jquery.js"></script>
    <link rel='stylesheet' type='text/css' href='<?=base_url()?>css/admin/layout.css' />
    <script>
	var $j = jQuery.noConflict();  
	</script>
</head>

<body>
<div id="page-wrapper">
	<div id="header">
		<div class="logo"><a href="<?=base_url()?>admin">Admin Home</a></div>
        <div id="main-nav">
        	<div class="text-welcome">
                <a href="<?=base_url()?>admin">Website Management</a>
                <?php if ($this->session->userdata('adminLoggedIn')): ?>
                    // <a href="<?=base_url()?>admin/logout">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="content">