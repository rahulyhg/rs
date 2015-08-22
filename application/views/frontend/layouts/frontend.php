<!DOCTYPE HTML>
<html>
	<head>
	    
		<?php include_title(); ?>
        <?php include_metas(); ?>
        <?php include_links(); ?>
        <?php include_stylesheets(); ?>
        <?php include_raws() ?>
        
        <script>
  			//declare global JS variables here
  			var base_url = '<?php echo base_url();?>';
  			var site_url = '<?php echo site_url();?>';
  			var current_controller = '<?php echo $this->uri->segment(1, 'index');?>';
  			var current_method = '<?php echo $this->uri->segment(2, 'index');?>';			
		    </script>

        

        <?php include_javascripts(); ?>
        
        
	</head>


	<body onload="MM_preloadImages('<?php echo include_img_path();?>/facebook_icon_o.png','<?php echo include_img_path();?>/twitter_icon_o.png','<?php echo include_img_path();?>/insta_icon_o.png','<?php echo include_img_path();?>/addres_icon_o.png','<?php echo include_img_path();?>/mail_icon_o.png','<?php echo include_img_path();?>/phone_icon_o.png')">

  	<div id="wrapper">
	    <div id="main">
			<?php echo $this->load->view($this->header, $this->data); ?>

			<?php if ($message = $this->service_message->render()) :?>
      			<?php echo $message;?>
      		<?php endif; ?>
					
			<?php echo $content; ?>
				

			<?php echo $this->load->view('frontend/_partials/footer'); ?>

		</div>
	</div>
		

		
		
	<?php 
	
		if(is_array($this->init_scripts))
		{
			foreach ($this->init_scripts as $file)
				$this->load->view($file, $this->data);
		}
	?>
		
	<script type="text/javascript">
		<!--
		function MM_preloadImages() { //v3.0
		  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
		    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
		    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
		}

		function MM_swapImgRestore() { //v3.0
		  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
		}

		function MM_findObj(n, d) { //v4.01
		  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
		  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
		  if(!x && d.getElementById) x=d.getElementById(n); return x;
		}

		function MM_swapImage() { //v3.0
		  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
		   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
		}
		//-->
	</script>
		
	<script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places"></script>
	</body>
</html>
