<div class="store" id="<?php echo $i ;?>">
    <div style="background-color: #4b86c7; width: 100%; height: 5px">
        </div>
    <div class="form-group">

    <button type="button" style="margin: 5px" onclick="remove(<?php echo $i ;?>);" class="btn btn-danger remove pull-right">
        <i class="fa fa-minus-circle "></i></button>
    </div>

    <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
        <div class="col-sm-10">
            <textarea type="text" name="address_append[<?php echo $i; ?>]" placeholder="<?php echo $entry_address; ?>" rows="5" id="input-address" class="form-control"><?php echo $address_append ?></textarea>
            <?php if ($error_address) { ?>
            <div class="text-danger"><?php echo $error_address; ?></div>
            <?php } ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-geocode"><span data-toggle="tooltip" data-container="#content" title="<?php echo $help_geocode; ?>"><?php echo $entry_geocode; ?></span></label>
        <div class="col-sm-10">
            <input type="text" name="geocode_append[<?php echo $i; ?>]" value="<?php echo $geocode_append ?>" placeholder="<?php echo $entry_geocode; ?>" id="input-geocode" class="form-control" />
        </div>
    </div>
    <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
        <div class="col-sm-10">
            <input type="text" name="telephone_append[<?php echo $i; ?>]" value="<?php echo $telephone_append ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
            <?php if ($error_telephone) { ?>
            <div class="text-danger"><?php echo $error_telephone; ?></div>
            <?php  } ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
        <div class="col-sm-10">
            <input type="text" name="fax_append[<?php echo $i; ?>]" value="<?php $fax_append ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
        <div class="col-sm-10">
            <input type="text" name="email_append[<?php echo $i; ?>]" value="<?php echo $email_append ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
        <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_append; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
            <input type="hidden" name="image_append[<?php echo $i; ?>]" value="<?php echo $image_append ?>" id="input-image" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-open"><span data-toggle="tooltip" data-container="#content" title="<?php echo $help_open; ?>"><?php echo $entry_open; ?></span></label>
        <div class="col-sm-10">
            <textarea name="open_append[<?php echo $i; ?>]" rows="5" placeholder="<?php echo $entry_open; ?>" id="input-open" class="form-control"><?php echo $open_append ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-comment"><span data-toggle="tooltip" data-container="#content" title="<?php echo $help_comment; ?>"><?php echo $entry_comment; ?></span></label>
        <div class="col-sm-10">
            <textarea name="comment_append[<?php echo $i; ?>]" rows="5" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control">
                <?php echo $comment_append ?></textarea>
        </div>
    </div>
</div>