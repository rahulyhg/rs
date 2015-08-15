<!DOCTYPE HTML>
<html>
	<head>
	    
		<?php include_title(); ?>
        <? include_metas(); ?>
        <? include_links(); ?>
        <? include_stylesheets(); ?>
        <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>
        <? include_raws() ?>
        
        <script>
			//declare global JS variables here
			var base_url = '<?php echo base_url();?>';
			var current_controller = '<?php echo $this->uri->segment(1, 'admin').'/'.$this->uri->segment(2, 'index');?>';
			var current_method = '<?php echo $this->uri->segment(3, 'index');?>';
			var namespace = '<?php echo $this->namespace;?>';
			var previous_url = '<?php echo $this->previous_url;?>';
		</script>
        
        
	</head>


	<?php if( !is_logged_in() ): ?>
	<body >
		<?php //$this->load->view('admin/_partials/header', $this->data); ?>
			
		<?php echo $content; ?>

		<?php $this->load->view('admin/_partials/footer'); ?>

		<? $this->load->view('admin/_partials/config_tools'); ?>
		
	<?php else: ?>
	<body >
		<div id="theme-wrapper">
			<?php $this->load->view('admin/_partials/header', $this->data); ?>
			<div id="page-wrapper" class="container">
			 	<div class="row">
					<?php $this->load->view('admin/_partials/left_menu', $this->data); ?>
						<?php echo $content; ?>
					<?php $this->load->view('admin/_partials/footer'); ?>
				</div>
			</div>
			<? $this->load->view('admin/_partials/config_tools'); ?>
		</div>
	<?php endif; ?>
	
		
		
		

		
		<!-- javascript
	    ================================================== -->
	    <!-- Placed at the end of the document so the pages load faster -->
	    

		<? include_javascripts(); ?>
		
		<?php 
		
			if(is_array($this->init_scripts))
			{
				foreach ($this->init_scripts as $file)
					$this->load->view($file, $this->data);
			}
		?>
	</body>
</html>
