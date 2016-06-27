<div class="form-group has-feedback">
      <label for="inputFromName" class="control-label col-sm-3"><?php echo ENTRY_NAME; ?></label>
      <div class="col-sm-<?php echo $content_width?>">
        <?php
        echo tep_draw_input_field('name', NULL, 'required autofocus="autofocus" aria-required="true" id="inputFromName" placeholder="' . ENTRY_NAME_TEXT . '"');
        echo FORM_REQUIRED_INPUT;
        ?>
      </div>
    </div>
    <div class="form-group has-feedback">
      <label for="inputFromEmail" class="control-label col-sm-3"><?php echo ENTRY_EMAIL; ?></label>
      <div class="col-sm-<?php echo $content_width?>">
        <?php
        echo tep_draw_input_field('email', NULL, 'required aria-required="true" id="inputFromEmail" placeholder="' . ENTRY_EMAIL_ADDRESS_TEXT . '"', 'email');
        echo FORM_REQUIRED_INPUT;
        ?>
      </div>
    </div>