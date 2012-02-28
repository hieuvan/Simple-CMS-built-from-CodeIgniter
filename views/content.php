 <div id="content_wrapper">
  <div class="page_title"><? echo $category['title'];?></div>
    <div class="page_content">
           <?php echo $page['content'];?>
    </div>
    <div id="photo_gallery">
       <?php 
	   // if gallery is attached and there is any photo in the gallery
	   if ($photos) 
	   { 
	   ?>
       <script type="text/javascript">
         $j(function() 
		 {
	        $j('#gallery_vertical a').lightBox();
         });
      </script>
      <div id="gallery_vertical">                                       
      <?php 
	    for($i=0;$i<count($photos);$i++) 
		{ 
		?>  
        <div style="margin: 0 0 15px;width: 138px;">
             <a <? if($i>=3) { echo 'style="display:none"';}?> href="<?=base_url()?>uploads/galleries/<?php print md5("cdkgallery".$photos[$i]['gallery_id']); ?>/<?php print $photos[$i]['name'];?>" title="<?=$photos[$i]['title']?>"><img src="<?=base_url()?>uploads/galleries/<?php print md5("cdkgallery".$photos[$i]['gallery_id']); ?>/thumbnails/<?php print $photos[$i]['name'];?>" /></a>
        </div>
      <?php
        } 
	   ?>
      </div>
	   <?
	   } // end if
	   ?>
    </div>
    <!-- end of photo gallery -->
   </div>
  <!-- end of content wrapper -->
<!-- end of page_wrapper-->
</div>
</body>
</html>