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
			var current_controller = '<?php echo $this->uri->segment(1, 'index');?>';
			var current_method = '<?php echo $this->uri->segment(2, 'index');?>';			
		</script>
        
        
	</head>


	<body >

		<?php echo $this->load->view('frontend/_partials/header', $this->data); ?>

		<div class="container">
			<div class="row">
				
				<?php if(!$this->uri->segment(2) == 'user_login' || $this->uri->segment(2) == 'user_profile' || $this->uri->segment(2) == 'user_settings' || $this->uri->segment(2) == 'product_detail' || $this->uri->segment(2) == 'list_like_product' || $this->uri->segment(2) == 'list_fav_product' || $this->uri->segment(2) == 'followers_user_list' || $this->uri->segment(2) == 'following_user_list' || $this->uri->segment(2) == 'list_collection_product' || $this->uri->segment(2) == 'search_result' || $this->uri->segment(2) == 'most_popular' || $this->uri->segment(2) == 'upcomming_auctions' || $this->uri->segment(2) == 'recent_view' || $this->uri->segment(2) == 'edit_collection' || $this->uri->segment(2) == 'directory_list' || $this->uri->segment(2) == 'auction_calender'  ) { ?>
				<?php echo $this->load->view('frontend/_partials/left_menu', $this->data); ?>
				<?php } ?>
				<?php echo $content; ?>
			</div>
		</div>
		</div>

		<?php echo $this->load->view('frontend/_partials/footer'); ?>

		
		<!-- javascript
	    ================================================== -->
	    <!-- Placed at the end of the document so the pages load faster -->
	    

	    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
		
		

		<?php include_javascripts(); ?>
		
		<?php 
		
			if(is_array($this->init_scripts))
			{
				foreach ($this->init_scripts as $file)
					$this->load->view($file, $this->data);
			}
		?>
		
		
		<script>
$.fn.extend({
    treed: function (o) {
      
      var openedClass = 'glyphicon-minus-sign';
      var closedClass = 'glyphicon-plus-sign';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
      tree.find('.branch .indicator').each(function(){
        $(this).on('click', function () {
            $(this).closest('li').click();
        });
      });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('#tree1').treed();

$('#tree2').treed({openedClass:'glyphicon-folder-open', closedClass:'glyphicon-folder-close'});

$('#tree3').treed({openedClass:'glyphicon-chevron-right', closedClass:'glyphicon-chevron-down'});

</script>

		<script>
	$.getScript('http://arshaw.com/js/fullcalendar-1.6.4/fullcalendar/fullcalendar.min.js',function(){
  
  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();
  var ev = <?php  echo $this->data['event']?>;
  
  $('#calendar').fullCalendar({
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    editable: true,
    events: ev
  });
})
	</script>
		
		
		
		
	</body>
</html>
