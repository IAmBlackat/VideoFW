<!DOCTYPE html>
<html lang="en">
  <?php $this->load->view('templates/head'); ?>
  <body>
    <?php $this->load->view('templates/header'); ?>

    <?php $this->load->view('templates/top_menu'); ?>

    <div class="wrap-body">
      <?php echo $content_for_layout ?>
    </div>

    <?php $this->load->view('templates/footer'); ?>
  </body>
</html>
