<script>
$j(function() {
	$j('.gallery-thumb *').tooltip({
		showURL: false
	});
	
});
function delete_photo(id)
{
	if (confirm('Are you sure you want to do this?')) {
		var url = "<?=base_url()?>admin/cms/delete_photo/";
		url = url + id;
		window.location = url;
	} else {
		return false;
	}
}
</script>
<div id="left-content">
	<div class="content-title"><img src="<?=base_url()?>images/admin/title-site-control.png" /></div>
    <div class="bar-title"><div>Add image to <?=$gallery['title']?></div></div>
    <div class="content-area">
    	<?php $pid = $this->session->flashdata('addphoto_id');
		$src = $this->session->flashdata('addphoto_src');
		if ($pid) { ?>        
        <div class="photo-thumb"><img src="<?=base_url()?>uploads/galleries/<?php print md5("cdkgallery".$gallery['id']); ?>/thumbnails/<?=$src?>" /></div>
        <div style="float:left;width:300px">
        <form name="addPhotoTitleForm" method="post" action="<?=base_url()?>admin/cms/add_photo_title">
        <input type="hidden" name="photo_id" value="<?=$pid?>" />
        <input type="hidden" name="gallery_id" value="<?=$gallery['id']?>" />
        The image has been uploaded successfully! Please add title for the photo.
        <p><input type="text" name="title" class="medium" /></p>
        <a href="#"><input type="button" class="button rounded" value="Add Title" onClick="document.addPhotoTitleForm.submit()" /></a>
        </form>
        </div>
		<?php } else { ?>
        <form name="addPhotoForm" method="post" enctype="multipart/form-data" action="<?=base_url()?>admin/cms/add_photo">
        <?php
			if ($this->session->flashdata('error_addphoto')) {
				print $this->session->flashdata('error_addphoto');
			}
		?>
        <input type="hidden" name="gallery_id" value="<?=$gallery['id']?>" />
		<p><input type="file" name="userfile" /> (Max width is 2000px and max height is 2000px)</p>
        <a href="#"><input type="button" class="button rounded" value="Add Image" onClick="document.addPhotoForm.submit()" /></a>
        </form>
        <?php } ?>
        <div class="gallery-thumbs">
        <?php 
			if (count($photos) == 0) { print "<p>There is no photo yet</p>"; }
			else
			for($i=0;$i<count($photos);$i++) { ?>
        	<div class="gallery-thumb" id="photo-<?=$photos[$i]['id']?>"><img title="<?=$photos[$i]['title']?>" src="<?=base_url()?>uploads/galleries/<?php print md5("cdkgallery".$gallery['id']); ?>/thumbnails/<?php print $photos[$i]['name'];?>" />
            	<div class="icon">
                	<a href="<?=base_url()?>admin/cms/galleries/<?=$gallery['id']?>/<?=$photos[$i]['id']?>"><img src="<?=base_url()?>images/icon-box-edit.png" title="Edit this photo" /></a><a href="#" onclick="delete_photo(<?=$photos[$i]['id']?>)"><img src="<?=base_url()?>images/icon-box-delete.png" title="Delete this photo" /></a><br />
                <?php if (count($photos) == 1) { ?>
                	<img src="<?=base_url()?>images/icon-previous.png" title="Move to left" /><img src="<?=base_url()?>images/icon-next.png" title="Move to right" />
				<?php } else if ($i==0) { ?>
                    <img src="<?=base_url()?>images/icon-previous.png" title="Move to left" /><a href="<?=base_url()?>admin/cms/reorder/<?=$photos[$i]['id']?>/1"><img src="<?=base_url()?>images/icon-next.png" title="Move to right" /></a>
				<?php } else if ($i==(count($photos) -1)) { ?>
                	<a href="<?=base_url()?>admin/cms/reorder/<?=$photos[$i]['id']?>/-1"><img src="<?=base_url()?>images/icon-previous.png" title="Move to left" /></a><img src="<?=base_url()?>images/icon-next.png" title="Move to right" />
                <?php } else { ?>
                    <a href="<?=base_url()?>admin/cms/reorder/<?=$photos[$i]['id']?>/-1"><img src="<?=base_url()?>images/icon-previous.png" title="Move to left" /></a><a href="<?=base_url()?>admin/cms/reorder/<?=$photos[$i]['id']?>/1"><img src="<?=base_url()?>images/icon-next.png" title="Move to right" /></a>
				<?php } ?>
                </div>
            </div>
        <?php } ?>
        </div>
        <div class="gallery-end"></div>
    </div>
	
</div>