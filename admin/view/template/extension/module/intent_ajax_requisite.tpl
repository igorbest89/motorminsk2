
<tr id="<?php echo $i; ?>">
    <td><div class="form-group">
            <div class="col-sm-10">
                <input type="text" name="requisite_name[<?php echo $i; ?>]" value="" placeholder="<?php echo $entry_requisite_name; ?>" id="input-requisites-name" class="form-control" />
                <?php if ($error_requisite_name) { ?>
                <div class="text-danger"><?php echo $error_requisite_name; ?></div>
                <?php } ?>
            </div>
        </div></td>

    <td><div class="form-group">
            <div class="col-sm-10">
                <input type="text" name="bic[<?php echo $i; ?>]" value="" placeholder="<?php echo $entry_bic; ?>" id="input-bic" class="form-control" />
                <?php if ($error_bic) { ?>
                <div class="text-danger"><?php echo $error_bic; ?></div>
                <?php } ?>
            </div>
        </div></td>

    <td><div class="form-group">
            <div class="col-sm-10">
                <input type="text" name="rs[<?php echo $i; ?>]" value="" placeholder="<?php echo $entry_rs; ?>" id="input-rs" class="form-control" />
                <?php if ($error_rs) { ?>
                <div class="text-danger"><?php echo $error_rs; ?></div>
                <?php } ?>
            </div>
        </div></td>

    <td><div class="form-group">
            <div class="col-sm-10"> <a href="" id="thumb-image<?php echo $i; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                <input type="hidden" name="image[<?php echo $i; ?>]" value="" id="input-image<?php echo $i; ?>" />
            </div>
        </div></td>

    <td><div class="form-group">
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
        </div></td>

    <td class="text-left">
        <button type="button" id="<?php echo $i; ?>" onclick="remove(this.id);" data-toggle="tooltip" class="btn btn-danger">
            <i class="fa fa-minus-circle"></i></button>
    </td>

</tr>