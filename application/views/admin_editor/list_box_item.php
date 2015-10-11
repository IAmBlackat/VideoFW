<input type="button" class="button" value="List box" onclick="window.location = '<?php echo site_url("admin_editor") ?>'"/>
Total Item(s): <?php echo $total?>
<table class="stats" style="width: 100%;">
  <thead>
  <th>No.</th>
  <th>Thumbnail</th>
  <th>Text</th>
  <th>Link</th>
  <th>Order</th>
  <th>Box</th>
  <th></th>
</thead>

<?php if (!empty($listObject)): ?>
  <?php foreach ($listObject as $i => $object): ?>
    <tr>
      <td><?php echo $i + 1 + $offset ?></td>
      <td><img height="100" src="<?php echo getThumbnail($object['item_thumbnail'], 'editor') ?>"></td>
      <td><?php echo $object['item_text'] ?></td>
      <td><?php echo $object['item_link'] ?></td>
      <td><?php echo $object['order'] ?></td>
      <td><a href="<?php echo base_url() ?>admin_editor/edit?id=<?php echo $object['box_id'] ?>"><?php echo $object['box_name'] ?></a></td>
      <td><a href="<?php echo base_url() ?>admin_editor/edit_box_item?id=<?php echo $object['id'] ?>">Edit</a></td>
      <td>
        <a href="#">
          <input type="button" class="button" value="Delete"
                 onclick="if (confirm('Are you sure delete this?')) {
                       window.location = '<?php echo base_url() ?>admin_editor/delete_box_item?id=<?php echo $object['id'] ?>';
                           } else
                             return false;"/>
        </a>
      </td>
    </tr>
  <?php endforeach; ?>
<?php endif; ?>
</table>
<input type="button" class="button" value="Create" onclick="window.location = '<?php echo site_url("admin_editor/create_box_item") ?>'"/>
<div class="paging">
  <?php $this->load->view("templates/paging", array('total' => $total, 'max' => $max, 'offset' => $offset)); ?>
</div>