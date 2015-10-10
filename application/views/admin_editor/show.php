<fieldset>
  <legend><strong>Box detail</strong></legend>
  <table class="stats" style="width: 100%;border: 1px #000">

    <tbody>
      <tr>
        <td>Key</td>
        <td><?php echo empty($key) ? '' : $key ?></td>
      </tr>
      <tr>
        <td>Name</td>
        <td><?php echo empty($name) ? '' : $name ?></td>
      </tr>
      <tr>
        <td>Description</td>
        <td><?php echo empty($description) ? '' : $description ?></td>
      </tr>
      <tr>
        <td>Link</td>
        <td><?php echo empty($link) ? '' : $link ?></td>
      </tr>
    </tbody>
  </table>
</fieldset>
<fieldset>
  <legend><strong>Items of Box</strong></legend>
  <table class="stats" style="width: 100%">
    <thead>
      <th>No.</th>
      <th>Item Text</th>
      <th>Item Link</th>
      <th>Item Thumbnail</th>
    </thead>
    <tbody>
      <?php if (!empty($itemOfbox)): ?>
        <?php foreach ($itemOfbox as $n1 => $item): ?>
          <tr>
            <td><?php echo $n1 + 1 ?></td>
            <td><?php echo $item['item_text'] ?></td>
            <td><?php echo $item['item_link'] ?></td>
            <td><img src="<?php echo getThumbnail($item['item_thumbnail'], 'editor') ?>" /></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</fieldset>
<fieldset style="border:0px;text-align: left">
  <input type="hidden" id="box_id" value=<?php echo $id ?>>
  <input type="button" class="button" value="Edit" onclick="window.location = '<?php echo base_url() ?>admin_editor/edit?id=<?php echo $id ?>'"/>
  <input type="button" class="button" value="List" onclick="window.location = '<?php echo base_url() ?>admin_editor/index'"/>
</fieldset>
