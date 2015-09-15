<fieldset>
	<legend><strong>Config detail</strong></legend>
	<table style="width: 100%;border: 1px #000">
		<tbody>
			<tr>
				<td>Key</td>
				<td><?php echo empty($key) ? '' : $key ?></td>
			</tr>
			<tr>
				<td>Value</td>
				<td><?php echo empty($value) ? '' : $value ?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td><?php echo $status==STATUS_SHOW ? 'Show' : 'Hide' ?></td>
			</tr>
		</tbody>
	</table>
</fieldset>
<fieldset style="border:0px;text-align: left">
<input type="button" class="button" value="Edit" onclick="window.location='<?php echo base_url() ?>config/edit?id=<?php echo $id?>'"/>
<input type="button" class="button" value="List" onclick="window.location='<?php echo base_url() ?>config/index'"/>

</fieldset>
