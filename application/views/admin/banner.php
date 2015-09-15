<div id="header">
  <div class="tail-top">
    <div class="tail-bottom">
      <div class="header">
        <div class="row-1">
          <ul>
            <li>
              <a href="<?php echo site_url('import_data') ?>"
                 <?php echo userdata('active_menu') == 'import_data' ? 'class="active"' : '' ?>>
                <em><b>Import</b></em>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url('admin_series').'?rand='.  uniqid('s') ?>"
                 <?php echo userdata('active_menu') == 'admin_series' ? 'class="active"' : '' ?>>
                <em><b>Series</b></em>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url('admin_video').'?rand='.  uniqid('v') ?>"
                 <?php echo userdata('active_menu') == 'admin_video' ? 'class="active"' : '' ?>>
                <em><b>Video</b></em>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url('admin_editor').'?rand='.  uniqid('e') ?>"
                 <?php echo userdata('active_menu') == 'admin_editor' ? 'class="active"' : '' ?>>
                <em><b>Editor</b></em>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url("config") ?>"
                 <?php echo userdata('active_menu') == 'config' ? 'class="active"' : '' ?> >
                <em><b>Configuration</b></em>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url("admin/logout") ?>" >
                <em><b>Logout</b></em>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>