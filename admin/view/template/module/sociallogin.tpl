<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>

  <?php if (isset($success)) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
		<a onclick="$('#stayid').attr('value', '0'); $('#form').submit();" class="button"><span><?php echo $button_save_go; ?></span></a>
		<a onclick="$('#stayid').attr('value', '1'); $('#form').submit();" class="button"><span><?php echo $button_save_stay; ?></span></a>
		<a href="<?php echo $cancel; ?>" class="button"><span><?php echo $button_cancel; ?></span></a>
	  </div>
    </div>
    <div class="content">
	  <div class="htabs">
		    <a href="#tab-general"><?php echo $tab_general; ?></a>
		    <a href="#tab-vkontakte"><?php echo $tab_vkontakte; ?></a>
		    <a href="#tab-facebook"><?php echo $tab_facebook; ?></a>
      </div>

	  <div id="tab-general">
		<? echo $text_description; ?>
	  </div>

      <form action="<?php echo $action; ?>" method="post" id="form">
	  <input type="hidden" name="stay" id="stayid" value="1">
	  <div id="tab-vkontakte">
        <table class="form">
          <tbody>
            <tr>
				<td><?php echo $entry_status; ?></td>
				<td>
					<select name="sociallogin_vkontakte_status">
					<?php if ($sociallogin_vkontakte_status) { ?>
						<option value="1" selected="selected"
						><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"
						><?php echo $text_disabled; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
            <tr>
				<td><?php echo $entry_vkontakte_appid; ?></td>
				<td><input type="text" name="sociallogin_vkontakte_appid" style="width: 300px;" value="<?php echo $sociallogin_vkontakte_appid; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_vkontakte_appsecret; ?></td>
				<td><input type="text" name="sociallogin_vkontakte_appsecret" style="width: 300px;" value="<?php echo $sociallogin_vkontakte_appsecret; ?>" /></td>
			</tr>
		</table>
	  </div>

	  <div id="tab-facebook">
        <table class="form">
          <tbody>
            <tr>
				<td><?php echo $entry_status; ?></td>
				<td>
					<select name="sociallogin_facebook_status">
					<?php if ($sociallogin_facebook_status) { ?>
						<option value="1" selected="selected"
						><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"
						><?php echo $text_disabled; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
            <tr>
				<td><?php echo $entry_facebook_appid; ?></td>
				<td><input type="text" name="sociallogin_facebook_appid" style="width: 300px;" value="<?php echo $sociallogin_facebook_appid; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_facebook_appsecret; ?></td>
				<td><input type="text" name="sociallogin_facebook_appsecret" style="width: 300px;" value="<?php echo $sociallogin_facebook_appsecret; ?>" /></td>
			</tr>
		</table>
	  </div>
      </form>

	  <div style="margin-top:25px;border-top:1px dashed #ccc;padding-top:15px;text-align:center;"><? echo $text_help; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.htabs a').tabs();
//--></script>
<?php echo $footer; ?>