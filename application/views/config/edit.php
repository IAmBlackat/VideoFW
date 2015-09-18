<form action="<?php echo base_url() ?>config/update" method="POST" name="_frmSave">
	<fieldset>
		<legend><strong>Edit config</strong></legend>
		<table style="width: 100%;border: 1px #000">
			<tbody>
				<tr>
					<td>Key <span style="color: red">*</span></td>
					<td><input size=50 type="text" readonly="true" name="key" id="key" value="<?php echo empty($key) ? '' : $key ?>"/></td>
				</tr>
				<tr>
					<td>Value <span style="color: red">*</span></td>
					<td><input size=50 type="text" name="value" id="value" value="<?php echo empty($value) ? '' : $value ?>"/></td>
				</tr>
				<tr>
					<td>Is Active</td>
					<td><input type="checkbox" <?php echo empty($is_active) ? '' : 'checked' ?> name="is_active" id="is_active"/></td>
				</tr>

			</tbody>
		</table>
	</fieldset>
	<fieldset style="border:0px;text-align: left">
		<input type="hidden" name="id" value="<?php echo $id?>"/>
		<input type="submit" value="Submit" class="button"/>
		<input type="button" class="button" value="Cancel" onclick="window.location='<?php echo site_url("config") ?>'"/>
	</fieldset>
</form>