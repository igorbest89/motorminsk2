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
                    <table id="categories-value" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr >
                                <td class="text-left"><?php echo $entry_requisite_name; ?></td>
                                <td class="text-left"><?php echo $entry_bic; ?></td>
                                <td class="text-left"><?php echo $entry_rs; ?></td>
                                <td class="text-left"><?php echo $entry_image; ?></td>
                                <td class="text-left"><?php echo $entry_status; ?></td>
                                <td class="text-left"></td>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach($module_info as $i => $requisite){ ?>
                            <tr id="<?php echo $i; ?>">
                                <td><div class="form-group">
                                    <div class="col-sm-10">
                                        <input type="text" name="requisite_name[<?php echo $i; ?>]" value="<?php echo $requisite['requisite_name']; ?>" placeholder="<?php echo $entry_requisite_name; ?>" id="input-requisites-name" class="form-control" />
                                        <?php if ($error_requisite_name) { ?>
                                        <div class="text-danger"><?php echo $error_requisite_name; ?></div>
                                        <?php } ?>
                                    </div>
                                </div></td>

                                <td><div class="form-group">
                                    <div class="col-sm-10">
                                        <input type="text" name="bic[<?php echo $i; ?>]" value="<?php echo $requisite['bic'] ?>" placeholder="<?php echo $entry_bic; ?>" id="input-bic" class="form-control" />
                                        <?php if ($error_bic) { ?>
                                        <div class="text-danger"><?php echo $error_bic; ?></div>
                                        <?php } ?>
                                    </div>
                                </div></td>

                                <td><div class="form-group">
                                    <div class="col-sm-10">
                                        <input type="text" name="rs[<?php echo $i; ?>]" value="<?php echo $requisite['rs']; ?>" placeholder="<?php echo $entry_rs; ?>" id="input-rs" class="form-control" />
                                        <?php if ($error_rs) { ?>
                                        <div class="text-danger"><?php echo $error_rs; ?></div>
                                        <?php } ?>
                                    </div>
                                </div></td>

                                <td><div class="form-group">
                                    <div class="col-sm-10"> <a href="" id="thumb-image<?php echo $i; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $requisite['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="image[<?php echo $i; ?>]" value="<?php echo $requisite['image']; ?>" id="input-image<?php echo $i; ?>" />
                                    </div>
                                </div></td>

                                <td><div class="form-group">
                                    <div class="col-sm-10">
                                        <select name="status[<?php echo $i; ?>]" id="input-status" class="form-control">
                                            <?php if ($requisite['status']) { ?>
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
                                    <button type="button" id="<?php echo $i; ?>" onclick="remove(this.id);"  data-toggle="tooltip" class="btn btn-danger">
                                        <i class="fa fa-minus-circle"></i></button>
                                </td>

                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5"></td>
                            <td class="text-left">
                                <button type="button" onclick="addRequisite();"  data-toggle="tooltip"
                                        class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--

        var category_row = <?php echo $i; ?>;

        function addRequisite() {

            var request = $.ajax({
                url: 'index.php?route=extension/module/intent_requisites/ajaxHtmlHelper&row_id=' + category_row + '&action=addrow&token=<?php echo $token; ?>',
                dataType: 'html'
            });
            request.done(function (response) {
                $('#categories-value tbody').append(response);
                category_row++;
            })
        }


        function remove(id) {

             $('#'+id).remove()
         }


        //--></script></div>
<?php echo $footer; ?>
