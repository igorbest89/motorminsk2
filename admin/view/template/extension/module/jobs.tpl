<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-location" data-toggle="tooltip" title="<?php echo $button_save; ?>"
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-location"
                      class="form-horizontal">

                    <div class="form-group required">
                        <label class="col-sm-2 control-label"
                               for="input-name"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?php echo $name; ?>"
                                   placeholder="<?php echo $entry_name; ?>" id="input-name"
                                   class="form-control"/>
                            <?php if ($error_name) { ?>
                            <div class="text-danger"><?php echo $error_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_module_description; ?></label>
                        <div class="col-sm-10">
                            <textarea name="module_description" id="input-module-description" class="form-control summernote"><?php echo $module_description; ?></textarea>
                        </div>
                    </div>

                    <div id="appenddiv">
                        <?php foreach($module_info as $i => $job){ ?>
                            <div class="job" id="<?php echo $i ;?>">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-job-name"><?php echo $entry_job_name; ?></label>
                                    <div class="col-sm-10">
                                        <input name="job_name[<?php echo $i; ?>]" value="<?php echo $job['job_name']; ?>" id="input-job-name[<?php echo $i; ?>]" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_job_description; ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="description[<?php echo $i; ?>]" id="input-description[<?php echo $i; ?>]" class="form-control summernote"><?php echo $job['description']; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_status; ?></label>
                                    <div class="col-sm-10">
                                        <select name="status[<?php echo $i; ?>]" id="input-status" class="form-control">
                                            <?php if ($job['status']) { ?>
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
                        <?php } ?>
                    </div>
                </form>
                <div class="form-group">
                    <button type="button" onclick="addJob();" data-toggle="tooltip"
                            class="btn btn-primary pull-right"><i class="fa fa-plus-circle "></i></button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
        var category_row = <?php echo $key; ?>;
        function addJob() {
            var request = $.ajax({
                url: 'index.php?route=extension/module/jobs/ajaxHtmlHelper&row_id=' + category_row + '&action=addrow&token=<?php echo $token; ?>',
                dataType: 'html'
            });
            request.done(function (response) {
                $('#appenddiv').append(response);
                category_row++;
            })
        }

        function remove(id){
            $('#'+id).remove();
        }

        //--></script>
</div>

<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>

<?php echo $footer; ?>