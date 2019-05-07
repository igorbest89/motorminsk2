<div class="job" id="<?php echo $i ;?>">



    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-job-name"><?php echo $entry_job_name; ?></label>
        <div class="col-sm-10">
            <input name="job_name[<?php echo $i; ?>]" value="" id="input-job-name[<?php echo $i; ?>]" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_job_description; ?></label>
        <div class="col-sm-10">
            <textarea name="description[<?php echo $i; ?>]" id="input-description[<?php echo $i; ?>]" class="form-control summernote"></textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-idescription"><?php echo $entry_status; ?></label>
        <div class="col-sm-10">
            <select name="status[<?php echo $i; ?>]" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <button type="button" style="margin: 5px" onclick="remove(<?php echo $i ;?>);" class="btn btn-danger remove pull-right">
            <i class="fa fa-minus-circle "></i></button>
    </div>
    <div style="background-color: #4b86c7; width: 100%; height: 5px"></div>
</div>

    <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
    <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
    <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>