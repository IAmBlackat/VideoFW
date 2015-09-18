<table class="stats" style="width: 100%;">
	<thead>
	<th>No.</th>
	<th>Key</th>
	<th width=100>Value</th>
	<th>Status</th>
	<th></th>
</thead>

<?php if (!empty($listObject)): ?>
	<?php foreach ($listObject as $object): ?>
		<tr>
			<td><?php echo $object['id'] ?></td>
			<td><a href="<?php echo base_url() ?>config/show?id=<?php echo $object['id'] ?>"><?php echo $object['key'] ?></a></td>
			<td><?php echo $object['value'] ?></td>
			<td><?php echo $object['status'] == STATUS_SHOW ? 'Show' : 'Hide' ?></td>
			<td><a href="<?php echo base_url() ?>config/edit?id=<?php echo $object['id'] ?>">Edit</a></td>
			<td>
				<a href="#">
					<input type="button" class="button" value="Delete"
						   onclick="if(confirm('Are you sure delete this?')){
							 window.location='<?php echo base_url() ?>config/delete?id=<?php echo $object['id'] ?>'; } else return false;"/>
				  </a>
			</td>
		</tr>
	<?php endforeach; ?>
<?php endif; ?>
</table>
<input type="button" class="button" value="Create" onclick="window.location='<?php echo site_url("config/create") ?>'"/>