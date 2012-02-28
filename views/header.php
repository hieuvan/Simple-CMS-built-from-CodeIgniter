<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="<?=base_url()?>css/layout.css" />
<script type="text/javascript" src="<?=base_url()?>js/jquery.js"></script>
<script type="text/javascript">
<!--
function MM_findObj(n, d) { //v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<meta name="description" <? if(isset($page['description'])) { echo 'content="'.$page['description'].'"';} else { echo "content='Some meta description'" ; }?> />
<meta name="keywords" <? if(isset($page['keywords'])) { echo 'content="'.$page['keywords'].'"';} else { echo "content='Some meta keywords'" ; }?> />
<title><? if($page['keywords'] != '') { echo $page['keywords']; } else { echo "Some title"; } ?></title>
<script type="text/javascript">
var $j = jQuery.noConflict(); 
</script>

</head>
<body>
<div id="page-wrapper">
     <!-- header -->
     <div id="header">
         <? $segment = $this->uri->segment(1) ;
		 ?>
        <!-- logo -->
        <div id="logo"><a href="<?=base_url()?>"><img border="0" src="images/logo.png" alt="logo" /></a></div>
        <!-- menu nav -->
        <div id="menu-navigation">
                <ul id="navmenu-h">
                    <li><a <? if($segment == '') { echo 'class="current"';}?>href="<?=base_url()?>">Home</a></li>
                    <li><a <? if($segment == 'company') { echo 'class="current"';}?> href="<?=base_url()?>company">Company</a></li>
                    <li class="contact_b"><a  <? if($segment == 'contact') { echo 'class="current"';}?> href="<?=base_url()?>contact">Contact</a></li>                
                </ul>
        </div>
        <!-- end of menu nav -->
     </div>
     <!-- end of header -->