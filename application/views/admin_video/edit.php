<form action="<?php echo base_url() ?>admin_video/update" method="POST" enctype="multipart/form-data" name="_frmSave">
	<fieldset>
		<legend><strong>Edit video</strong></legend>
		<table style="width: 100%;border: 1px #000">
			<tbody>
				<tr>
					<td>Title<span style="color: red">*</span></td>
					<td><input size=50 type="text" name="title" id="title" value="<?php echo empty($title) ? '' : $title ?>"/></td>
				</tr>
        <tr>
					<td>Description</td>
          <td><textarea rows="5" cols="90" name="description" style="width: 80%"><?php echo empty($description)?'':$description?></textarea></td>
				</tr>
        <tr>
					<td>Episode</td>
					<td><input size=50 type="text" name="episode" id="episode" value="<?php echo empty($episode) ? '' : $episode ?>"/></td>
				</tr>
        <tr>
					<td>Series</td>
					<td><?php echo $seriesSelectbox?></td>
				</tr>
				<tr>
					<td>Status</td>
					<td><?php echo $statusSelectbox?></td>
				</tr>
        <tr>
					<td>Has Sub</td>
					<td><input type="checkbox" <?php echo empty($has_sub) ? '' : 'checked' ?> name="has_sub" id="has_sub"/></td>
				</tr>

			</tbody>
		</table>
	</fieldset>
	<fieldset style="border:0px;text-align: left">
		<input type="hidden" name="id" value="<?php echo $id?>"/>
		<input type="submit" value="Submit" class="button"/>
		<a href="<?php echo site_url("admin_video") ?>">
			<input type="button" class="button" value="Cancel" onclick="window.location='<?php echo site_url("admin_video/create") ?>'"/>
		</a>
	</fieldset>
</form>