<form action="<?php echo base_url() ?>admin_series/save" method="POST" enctype="multipart/form-data" name="_frmSave">
  <fieldset>
    <legend><strong>Create series</strong></legend>
    <table style="width: 100%;border: 1px #000">
      <tbody>
        <tr>
          <td>Title <span style="color: red">*</span></td>
          <td><input type="text" name="title" style="width: 55%" value="<?php echo empty($title) ? '' : $title ?>"/></td>
        </tr>
        <tr>
					<td>Description</td>
					<td><textarea rows="5" cols="90" name="description"><?php echo empty($description) ? '' : $description ?></textarea></td>
				</tr>
        <tr>
					<td>
            Thumbnail<span style="color: red">*</span>
          </td>
					<td><input type="file" name="thumbnail" id="thumbnail"/></td>
				</tr>
        <tr>
          <td>Country</td>
          <td>
            <?php echo $countrySelectbox?>
          </td>
        </tr>
        <tr>
          <td>Genre</td>
          <td>
            <?php echo $genreSelectbox?>
          </td>
        </tr>
        <tr>
          <td>Type</td>
          <td>
            <?php echo $typeSelectbox?>
          </td>
        </tr>
        
        <tr>
          <td>Status</td>
          <td>
            <?php echo $statusSelectbox?>
          </td>
        </tr>
        <tr>
          <td>Is Complete</td>
          <td><input type="checkbox" <?php echo empty($is_complete) ? '' : 'checked' ?> name="is_complete"/></td>
        </tr>
      </tbody>
    </table>
  </fieldset>
  <fieldset style="border:0px;text-align: left">
    <input type="submit" value="Submit" class="button"/>
    <a href="<?php echo site_url("admin_serie") ?>">
      <input type="button" class="button" value="Cancel" onclick="window.location = '<?php echo site_url("admin_series/create") ?>'"/>
    </a>
  </fieldset>
</form>