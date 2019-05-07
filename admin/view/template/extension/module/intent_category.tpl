<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>"
                        class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
                   class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured"
                      class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?php echo $name; ?>"
                                   placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control"/>
                            <?php if ($error_name) { ?>
                            <div class="text-danger"><?php echo $error_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">Статус</label>
                            <div class="col-sm-10">
                                <select name="status" id="input-status" class="form-control">
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

                    <table id="categories-value" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-left">Категория</td>
                            <td class="text-left">Товары</td>
                            <td class="text-left"></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; ?>
                        <?php  foreach ($categories as $category) { ?>

                        <tr id="row-<?php echo $i; ?>">
                            <td>
                                <div class="col-sm-10 form-group">
                                    <div class="custom_block">
                                        <div class="custom_in">
                                        <label class="col-sm-2 control-label" for="category_name">Имя</label>
                                        <input type="text" name="category[<?php echo $i; ?>][name]"
                                               value="<?php echo $category['name']; ?>" class="form-control"/>
                                        </div>
                                        <div class="custom_in">
                                        <label class="col-sm-2 control-label" for="category_href">Ссылка</label>
                                        <input type="text" name="category[<?php echo $i; ?>][href]"
                                               value="<?php echo $category['href']; ?>" class="form-control"/>
                                        </div>
                                        <div class="custom_in">


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="input-image">логотип</label>
                                                <div class="col-sm-10"> <a href="" id="thumb-image<?php echo $i; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $category['thumb']; ?>" /></a>
                                                    <input type="hidden" name="category[<?php echo $i; ?>][image]" value="<?php echo $category['image']; ?>" id="input-image<?php echo $i ?>" />
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td>
                                <div class="form-group custom_parent">
                                    <?php $s = 1; ?>
                                    <?php foreach ($category['sub_categories'] as $sub_category) { ?>
                                    <div id="<?php echo $s; ?>" class="custom_block sub_cat">
                                        <div class="custom_in">
                                            <label class="col-sm-2 control-label" for="subcategory_name">Имя</label>
                                            <input type="text" name="category[<?php echo $i; ?>][sub_categories][<?php echo $s; ?>][sub_name]"  value="<?php echo $sub_category['sub_name']; ?>" class="form-control"/>
                                        </div>
                                        <div class="custom_in">
                                            <label class="col-sm-2 control-label" for="subcategory_href">Ссылка</label>
                                            <input type="text" name="category[<?php echo $i; ?>][sub_categories][<?php echo $s; ?>][sub_href]"  value="<?php echo $sub_category['sub_href']; ?>" class="form-control"/>
                                        </div>

                                        <button type="button"  onclick="removeSub(this);" data-toggle="tooltip" class="custom_remove">
                                            <i class="fa fa-minus-circle"></i></button>
                                    </div>
                                <?php $s++; } ?>
                                <div class="custom_in custom_in-button">
                                    <button type="button" onclick="addsubcategory(this);" data-toggle="tooltip"
                                            class="btn btn-primary "><i class="fa fa-plus-circle"></i></button>
                                </div>
                                </div>

                            </td>

                            <td class="text-left">
                                <button type="button"  onclick="$('#row-<?php echo $i; ?>').remove();" data-toggle="tooltip" class="btn btn-danger remove">
                                    <i class="fa fa-minus-circle"></i></button>
                            </td>
                        </tr>
                        <?php $i++; }?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-left">
                                <button type="button" onclick="addcategoryValue();" data-toggle="tooltip"
                                        class="btn btn-primary "><i class="fa fa-plus-circle"></i></button>
                            </td>
                        </tr>
                        </tfoot>

        </table>
        </form>
    </div>
</div>
</div>
<script type="text/javascript"><!--

    function  addsubcategory(that) {

      var array = [];
        $(that.closest('.form-group')).find('.sub_cat').each(function (index,value) {
            array.push(($(value).attr('id')));
        });
        var iterrator
        if(array.length > 0){
            iterrator = Math.max.apply(null, array) + 1;
        }else {
            iterrator = 1;
        }

        var row = $(that).closest('tr').attr('id');
        row = parseInt(row.split('-')[1]);

        var html;
        html = '<div id="'+iterrator+'" class="custom_block sub_cat">';
        html  += '<div class="custom_in">';
        html  += '<label class="col-sm-2 control-label" for="subcategory_name">Имя</label>';
        html  += '<input type="text" name="category[' + row + '][sub_categories][' + iterrator + '][sub_name]"  value="" class="form-control"/>';
        html  += '</div>';
        html  += '<div class="custom_in">';
        html  += '<label class="col-sm-2 control-label" for="subcategory_href">Ссылка</label>';
        html  += '<input type="text" name="category[' + row + '][sub_categories][' + iterrator + '][sub_href]"  value="" class="form-control"/>';
        html  += '</div>';
        html  += '<button type="button"  onclick="removeSub(this);" data-toggle="tooltip" class="custom_remove">\n' +
            '                                            <i class="fa fa-minus-circle"></i></button>';
        html  += '</div>';
        $(that.closest('.form-group')).append(html);
    }


    function  removeSub(that) {
        $(that.closest('.sub_cat')).remove();
    }

    var category_row = <?php echo $i; ?>;


    function addcategoryValue() {
        var request = $.ajax({
            url: 'index.php?route=extension/module/intent_category/ajaxHtmlHelper&row_id='+ category_row +'&action=addrow&token=<?php echo $token; ?>',
            dataType: 'html'
        });
        request.done(function (response) {
            $('#categories-value tbody').append(response);
            category_row++;
        })

    }
    $('.remove').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });

    //--></script></div>
<?php echo $footer; ?>
