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

                    <div id="appenddiv">

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

                            <div class="form-group required">
                                <label class="col-sm-2 control-label"
                                       for="input-address"><?php echo $entry_address; ?></label>
                                <div class="col-sm-10">
                                <textarea type="text" name="address" placeholder="<?php echo $entry_address; ?>"
                                          rows="5" id="input-address"
                                          class="form-control"><?php echo $address; ?></textarea>
                                    <?php if ($error_address) { ?>
                                    <div class="text-danger"><?php echo $error_address; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-geocode"><span data-toggle="tooltip"
                                                                                                data-container="#content"
                                                                                                title="<?php echo $help_geocode; ?>"><?php echo $entry_geocode; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="geocode" value="<?php echo $geocode; ?>"
                                           placeholder="<?php echo $entry_geocode; ?>" id="input-geocode"
                                           class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label"
                                       for="input-telephone"><?php echo $entry_telephone; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="telephone" value="<?php echo $telephone; ?>"
                                           placeholder="<?php echo $entry_telephone; ?>" id="input-telephone"
                                           class="form-control"/>
                                    <?php if ($error_telephone) { ?>
                                    <div class="text-danger"><?php echo $error_telephone; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="fax" value="<?php echo $fax; ?>"
                                           placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-email"><?php echo $entry_email; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" value="<?php echo $email; ?>"
                                           placeholder="<?php echo $entry_email; ?>" id="input-email"
                                           class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-image"><?php echo $entry_image; ?></label>
                                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image"
                                                          class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt=""
                                                                                     title=""
                                                                                     data-placeholder="<?php echo $placeholder; ?>"/></a>
                                    <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-open"><span data-toggle="tooltip"
                                                                                             data-container="#content"
                                                                                             title="<?php echo $help_open; ?>"><?php echo $entry_open; ?></span></label>
                                <div class="col-sm-10">
                                <textarea name="open" rows="5" placeholder="<?php echo $entry_open; ?>" id="input-open"
                                          class="form-control"><?php echo $open; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-comment"><span data-toggle="tooltip"
                                                                                                data-container="#content"
                                                                                                title="<?php echo $help_comment; ?>"><?php echo $entry_comment; ?></span></label>
                                <div class="col-sm-10">
                                <textarea name="comment" rows="5" placeholder="<?php echo $entry_comment; ?>"
                                          id="input-comment" class="form-control"><?php echo $comment; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label"
                                       for="input-store-groupe"><?php echo $entry_store_group; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="store_group" onfocus="storeGroupsAutocomplete();"
                                           value="<?php echo $store_group; ?>"
                                           placeholder="<?php echo $entry_store_group; ?>" id="input_store_group"
                                           class="form-control group_name"/>
                                    <?php if ($error_store_group) { ?>
                                    <div class="text-danger"><?php echo $error_store_group; ?></div>
                                    <?php } ?>
                                    <input type="text" style="display: none" name="store_group_id"
                                           value="<?php echo $store_group_id; ?>" id="input_store_group_id"
                                           class="form-control group_id"/>
                                </div>
                            </div>

                        <?php if($settings != ''){
                             foreach ($settings as $setting){
                                echo $setting;
                             }
                         } ?>

                    </div>
                </form>
                <div class="form-group">
                    <button type="button" onclick="addStore();" data-toggle="tooltip"
                            class="btn btn-primary pull-right"><i class="fa fa-plus-circle "></i></button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--

        function storeGroupsAutocomplete() {
            $('.group_name').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: 'index.php?route=localisation/location/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                        dataType: 'json',
                        success: function (json) {
                            response($.map(json, function (item) {
                                return {
                                    label: item['name'],
                                    value: item['store_group_id']
                                }
                            }));
                        }
                    });
                },
                select: function (item) {
                    console.log(item)
                    $(this).parent().find('.group_name').val(item['label']);
                    $(this).parent().find('.group_id').val(item['value']);
                }
            });
        }

        var category_row = <?php echo $i; ?>;

        function addStore() {
            var request = $.ajax({
                url: 'index.php?route=localisation/location/ajaxHtmlHelper&row_id='+ category_row +'&action=addrow&token=<?php echo $token; ?>',
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
<?php echo $footer; ?>