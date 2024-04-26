<?php echo $this->render_table_name($mode); ?>
<div class="xcrud-top-actions">
    <?php echo $this->render_button('save_new','save','create','xcrud-button xcrud-blue','','create,edit') ?>
    <?php echo $this->render_button('save_edit','save','edit','xcrud-button xcrud-green','','create,edit') ?>
    <?php echo $this->render_button('save_return','save','list','xcrud-button xcrud-purple','','create,edit') ?>
    <?php echo $this->render_button('return','list','','xcrud-button xcrud-orange') ?>
</div>
<div class="xcrud-view">
<?php echo $this->render_fields_list($mode); ?>
</div>
<div class="xcrud-nav">
    <?php echo $this->render_benchmark(); ?>
</div>
