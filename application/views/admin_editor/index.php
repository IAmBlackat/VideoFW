<input type="button" class="button" value="List box item" onclick="window.location = '<?php echo site_url("admin_editor/list_box_item") ?>'"/>
Total Item(s): <?php echo $total?>
<table class="stats" style="width: 100%;">
  <thead>
  <th>No.</th>
  <th>Key</th>
  <th>Name</th>
  <th>Description</th>
  <th>Link</th>
  <th></th>
</thead>

<?php if (!empty($listObject)): ?>
  <?php foreach ($listObject as $i => $object): ?>
    <tr>
      <td><?php echo $i + 1 + $offset ?></td>
      <td><?php echo $object['key'] ?></td>
      <td><a href="<?php echo base_url() ?>admin_editor/show?id=<?php echo $object['id'] ?>"><?php echo $object['name'] ?></a></td>
      <td><?php echo subString($object['description'],200) ?></td>
      <td><?php echo $object['link'] ?></td>
      <td><a href="<?php echo base_url() ?>admin_editor/edit?id=<?php echo $object['id'] ?>">Edit</a></td>
      <td>
        <a href="#">
          <input type="button" class="button" value="Delete"
                 onclick="if (confirm('Are you sure delete this?')) {
                       window.location = '<?php echo base_url() ?>admin_editor/delete?id=<?php echo $object['id'] ?>';
                           } else
                             return false;"/>
        </a>
      </td>
    </tr>
  <?php endforeach; ?>
<?php endif; ?>
</table>
<input type="button" class="button" value="Create" onclick="window.location = '<?php echo site_url("admin_editor/create") ?>'"/>
<div class="paging">
  <?php $this->load->view("templates/paging", array('total' => $total, 'max' => $max, 'offset' => $offset)); ?>
</div>