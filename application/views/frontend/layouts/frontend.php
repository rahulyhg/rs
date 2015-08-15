<!DOCTYPE HTML>
<html>
	<head>
	    
		    <?php include_title(); ?>
        <?php include_metas(); ?>
        <?php include_links(); ?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <?php include_stylesheets(); ?>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,800italic,800,700,700italic,600italic,600,400italic,300italic' rel='stylesheet' type='text/css'>
        <?php include_raws() ?>
        
        <script>
  			//declare global JS variables here
  			var base_url = '<?php echo base_url();?>';
  			var current_controller = '<?php echo $this->uri->segment(1, 'index');?>';
  			var current_method = '<?php echo $this->uri->segment(2, 'index');?>';			
		    </script>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <?php include_javascripts(); ?>
        
        
	</head>


	<body onload="MM_preloadImages('<?php echo include_img_path();?>/facebook_icon_o.png','<?php echo include_img_path();?>/twitter_icon_o.png','<?php echo include_img_path();?>/insta_icon_o.png','<?php echo include_img_path();?>/addres_icon_o.png','<?php echo include_img_path();?>/mail_icon_o.png','<?php echo include_img_path();?>/phone_icon_o.png')">

  	<div id="wrapper">
	    <div id="main">
			<?php echo $this->load->view('frontend/_partials/header', $this->data); ?>

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
		
		
		
	</body>
</html>
