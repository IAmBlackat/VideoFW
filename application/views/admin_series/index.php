
<form id="frm_search" action="<?=site_url('admin_series')?>" method="post">
<label><b>Keyword: </b></label>
<input name="keyword" type="text" value="<?=isset($params['keyword'])?$params['keyword']:''?>">
<label><b>Video Type: </b></label>
<?php echo $typeSelectbox?>
<label><b>Status: </b></label>
<?php echo $statusSelectbox?>
<label><b>Country: </b></label>
<?php echo $countrySelectbox?>
<input type="submit" name="search" value="Search"/>
</form>
Total Item(s): <?php echo $total?>
<fieldset style="border:0px;text-align: left">
	<input id="series_approve_checked" data-command="approve" type="button" class="button" value="Show All (Checked)"/>
	<input id="series_hide_checked" data-command="hide" type="button" class="button" value="Hide All (Checked)"/>
</fieldset>
<table class="stats" style="width: 100%;">
  <thead>
  <th><input class="checkall" id="checkall" type="checkbox"></th>
  <th>No.</th>
  <th>Thumbnail</th>  
  <th>Title</th>  
  <th>Description</th>
  <th>Status</th>
  <th>Country</th>
  <th>Type</th>
  <th>Is Complete</th>
  <th></th>
</thead>

<?php if (!empty($listObject)): ?>
  <?php foreach ($listObject as $i => $object): ?>
    <tr>
      <td><input class="cb" type="checkbox" value="<?php echo $object['id'] ?>"></td>
      <td><?php echo $i + 1 + $offset ?></td>
      <td><img height="100" src="<?php echo getThumbnail($object['thumbnail'], 'series') ?>"></td>
      <td><a href="<?php echo base_url() ?>admin_series/show?id=<?php echo $object['id'] ?>"><?php echo $object['title'] ?></a></td>
      <td><?php echo subString($object['description'],200) ?></td>
      <td><?php echo getStrStatus($object['status']) ?></td>
      <td><?php echo isset($countries[$object['country']]) ? $countries[$object['country']] : "Other" ?></td>
      <td><?php echo $video_type[$object['type']] ?></td>
      <td><?php echo $object['is_complete'] ? 'Completed' : 'OnGoing' ?></td>
      <td><a href="<?php echo base_url() ?>admin_series/edit?id=<?php echo $object['id'] ?>">Edit</a></td>
      <td>
        <a href="#">
          <input type="button" class="button" value="Delete"
                 onclick="if (confirm('Are you sure delete this?')) {
                       window.location = '<?php echo base_url() ?>admin_series/delete?id=<?php echo $object['id'] ?>';
                           } else
                             return false;"/>
        </a>
      </td>
    </tr>
  <?php endforeach; ?>
<?php endif; ?>
</table>
<input type="button" class="button" value="Create" onclick="window.location = '<?php echo site_url("admin_series/create") ?>'"/>
<div class="paging">
  <?php $this->load->view("templates/paging", array('total' => $total, 'max' => $max, 'offset' => $offset)); ?>
</div>