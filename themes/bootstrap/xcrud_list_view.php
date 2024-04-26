			<div class="row">
							<div class="col">
								<section class="card">
									<div class="card-body">
									<?php echo $this->render_table_name(); ?>

<?php if ($this->is_create or $this->is_csv or $this->is_print){?>
        <div class="xcrud-top-actions">

	
            <div class="btn-group pull-right">
				
                <?php echo $this->print_button('btn btn-primary','fas fa-print');
                echo $this->csv_button('btn btn-success','fas fa-file-excel'); ?>
				 <?php echo $this->render_search(); ?>
            </div>
          <?php echo $this->add_button('btn btn-success','fas fa-plus'); ?>
			
			   
            <div class="clearfix"></div>
        </div>
<?php } ?>
        <div class="xcrud-list-container">
        <table class="xcrud-list table table-striped table-hover table-bordered">
            <thead>
                <?php echo $this->render_grid_head('tr', 'th'); ?>
            </thead>
            <tbody>
                <?php echo $this->render_grid_body('tr', 'td'); ?>
            </tbody>
            <tfoot>
                <?php echo $this->render_grid_footer('tr', 'td'); ?>
            </tfoot>
			
        </table>
        </div>
        <div class="xcrud-nav">
    		<div class="btn-group pull-left">
				<?php echo $this->render_limitlist(true); ?>
            </div>
					<div class="btn-group pull-right">
			<div class="dataTables_paginate paging_simple_numbers" id="datatable-tabletools_paginate">
            <?php echo $this->render_pagination(); ?>
			</div>	
            </div>
	        </div>   
				</section>		
			
        
            <?php echo $this->render_benchmark(); ?>
        </div>  </div>  </div> 
   