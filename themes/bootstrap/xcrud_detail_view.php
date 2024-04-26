
			<div class="row">
							<div class="col">
								<section class="card">
									<div class="card-body">

<?php echo $this->render_table_name($mode); ?>
<div class="xcrud-top-actions btn-group">
    <?php 
    echo $this->render_button('save_return','save','list','btn btn-success','fas fa-save','create,edit');
 //   echo $this->render_button('save_new','save','create','btn btn-danger','','create,edit');
   // echo $this->render_button('save_edit','save','edit','btn btn-primary','','create,edit');
    echo $this->render_button('return','list','','btn btn-warning','fas fa-caret-left'); ?>
</div>
<div class="xcrud-view">
<?php echo $mode == 'view' ? $this->render_fields_list($mode,array('tag'=>'table','class'=>'table')) : $this->render_fields_list($mode,'div','div','label','div'); ?>
</div>
<div class="xcrud-nav">
    <?php echo $this->render_benchmark(); ?>
</div>
									</div>
											</section>		</div></div>