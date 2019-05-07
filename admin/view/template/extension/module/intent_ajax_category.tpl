<tr id="row-<?php echo $i; ?>">
    <td>
        <div class="col-sm-10 form-group">
            <div class="custom_block">
                <div class="custom_in">
                    <label class="col-sm-2 control-label" for="category_name">Имя</label>
                    <input type="text"  name="category[<?php echo $i; ?>][name]" value="" class="form-control"/>
                </div>
                <div class="custom_in">
                    <label class="col-sm-2 control-label" for="category_href">Ссылка</label>
                    <input type="text"  name="category[<?php echo $i; ?>][href]" value="" class="form-control"/>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-image">логотип</label>
                    <div class="col-sm-10"> <a href="" id="thumb-image<?php echo $i; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="" /></a>
                        <input type="hidden" name="category[<?php echo $i; ?>][image]" value="" id="input-image<?php echo $i?>" />
                    </div>
                </div>
            </div>
        </div>

    </td>
    <td>
        <div class="form-group custom_parent">
            <div id="1" class="custom_block sub_cat">
                <div class="custom_in">
               <label class="col-sm-2 control-label" for="subcategory_name">Имя</label>
                    <input type="text" name="category[<?php echo $i; ?>][sub_categories][0][sub_name]"  value="" class="form-control"/>
                </div>
                <div class="custom_in">
               <label class="col-sm-2 control-label" for="subcategory_href">Ссылка</label>
                    <input type="text" name="category[<?php echo $i; ?>][sub_categories][0][sub_href]"  value="" class="form-control"/>
                </div>
                <button type="button"  onclick="removeSub(this);" data-toggle="tooltip" class="custom_remove remove">
                    <i class="fa fa-minus-circle"></i></button>
            </div>
            <div id="custom_button_plus" class="custom_in custom_in-button">
                <button type="button" onclick="addsubcategory(this);" data-toggle="tooltip"
                        class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
            </div>
        </div>
    </td>

    <td class="text-left">
        <button type="button" onclick="$('#row-<?php echo $i; ?>').remove();" data-toggle="tooltip"
                class="btn btn-danger remove">
            <i class="fa fa-minus-circle"></i></button>
    </td>
</tr>