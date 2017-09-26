<?php
include_once "include/include.php";

$username = "";
$error_msg = "";
if (isset($_POST['username']) && isset($_POST['password'])) {
	// check admin by username and password
	$username = $_POST['username'];
	$password = $_POST['password'];

	$admin = $wpdb->get_row("SELECT * FROM admins WHERE username='".$username."'");
	if ($admin) {
		if (encrypt_password($password) != $admin->password) {
			$error_msg = "Error: The password is incorrect.  ";
		} else {
			set_session_adminid($admin->id);
			date_default_timezone_set("UTC");
			$wpdb->update("admins", array("last_logined" => date('Y-m-d H:i:s')), array("id" => $admin->id));

			header("Location: index.php");
		}
	} else {
		$error_msg = "Error: <b>'" . $username . "'</b> isn't admin user of our system";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo SITE_TITLE?> - Login</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
	
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">Log in</div>
				<div class="panel-body">
					<?php if ($error_msg != '') : ?>
					<div class="alert bg-danger" role="alert">
						<?php echo $error_msg ?>
					</div>
					<?php endif; ?>
					<form role="form" name="frmLogin" method="post" autocomplete="off">
						<fieldset>
							<div class="form-group">
								<input class="form-control" placeholder=Username name="username" type="text" autofocus="" value="<?php echo $username ?>">
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="Password" name="password" type="password" value="">
							</div>
							<a href="#" class="btn btn-primary" onclick="login()">Login</a>
						</fieldset>
					</form>
				</div>
			</div>
		</div><!-- /.col-->
	</div><!-- /.row -->	
	
		

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
		!function ($) {
			$(document).on("click","ul.nav li.parent > a > span.icon", function(){		  
				$(this).find('em:first').toggleClass("glyphicon-minus");	  
			}); 
			$(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})

		function login() {
			var frmLogin = document.frmLogin;
			if (frmLogin.username.value.length == '') {
				alert("Please input username");
				frmLogin.username.focus();
				return false;
			}

			if (frmLogin.password.value.length == '') {
				alert("Please input password");
				frmLogin.password.focus();
				return false;
			}

			frmLogin.submit();
		}
	</script>	
</body>

</html>
