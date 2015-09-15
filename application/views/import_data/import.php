<form action="<?php echo base_url() ?>import_data/save" method="POST" enctype="multipart/form-data" name="_frmSave">
  <fieldset>
    <legend><strong>Import Data</strong></legend>
    Page Support: dramacool.com
    <table style="width: 100%;border: 1px #000">
      <tbody>
        <tr>
          <td>Link<span style="color: red">*</span></td>
          <td>
            <textarea name="site_url" rows="3" cols="90"><?php echo empty($site_url) ? '' : $site_url ?></textarea>
          </td>
        </tr>
        <tr>
          <td>Link Type</td>
          <td>
            <?php echo $importTypeSelectbox?>
          </td>
        </tr>
        <tr>
          <td>Video Type</td>
          <td>
            <?php echo $typeSelectbox?>
          </td>
        </tr>
      </tbody>
    </table>
  </fieldset>
  <fieldset style="border:0px;text-align: left">
    <input type="submit" value="Submit" class="button"/>
    <a href="<?php echo site_url("import_data") ?>">
      <input type="button" class="button" value="Cancel" onclick="window.location = '<?php echo site_url("import_data") ?>'"/>
    </a>
  </fieldset>
</form>