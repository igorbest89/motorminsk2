<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            <?php if ($error_name) { ?>
                            <div class="text-danger"><?php echo $error_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="table-responsive">
                            <table id="categories-value" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td class="text-left"><?php echo $entry_category; ?></td>
                                    <td class="text-right"><?php echo $entry_product; ?></td>
                                    <td class="text-left"><?php echo $entry_limit; ?></td>
                                    <td class="text-right"><?php echo $entry_width; ?></td>
                                    <td class="text-right"><?php echo $entry_height; ?></td>
                                    <td class="text-right"><?php echo $entry_status; ?></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $category_row = 0; ?>
                                <?php foreach ($categories_featured as $category_featured) { ?>
                                <tr id="category-value-row<?php echo $category_row; ?>">
                                    <td>
                                        <select name="categories_featured[<?php echo $category_row; ?>][category_name]">
                                            <?php foreach ($categories as $category) { ?>
                                            <?php if ($category_featured['category_name'] == $category['category_id']) { ?>
                                            <option value="<?php echo $category['category_id']; ?>" selected><?php echo $category['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="product_name" value="" onfocus="findProduct();" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                                        <div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($category_featured['products'] as $product) { ?>
                                            <div id="featured-product"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                                                <input type="hidden" name="categories_featured[<?php echo $category_row; ?>][products][]" value="<?php echo $product['product_id']; ?>" />
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td><input type="text" name="categories_featured[<?php echo $category_row; ?>][limit]" value="<?php echo $category_featured['limit']; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" /></td>
                                    <td><input type="text" name="categories_featured[<?php echo $category_row; ?>][width]" value="<?php echo $category_featured['width']; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                                        <?php if ($error_width) { ?>
                                        <div class="text-danger"><?php echo $error_width; ?></div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <input type="text" name="categories_featured[<?php echo $category_row; ?>][height]" value="<?php echo $category_featured['height']; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                                        <?php if ($error_height) { ?>
                                        <div class="text-danger"><?php echo $error_height; ?></div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="col-sm-10">
                                            <select name="categories_featured[<?php echo $category_row; ?>][status]" id="input-status" class="form-control">
                                                <?php if ($category_featured['status']) { ?>
                                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                <option value="0"><?php echo $text_disabled; ?></option>
                                                <?php } else { ?>
                                                <option value="1"><?php echo $text_enabled; ?></option>
                                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="text-left"><button type="button" onclick="$(this).tooltip('destroy');$('#category-value-row<?php echo $category_row; ?>').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                </tr>
                                <?php $category_row++; } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6"></td>
                                    <td class="text-left"><button type="button" onclick="addcategoryValue();" data-toggle="tooltip" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
        var category_row = <?php echo $category_row; ?>;
        function addcategoryValue() {
            html  = '<tr id="category-value-row' + category_row + '">';
            html  += '<td>';
            html  += '<select name="categories_featured[' + category_row + '][category_name]">';
        <?php foreach ($categories as $category) { ?>
                html  +='<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>';
            <?php } ?>
            html  +='</select>';
            html  +='</td>';
            html  +='<td>';
            html  +='<input type="text" name="product_name" value="" onfocus="findProduct();" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />';
            html  +='<div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">';
            html  +='<div>';
            html  +='</td>';
            html  +='<td><input type="text" name="categories_featured[' + category_row + '][limit]" value="" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" /></td>';
            html  +='<td><input type="text" name="categories_featured[' + category_row + '][width]" value="" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />';
        <?php if ($error_width) { ?>
                html  +='<div class="text-danger"><?php echo $error_width; ?></div>';
            <?php } ?>
            html  +='</td>';
            html  +='<td>';
            html  +='<input type="text" name="categories_featured[' + category_row + '][height]" value="" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />';
        <?php if ($error_height) { ?>
                html  +='<div class="text-danger"><?php echo $error_height; ?></div>';
            <?php } ?>
            html  +='</td>';
            html  +='<td>';
            html  +='<div class="col-sm-10">';
            html  +='<select name="categories_featured[' + category_row + '][status]" id="input-status" class="form-control">';
            html  +='<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
            html  +=' <option value="0"><?php echo $text_disabled; ?></option>';
            html  +='</select>';
            html  +='</div>';
            html  +='</td>';
            html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#category-value-row' + category_row + '\').remove();" data-toggle="tooltip" rel="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
            html  +='</tr>';
            $('#categories-value tbody').append(html);
            $('[rel=tooltip]').tooltip();
            category_row++;
        }
        function findProduct() {
            $('input[name=\'product_name\']').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                        dataType: 'json',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return {
                                    label: item['name'],
                                    value: item['product_id']
                                }
                            }));
                        }
                    });
                },
                select: function(item) {
                    $('input[name=\'product_name\']').val('');
                    $('#featured-product' + item['value']).remove();
                    $('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="categories_featured['+category_row+'][products][]" value="' + item['value'] + '" /></div>');
                }
            });
        }
        $('input[name=\'product_name\']').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['product_id']
                            }
                        }));
                    }
                });
            },
            select: function(item) {
                $('input[name=\'product_name\']').val('');
                $('#featured-product' + item['value']).remove();
                $('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="categories_featured['+category_row+'][products][]" value="' + item['value'] + '" /></div>');
            }
        });
        $('#featured-product').delegate('.fa-minus-circle', 'click', function() {
            $(this).parent().remove();
        });
        //--></script></div>
<?php echo $footer; ?>





