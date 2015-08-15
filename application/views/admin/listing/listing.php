	
	<input type="hidden" name="bulk_all" id="bulk_all" value="0" />
	<input type="hidden" name="bulk_action" id="bulk_action" value="" />
	<input type="hidden" name="cur_page" id="cur_page" value="<?php echo $cur_page;?>" />
	<input type="hidden" name="base_url" id="base_url" value="<?php echo $base_url;?>" />
	<input type="hidden" name="namespace" id="namespace" value="<?php echo $namespace;?>" />
	
		
		

	<table class="table user-list table-hover"	id="data_table">

		<thead >
			<tr>
				<th data-priority="1">
					<div class="btn-group">
					<button title="" type="button" class="btn btn-primary dropdown-toggle has-tooltip" data-toggle="dropdown" data-original-title="Select" aria-expanded="false">
					<i class="fa fa-square-o"></i> <span class="caret"></span>
					</button>
						<ul class="dropdown-menu">
							<li><a href="#">Select All</a></li>
							<li><a href="#">Select Only this view</a></li>
							<li><a href="#">UnSelect All</a></li>
						</ul>
				</div>
				</th>
				<?php  $cols = 0; foreach ($fields as $field => $values):$cols++;?>
				<?php if($values['default_view'] == '0') continue; ?>
				<th data-priority="1">
				<a href="<?php echo $base_url.$cur_page.'/'.$field.'/';?><?php echo Listing::reverse_direction($direction); ?>" data-original-title="Click to sort" data-toggle="tooltip" data-placement="top" title="Click to sort">
					<?php echo $values['name'];?> 
				</a>
				
				<?php if(strcmp($order,$field) === 0): $arrow_icon = (strcmp($direction, 'ASC') === 0)?'up_sort':'down_sort';?>
					
					 <div class="sort_group">

						<a style="display:<?php echo strcmp($arrow_icon, 'up_sort') === 0?'block':'none';?>" href="<?php echo $base_url.$cur_page.'/'.$field.'/';?><?php echo Listing::reverse_direction($direction); ?>">
							<i class="up_sort m_top_15"></i>
						</a>

						<a style="display:<?php echo strcmp($arrow_icon, 'down_sort') === 0?'block':'none';?>" href="<?php echo $base_url.$cur_page.'/'.$field.'/';?><?php echo Listing::reverse_direction($direction); ?>">
							<i class="down_sort m_top_15"></i>
						</a>
						
					</div>  
				<?php else:?>
					
				<?php endif;?>
				</th>
				<?php endforeach;?>
				<th>Action</th>
			</tr>
		</thead>

		<tbody class="white_bg">
			<?php if(count($list)):?>

			<?php foreach ($list as $item) : ?>
			<tr id="sales_list">
				<td><?php echo form_checkbox("op_select[]", $item['id'], '', "id='op_select{$item['id']}'"); ?>
				</td>

				<?php foreach ($fields as $field => $row):?>
				<?php if($row['default_view'] == '0') continue; ?>
				<td>
					<?php echo displayData($item[$field], $row['data_type'], $item);?>
				</td>
				<?php endforeach;?>

				<td>
					<?php if(strcmp($listing_action, '') === 0):?>
					<a class="btn btn-small" href="<?php echo site_url($this->uri->segment(1, 'index')."/view/". $item['id']);?>"
						data-placement="top" data-toggle="tooltip"
						data-original-title="view"> <i class="icon-eye-open"></i>
					</a>
					<?php else:?>
						
						<?php 
							echo $this->parser->parse_string($listing_action, $item, TRUE);
						?>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach; ?>

			<?php else:?>
			<tr>
				<td colspan="<?php echo $cols+2;?>">
					<h2 class="text-center ">No records found.</h2>
				</td>
			</tr>
			<?php endif;?>
		</tbody>

	</table>

	<div class="pagination pagination-right pull-right" id="pagination">
			<?php echo $pagination ?>
	</div>

