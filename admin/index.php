<?php
$page_title = "User Management";
include_once "include/header.php";

?>

<script>
	function deleteUser() {
		if (!confirm("Are you sure this action?")) {
			return;
		}
		$tblUsers = $("#tblUsers");

		var selectedIds = "0";
		var users = $tblUsers.bootstrapTable("getData");
		for (i = 0; i < users.length; i ++) {
			if (users[i].userid) {
				selectedIds += ("," + users[i].id);
			}
		}

		if (selectedIds == "0") {
			alert("Please select users");
			return;
		}

		$.post("apis.php?action=deleteusers", {
			'selected_ids' : selectedIds
		}, function(result) {
			
			if (result == 'OK') {
				$tblUsers.bootstrapTable('refresh');
			} else {
				alert("Failed deleting users");
			}
		})
	}
</script>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">User Management</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">

				<div class="panel-heading">
					<a href="#" class="btn btn-primary" onclick="deleteUser()">Delete</a>
					&nbsp;
					<small>selected stories</small>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">
						<table id="tblUsers"
							   data-toggle="table"
							   data-url="apis.php?action=getusers"
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
									<th data-field="user_name"  data-sortable="true">User Name</th>
									<th data-field="fname" data-sortable="true">First Name</th>
									<th data-field="lname" data-sortable="true">Last Name</th>
									<th data-field="email" data-sortable="true">Email</th>
									<th data-field="birthday" data-sortable="true">Birthday</th>
									<th data-field="last_logintime" data-sortable="true">Last Login Time</th>
								</tr>
						    </thead>
						</table>
					</div>
				</div>
			</div>
		</div><!--/.row-->

<?php
include_once "include/footer.php";