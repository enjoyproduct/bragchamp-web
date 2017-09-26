<?php
$page_title = "Change Password";
include_once "include/header.php";

$error_msg = FALSE;
$success_msg = FALSE;
if (isset($_POST['current_pwd']) && isset($_POST['new_pwd']) && isset($_POST['confirm_pwd'])) {
	$current_pwd = $_POST['current_pwd'];
	$new_pwd = $_POST['new_pwd'];
	$confirm_pwd = $_POST['confirm_pwd'];

	if (encrypt_password($current_pwd) != $adminuser->password) {
		$error_msg = "Your current password is incorrect.";
	}

	if ($wpdb->update("admins", array("password" => encrypt_password($new_pwd)), array("id" => $adminuser->id))) {
		$success_msg = "Your password was changed.";
	} else {
		$error_msg = "Failed change password.";
	}
}
?>

<script>
function savePwd() {
	var frmChangePwd = document.frmChangePwd;

	$("div.form-group").removeClass("has-error");

	if (frmChangePwd.current_pwd.value == "") {
		$("div.current-pwd-field").addClass("has-error");
		alert("Please input current password.");
		frmChangePwd.current_pwd.focus();

		return;
	}

	if (frmChangePwd.new_pwd.value == "") {
		$("div.new-pwd-field").addClass("has-error");
		alert("Please input new password.");
		frmChangePwd.new_pwd.focus();

		return;
	}

	if (frmChangePwd.confirm_pwd.value != frmChangePwd.new_pwd.value) {
		$("div.confirm-pwd-field").addClass("has-error");
		alert("Confirm password is incorrect.");
		frmChangePwd.confirm_pwd.focus();

		return;
	}

	frmChangePwd.submit();
}
</script>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Please input new password.</h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<?php if ($success_msg !== FALSE) : ?>
					<div class="alert bg-success" role="alert">
						<svg class="glyph stroked checkmark"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-checkmark"></use></svg> <?php echo $success_msg ?> <a href="javascript:$('div.bg-success').fadeOut()" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
					</div>
				<?php endif; ?>
				<?php if ($error_msg !== FALSE) : ?>
					<div class="alert bg-danger" role="alert">
						<svg class="glyph stroked cancel"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-cancel"></use></svg> <?php echo $error_msg ?> <a href="javascript:$('div.bg-danger').fadeOut()" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
					</div>
				<?php endif; ?>

				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-6">
							<form role="form" name="frmChangePwd" method="post" autocomplete="off">
							
								<div class="form-group current-pwd-field">
									<label>Current Password</label>
									<input type="password" class="form-control" name="current_pwd">
								</div>

								<div class="form-group new-pwd-field">
									<label>New Password</label>
									<input type="password" class="form-control" name="new_pwd">
								</div>

								<div class="form-group confirm-pwd-field">
									<label>Confirm Password</label>
									<input type="password" class="form-control" name="confirm_pwd">
								</div>
								
								<button type="button" class="btn btn-primary" onclick="savePwd()">Save</button>
								<button type="button" class="btn btn-default" onclick="location.href='index.php'">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
<?php
include_once "include/footer.php";