<form id="frm_search" action="<?=site_url('admin_video')?>" method="post">
<label><b>Keyword: </b></label>
<input name="keyword" type="text" value="<?=isset($params['keyword'])?$params['keyword']:''?>">
<label><b>Status: </b></label>
<?php echo $statusSelectbox?>
<input type="submit" name="search" value="Search"/>
</form>
Total Item(s): <?php echo $total?>
<fieldset style="border:0px;text-align: left">
	<input id="video_approve_checked" data-command="approve" type="button" class="button" value="Show All (Checked)"/>
	<input id="video_hide_checked" data-command="hide" type="button" class="button" value="Hide All (Checked)"/>
</fieldset>
<br>
<table class="stats" style="width: 100%;">
	<thead>
  <th><input class="checkall" id="checkall" type="checkbox"></th>
	<th>No.</th>
	<th>Title</th>
  <th>Episode</th>
	<th>Status</th>
  <th>Has Sub</th>
  <th>Publish Date</th>
	<th></th>
</thead>

<?php if (!empty($listObject)): ?>
	<?php foreach ($listObject as $i =>$object): ?>
		<tr>
      <td><input class="cb" type="checkbox" value="<?php echo $object['id'] ?>"></td>
			<td><?php echo $i + 1 + $offset ?></td>
			<td><a href="<?php echo base_url() ?>admin_video/show?id=<?php echo $object['id'] ?>"><?php echo $object['title'] ?></a></td>
      <td><?php echo $object['episode']; ?></td>
      <td><?php echo getStrStatus($object['status']); ?></td>
      <td><?php echo $object['has_sub'] ? 'YES' : 'NO (RAW)'  ?></td>
      <td><?php echo $object['publish_date']; ?></td>
			<td><a href="<?php echo base_url() ?>admin_video/edit?id=<?php echo $object['id'] ?>">Edit</a></td>
			<td>
				<a href="#">
					<input type="button" class="button" value="Delete"
						   onclick="if(confirm('Are you sure delete this?')){
							 window.location='<?php echo base_url() ?>admin_video/delete?id=<?php echo $object['id'] ?>'; } else return false;"/>
				  </a>
			</td>
		</tr>
	<?php endforeach; ?>
<?php endif; ?>
</table>
<input type="button" class="button" value="Create" onclick="window.location='<?php echo site_url("admin_video/create") ?>'"/>
<div class="paging">
	<?php $this->load->view("templates/paging",array('total'=>$total,'max'=>$max,'offset'=>$offset)); ?>
</div>