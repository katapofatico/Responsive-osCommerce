<div class="form-group has-feedback">
    <label for="inputEnquiry" class="control-label col-sm-3"><?php echo ENTRY_ENQUIRY; ?></label>
    <div class="col-sm-<?php echo $content_width?>">
        <?php
        echo tep_draw_textarea_field('enquiry', 'soft', 50, 15, NULL, 'required aria-required="true" id="inputEnquiry" placeholder="' . ENTRY_ENQUIRY_TEXT . '"');
        echo FORM_REQUIRED_INPUT;
        ?>
    </div>
</div>