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
				<div class="htabs">
					<a href="#tab-general"><?php echo $tab_general; ?></a>
					<a href="#tab-vkontakte"><?php echo $tab_vkontakte; ?></a>
					<a href="#tab-facebook"><?php echo $tab_facebook; ?></a>
					<a href="#tab-facebook"><?php echo $tab_twitter; ?></a>
				</div>
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
					<input type="hidden" name="stay" id="stayid" value="1">
					<div id="tab-vkontakte">
						<table  class="table table-striped table-bordered table-hover">
							<th colspan="2" style="text-align: center; color: #000000; background-color: #959ba6">Вконтакте</th>
							<tbody>
							<tr>
								<td><?php echo $entry_status; ?></td>
								<td>
									<select name="sociallogin_vkontakte_status" class="form-control">
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
						<table  class="table table-striped table-bordered table-hover">
							<th colspan="2" style="text-align: center; color: #000000; background-color: #959ba6">Фейсбук</th>
							<tbody>
							<tr>
								<td><?php echo $entry_status; ?></td>
								<td>
									<select name="sociallogin_facebook_status" class="form-control">
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

					<div id="tab-twitter">
						<table  class="table table-striped table-bordered table-hover">
							<th colspan="2" style="text-align: center; color: #000000; background-color: #959ba6">Твитер</th>
							<tbody>
							<tr>
								<td><?php echo $entry_status; ?></td>
								<td>
									<select name="sociallogin_twitter_status" class="form-control">
										<?php if ($sociallogin_twitter_status) { ?>
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
								<td><?php echo $entry_twitter_appid; ?></td>
								<td><input type="text" name="sociallogin_twitter_appid" style="width: 300px;" value="<?php echo $sociallogin_twitter_appid; ?>" /></td>
							</tr>
							<tr>
								<td><?php echo $entry_facebook_appsecret; ?></td>
								<td><input type="text" name="sociallogin_twitter_appsecret" style="width: 300px;" value="<?php echo $sociallogin_twitter_appsecret; ?>" /></td>
							</tr>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript"><!--

$('.htabs a').tabs();

//--></script>


<?php echo $footer; ?>