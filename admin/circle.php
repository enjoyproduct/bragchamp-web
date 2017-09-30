<?php
$page_title = "Trending Items";
include_once "include/header.php";

$error_msg = FALSE;
$success_msg = FALSE;

if (isset($_POST["name"]) && isset($_POST["latitude"]) && isset($_POST["longitude"])) {
	$name = $_POST["name"];
	$latitude = $_POST["latitude"];
	$longitude = $_POST["longitude"];
	$row = $wpdb->get_row("SELECT * FROM circle WHERE name='".$name."'");
	if ($row) {
		$error_msg = "This name is existing. Try to input another name.";
	} else {
	
		$query = "INSERT INTO circle (name, latitude, longitude) VALUES('$name', $latitude, $longitude)";
		$status = $wpdb->query($query);
		if ($status !== FALSE) {
			header("Location: circle.php");
		} else {
			$error_msg = "Insert failed.";
		}
		
	}
}

?>

<script>

function saveCircle() {
	
	var frmUpload = document.frmUpload;

	$("div.form-group").removeClass("has-error");

	if (frmUpload.name.value == "") {
		$("div.name-field").addClass("has-error");
		alert("Please input trending name.");
		frmUpload.name.focus();

		return;
	}

	if (frmUpload.latitude.value == "") {
		$("div.latitude-field").addClass("has-error");
		alert("Please input latitude.");
		frmUpload.latitude.focus();

		return;
	}

	if (frmUpload.longitude.value == "") {
		$("div.longitude-field").addClass("has-error");
		alert("Please input longitude.");
		frmUpload.longitude.focus();

		return;
	}

	frmUpload.submit();
}

function deleteCircle() {
		if (!confirm("Are you sure this action?")) {
			return;
		}
		$tblUsers = $("#tblUsers");

		var selectedIds = "0";
		var circles = $tblUsers.bootstrapTable("getData");
		for (i = 0; i < circles.length; i ++) {
			if (circles[i].userid) {
				selectedIds += ("," + circles[i].id);
			}
		}

		if (selectedIds == "0") {
			alert("Please select trendings");
			return;
		}

		$.post("apis.php?action=deletecircle", {
			'selected_ids' : selectedIds
		}, function(result) {
			
			if (result == 'OK') {
				$tblUsers.bootstrapTable('refresh');
			} else {
				alert("Failed deleting trendings");
			}
		})
	}

</script>

<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Trending Items</h1>
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
							<form role="form" name="frmUpload" method="post" autocomplete="off">
							
								<div class="form-group name-field">
									<label>Name</label>
									<input type="text" class="form-control" name="name">
								</div>

								<div class="form-group latitude-field">
									<label>Latitude</label>
									<input type="number" step="0.000001" class="form-control" name="latitude">
								</div>

								<div class="form-group longitude-field">
									<label>Longitude</label>
									<input type="number" step="0.000001" class="form-control" name="longitude">
								</div>

								<button type="button" class="btn btn-primary" onclick="saveCircle()">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div><!-- /.col-->

			<div class="col-lg-12">

				<div class="panel-heading">
					<a href="#" class="btn btn-primary" onclick="deleteCircle()">Delete</a>
					&nbsp;
					<small>selected lives</small>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<table id="tblUsers"
							   data-toggle="table"
							   data-url="apis.php?action=getcircles"
							   data-show-refresh="true"
							   data-show-toggle="true"
							   data-show-columns="true"
							   data-search="true"
							   data-select-item-name="userid"
							   data-pagination="true"
							   data-sort-name="name"
							   data-sort-order="desc">
						    <thead>
								<tr>
									<th data-field="userid" data-checkbox="true" >User ID</th>
									<th data-field="id" data-sortable="true">ID</th>
									<th data-field="name"  data-sortable="true">Name</th>
									<th data-field="latitude" data-sortable="true">Latitude</th>
									<th data-field="longitude" data-sortable="true">Longitude</th>
								</tr>
						    </thead>
						</table>
					</div>
				</div>
			</div>
		</div><!--/.row-->

<?php
include_once "include/footer.php";
?>