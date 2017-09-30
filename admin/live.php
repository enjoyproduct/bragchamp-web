<?php
$page_title = "Live Items";
include_once "include/header.php";

$error_msg = FALSE;
$success_msg = FALSE;

if (isset($_POST["name"])) {
	$name = $_POST["name"];
	$row = $wpdb->get_row("SELECT * FROM live WHERE name='".$name."'");
	if ($row) {
		$error_msg = "This name is existing. Try to input another name.";
	} else {
		$targetPath = "	upload_files/";
		$fileName = $name."_".$_FILES["myFile"]["name"];
		$targetPath .= $fileName;
		if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $targetPath)) {
			$query = "INSERT INTO live (name, image) VALUES('$name', '$fileName')";
			$status = $wpdb->query($query);
			if ($status !== FALSE) {
				header("Location: live.php");
			} else {
				$error_msg = "Insert failed.";
			}
		} else {
			$error_msg = "Uploading failed. Please try again.";
		}
	}
}

?>

<script>

function saveLive() {
	
	var frmUpload = document.frmUpload;

	$("div.form-group").removeClass("has-error");

	if (frmUpload.name.value == "") {
		$("div.name-field").addClass("has-error");
		alert("Please input live name.");
		frmUpload.name.focus();

		return;
	}

	var fileName = $("#myFile").val();

	if (fileName == "") {
		$("div.file-field").addClass("has-error");
		alert("Please select live image.");
		fileName.focus();

		return;
	} else if (fileName.lastIndexOf("png") === fileName.length-3 || 
				fileName.lastIndexOf("jpg") == fileName.length-3 ||
				fileName.lastIndexOf("jpeg") == fileName.length-4) {

	} else {
		alert("Invaild image type. Image should be jpg, jpeg or png.");
		fileName.focus();
		return;
	}

	frmUpload.submit();
}

function deleteLive() {
		if (!confirm("Are you sure this action?")) {
			return;
		}
		$tblUsers = $("#tblUsers");

		var selectedIds = "0";
		var lives = $tblUsers.bootstrapTable("getData");
		for (i = 0; i < lives.length; i ++) {
			if (lives[i].userid) {
				selectedIds += ("," + lives[i].id);
			}
		}

		if (selectedIds == "0") {
			alert("Please select lives");
			return;
		}

		$.post("apis.php?action=deletelive", {
			'selected_ids' : selectedIds
		}, function(result) {
			
			if (result == 'OK') {
				$tblUsers.bootstrapTable('refresh');
			} else {
				alert("Failed deleting lives");
			}
		})
	}

</script>

<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Live Items</h1>
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
							<form role="form" name="frmUpload" method="post" autocomplete="off" enctype="multipart/form-data">
							
								<div class="form-group name-field">
									<label>Name</label>
									<input type="text" class="form-control" name="name">
								</div>

								<div class="form-group file-field">
									<input type="file" class="form-control" name="myFile" id="myFile" accept=".png,.jpg,.jpeg">
									
								</div>

								<button type="button" class="btn btn-primary" onclick="saveLive()">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div><!-- /.col-->

			<div class="col-lg-12">

				<div class="panel-heading">
					<a href="#" class="btn btn-primary" onclick="deleteLive()">Delete</a>
					&nbsp;
					<small>selected lives</small>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<table id="tblUsers"
							   data-toggle="table"
							   data-url="apis.php?action=getlives"
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
									<th data-field="image" data-sortable="true">File Name</th>
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