<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-oc-smsc" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-oc-smsc" onsubmit="create_hidden_fields()" class="form-horizontal">
          <div class="tab-pane">
            <ul class="nav nav-tabs" id="setting">
				<li><a href="#tab-connection" data-toggle="tab"><h4><?php echo $oc_smsc_tab_connection ?></h4></a></li>
				<li><a href="#tab-admin" data-toggle="tab"><h4><?php echo $oc_smsc_tab_member ?></h4></a></li>
				<li><a href="#tab-customer" data-toggle="tab"><h4><?php echo $oc_smsc_tab_customer ?></h4></a></li>
            </ul>
            <div class="tab-content">
              <div id="tab-connection" class="tab-pane">
			  <table><tr><td width="32%">
				<label>
					<?php echo $oc_smsc_text_login ?> *<br />
					<input type="text" name="oc_smsc_login" value="<?php echo (isset($value_oc_smsc_login) ? $value_oc_smsc_login : false) ?>" required />
				</label><br>
				<label>
					<?php echo $oc_smsc_text_password ?> *<br />
					<input type="password" name="oc_smsc_password" value="<?php echo (isset($value_oc_smsc_password) ? $value_oc_smsc_password : false) ?>" required />
				</label><br>
				<label>
					<?php echo $oc_smsc_text_signature ?><br />
					<input type="text" name="oc_smsc_signature" value="<?php echo (isset($value_oc_smsc_signature) ? $value_oc_smsc_signature : false) ?>" />
				</label><br>
				<label>
					<?php echo $oc_smsc_text_maxsms ?><br />
					<div><input type="text" name="oc_smsc_maxsms" value="<?php echo (isset($value_oc_smsc_maxsms) ? $value_oc_smsc_maxsms : false) ?>" />&nbsp;<?php echo $oc_smsc_text_sms ?></div>
				</label><br>
				<label>
					<div><input type="checkbox" name="oc_smsc_debug" value="1" <?php echo (isset($value_oc_smsc_debug) ? 'checked="checked"' : false) ?> />&nbsp;<?php echo $oc_smsc_text_debug ?></div>
				</label>
				<td valign="top"><?php echo $oc_smsc_text_connection_tab_description ?>
			  </table>
			  </div>
              <div id="tab-admin" class="tab-pane">
			  <table><tr><td valign="top" width="30%">
				<h4><?php echo $oc_smsc_text_notify_by_sms ?>:</h4>
				<label>
					<div><input type="checkbox" name="oc_smsc_admin_new_customer" value="1" <?php echo (isset($value_oc_smsc_admin_new_customer) ? 'checked="checked"' : false) ?> />
					<?php echo $oc_smsc_text_admin_new_customer ?></div>
				</label><br>
				<div>
					<label><input type="checkbox" name="oc_smsc_admin_new_order" value="1" <?php echo (isset($value_oc_smsc_admin_new_order) ? 'checked="checked"' : false) ?> />
					<?php echo $oc_smsc_text_admin_new_order ?></label><b>&nbsp;(
					<input type="checkbox" name="oc_smsc_call_adm_order" id="oc_smsc_call_adm_order" <?php echo (isset($value_oc_smsc_call_adm_order) ? 'checked="checked"' : '') ?> />&nbsp;<span style="cursor:default" onclick="check_call('oc_smsc_call_adm_order')"><?php echo $oc_smsc_text_call ?></span>)
				</div>
				<br>
				<label>
					<input type="checkbox" name="oc_smsc_admin_new_email" value="1" <?php echo (isset($value_oc_smsc_admin_new_email) ? 'checked="checked"' : false) ?> />
					<?php echo $oc_smsc_text_admin_new_email ?>
				</label><br>
				<label>
					<?php echo $oc_smsc_text_telephone ?><br />
					<input type="text" name="oc_smsc_telephone" value="<?php echo (isset($value_oc_smsc_telephone) ? $value_oc_smsc_telephone : false) ?>" />
				</label>
				<td valign="top" width="30%"><h4><?php echo $oc_smsc_text_notify ?>:</h4>
				<label>
					<?php echo $oc_smsc_label_admin_new_order ?><br />
					<textarea cols=45 rows=3 name="oc_smsc_textarea_admin_new_order"><?php echo (!empty($value_oc_smsc_textarea_admin_new_order) ? $value_oc_smsc_textarea_admin_new_order : $oc_smsc_text_admin_new_order) ?></textarea>
				</label>
				<td valign="top"><?php echo $oc_smsc_text_macros_description ?>
			  </table>
			  </div>
              <div id="tab-customer" class="tab-pane">
			  <table><tr><td valign="top" width="30%">
				<h4><?php echo $oc_smsc_text_notify_by_sms ?>:</h4>
				<div>
						<label><input type="checkbox" name="oc_smsc_customer_new_order" value="1" <?php echo (isset($value_oc_smsc_customer_new_order) ? 'checked="checked"' : false) ?> />
						<?php echo $oc_smsc_text_customer_new_order ?></label><b>&nbsp;(
					<input type="checkbox" name="oc_smsc_call_cust_order" id="oc_smsc_call_cust_order" <?php echo (isset($value_oc_smsc_call_cust_order) ? 'checked="checked"' : '') ?> />&nbsp;<span style="cursor:default" onclick="check_call('oc_smsc_call_cust_order')"><?php echo $oc_smsc_text_call ?></span>)
				</div>
				<br>
				<div>
					<label><input type="checkbox" name="oc_smsc_customer_new_order_status" value="1" <?php echo (isset($value_oc_smsc_customer_new_order_status) ? 'checked="checked"' : false) ?> />
					<?php echo $oc_smsc_text_customer_new_order_status ?></label><b>&nbsp;(
					<input type="checkbox" name="oc_smsc_call_status_order" id="oc_smsc_call_status_order" <?php echo (isset($value_oc_smsc_call_status_order) ? 'checked="checked"' : '') ?> />&nbsp;<span style="cursor:default" onclick="check_call('oc_smsc_call_status_order')"><?php echo $oc_smsc_text_call ?></span>)
				</div>
				<br>
				<label>
					<div><input type="checkbox" name="oc_smsc_customer_new_register" value="1" <?php echo (isset($value_oc_smsc_customer_new_register) ? 'checked="checked"' : false) ?> />
					<?php echo $oc_smsc_text_customer_new_register ?></div>
				</label>
				<div>
					<label><input type="checkbox" id="oc_smsc_customer_act_phone" name="oc_smsc_customer_act_phone" value="1" <?php echo (isset($value_oc_smsc_customer_act_phone) ? 'checked="checked"' : false) ?> onclick="act_secret(this)"/>
					<?php echo $oc_smsc_text_customer_act_phone ?></label><b>&nbsp;(
					<input type="checkbox" name="oc_smsc_call_reg" id="oc_smsc_call_reg" <?php echo (isset($value_oc_smsc_call_reg) ? 'checked="checked"' : '') ?> />&nbsp;<span style="cursor:default" onclick="check_call('oc_smsc_call_reg')"><?php echo $oc_smsc_text_call ?></span>)
					<br><?php echo $oc_smsc_text_customer_attention ?></b>
				</div>
				<br>
				<label>
					<?php echo $oc_smsc_text_secret ?>
					<input type="text" name="oc_smsc_secret" value="<?php echo (isset($value_oc_smsc_secret) ? $value_oc_smsc_secret : 'secret string') ?>" id="oc_smsc_secret"/>
				</label><br>
				<td valign="top" width="30%"><h4><?php echo $oc_smsc_text_notify ?>:</h4>
				<label>
					<?php echo $oc_smsc_label_customer_new_order ?><br />
					<textarea cols=45 rows=3 name="oc_smsc_textarea_customer_new_order"><?php echo (!empty($value_oc_smsc_textarea_customer_new_order) ? $value_oc_smsc_textarea_customer_new_order : $oc_smsc_text_customer_new_order) ?></textarea>
				</label>
				<label>
					<br /><?php echo $oc_smsc_label_customer_new_status."<br />".$oc_smsc_text_status ?>&nbsp;
					<select name="oc_smsc_select_customer_new_status" id="oc_smsc_select_customer_new_status" onchange="oc_smsc_textarea_customer_new_status.value = return_status_mes(this)">
					<?php
						$stat_num = $options = "";

						$fonf = !empty($status_id_message->rows);

						foreach ($order_statuses->rows as $k => $v) {
							$options .= "<option ".($k ? "" : "selected")." value=".$v['order_status_id'].">".$v['name']."</option>";
							$stat_num .= ($k ? "," : "")."[".$v['order_status_id'].",";
							if ($fonf)
								foreach ($status_id_message->rows as $kid => $vid) {
									if (substr($vid['key'], 18) == $v['order_status_id']) {
										$stat_num .= '"'.($vid['value'] ? $vid['value'] : '').'"]';
										break;
									}

									if ($kid == count($status_id_message->rows) - 1)
										$stat_num .= '""]';
								}
							else
								$stat_num .= '"'.$oc_smsc_textarea_customer_new_status.'"]';
						}
						echo $options;
					?>
					</select><br><br>
					<textarea cols=45 rows=3 name="oc_smsc_textarea_customer_new_status" onchange="save_notify_mes(oc_smsc_select_customer_new_status, this)"><?php echo (!empty($value_oc_smsc_textarea_customer_new_status) ? $value_oc_smsc_textarea_customer_new_status : $oc_smsc_message_customer_new_order_status) ?></textarea>
				</label>
				<td valign="top"><?php echo $oc_smsc_text_macros_description ?>
				<input type="hidden" name="form-oc-smsc" value="1" />
			  </table>
			  </div>
			</div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="oc_smsc_status" id="input-status" class="form-control">
                <?php if ($oc_smsc_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$('#oc_smsc_secret').prop({disabled: ($('#oc_smsc_customer_act_phone').prop('checked') ? false : true)});

	$('#setting a:first').tab('show');

	var status_ids_mes = [<?php echo $stat_num ?>]

	var HTMLdecoder = document.createElement('textarea')
	var HTMLencoder = document.createElement('div')

	var create_change = document.getElementById('oc_smsc_select_customer_new_status')
	var evnt = document.createEvent('HTMLEvents')

	evnt.initEvent('change', true, false)

	create_change.dispatchEvent(evnt)

	function save_notify_mes(st_id, notify_mes) {
		var i

		for (i = 0; i < status_ids_mes.length; i++)
			if (status_ids_mes[i][0] == st_id.value) {
				HTMLencoder.innerHTML = notify_mes.value
				status_ids_mes[i][1] = HTMLencoder.innerHTML.replace(/\n/g, '\\n')
				break
			}
	}

	function return_status_mes(status) {
		for (var i = 0; i < status_ids_mes.length; i++)
			if (status_ids_mes[i][0] == status.value) {
				HTMLdecoder.innerHTML = status_ids_mes[i][1].replace(/\\n/g, '\n')

				return HTMLdecoder.value
			}
	}

	function create_hidden_fields() {
		var inp, i

		for (i = 0; i < status_ids_mes.length; i++) {
			inp = document.createElement('input')
			inp.name = 'oc_smsc_status_id_' + status_ids_mes[i][0]
			inp.type = 'hidden'

			HTMLdecoder.innerHTML = status_ids_mes[i][1]

			inp.value = HTMLdecoder.value.replace(/\n/g, '\\n')
			document.getElementById('form-oc-smsc').appendChild(inp)
		}
	}

	function act_secret(e) {
		$('#oc_smsc_secret').prop({disabled: (e.checked ? false : true)});
	}
	function check_call(v) {
		$('#' + v).prop({checked: !$('#' + v).prop("checked")});
	}
</script>

<?php echo $footer; ?>
