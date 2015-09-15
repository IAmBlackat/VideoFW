<form action="<?php echo base_url() ?>admin_editor/update_box_item" method="POST" enctype="multipart/form-data" name="_frmSave">
  <fieldset>
    <legend><strong>Create box</strong></legend>
    <table style="width: 100%;border: 1px #000">
      <tbody>

        <tr>
          <td>Box</td>
          <td><?php echo $boxSelectbox?></td>
        </tr>
        <tr>
          <td>Text <span style="color: red">*</span></td>
          <td><input type="text" name="item_text" style="width: 55%" value="<?php echo empty($item_text) ? '' : $item_text ?>"/></td>
        </tr>

        <tr>
          <td>Link <span style="color: red">*</span></td>
          <td><input type="text" name="item_link" style="width: 55%" value="<?php echo empty($item_link) ? '' : $item_link ?>"/></td>
        </tr>
        <tr>
          <td>
            <?php if($item_thumbnail):?>
              <img height ="100" src="<?php echo getThumbnail($item_thumbnail, 'editor') ?>">
            <?php else: ?>
              Image<span style="color: red">*</span>
            <?php endif;?>
          </td>
          <td><input type="file" name="item_thumbnail" id="item_thumbnail"/></td>
        </tr>
      </tbody>
    </table>
  </fieldset>
  <fieldset style="border:0px;text-align: left">
    <input type="hidden" name="id" value="<?php echo $id?>"/>
    <input type="submit" value="Submit" class="button"/>
    <a href="<?php echo site_url("admin_editor/create_box_item") ?>">
      <input type="button" class="button" value="Cancel"
             onclick="window.location = '<?php echo site_url("admin_editor/create_box_item") ?>'"/>
    </a>
  </fieldset>
</form>