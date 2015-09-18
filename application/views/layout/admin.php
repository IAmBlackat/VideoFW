<?php $this->load->view('admin/header'); ?>
<script type="text/javascript">
  var base_url = "<?php echo base_url()?>";
      $(function() {
        <?php if( isset($fields) && !empty($fields) ){
          foreach( $fields as $field ){
        ?>
          $( "#<?php echo $field?>" ).css( "border", "1px solid red" );
        <?php } } ?>
      });
    </script>
<body id="page1">
	<?php $this->load->view("admin/banner"); ?>
	<div id="content_1">
		<div class="content_1">
			<div class="inner">
				<div class="wrapper">
					<?php if (has_error()) { ?>
						<fieldset class="error">
							<?php echo flash_error() ?>
						</fieldset>
					<?php } ?>
					<?php if (has_message()) { ?>
						<fieldset class="message">
							<?php echo flash_message() ?>
						</fieldset>
					<?php } ?>
					<?php echo $content_for_layout ?>
				</div>
			</div>
		</div>
	</div>
</body>
<br>
<?php $this->load->view('admin/footer'); ?>