<form action="<?php echo base_url() ?>admin_editor/save" method="POST" enctype="multipart/form-data" name="_frmSave">
  <fieldset>
    <legend><strong>Create box</strong></legend>
    <table style="width: 100%;border: 1px #000">
      <tbody>
        <tr>
          <td>Key <span style="color: red">*</span></td>
          <td><input type="text" name="key" style="width: 55%" value="<?php echo empty($key) ? '' : $key ?>"/></td>
        </tr>
        <tr>
          <td>Name <span style="color: red">*</span></td>
          <td><input type="text" name="name" style="width: 55%" value="<?php echo empty($name) ? '' : $name ?>"/></td>
        </tr>

        <tr>
          <td>Description</td>
          <td><textarea rows="5" cols="90" name="description"><?php echo empty($description) ? '' : $description ?></textarea></td>
        </tr>
        <tr>
          <td>Link</td>
          <td><input type="text" name="link" style="width: 55%" value="<?php echo empty($link) ? '' : $link ?>"/></td>
        </tr>
      </tbody>
    </table>
  </fieldset>
  <fieldset style="border:0px;text-align: left">
    <input type="submit" value="Submit" class="button"/>
    <a href="<?php echo site_url("admin_serie") ?>">
      <input type="button" class="button" value="Cancel"
             onclick="window.location = '<?php echo site_url("admin_editor/create") ?>'"/>
    </a>
  </fieldset>
</form>