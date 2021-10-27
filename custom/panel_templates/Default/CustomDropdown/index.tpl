{include file='header.tpl'}

<body id="page-top">

	<!-- Wrapper -->
	<div id="wrapper">

		<!-- Sidebar -->
		{include file='sidebar.tpl'}

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main content -->
			<div id="content">

				<!-- Topbar -->
				{include file='navbar.tpl'}

				<!-- Begin Page Content -->
				<div class="container-fluid">

					<!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-4">


						<div class="row mb-2">
							<div class="col-sm-6">
								<h1 class="m-0 text-dark">{$TITLE}</h1>
							</div>
						</div>
					</div>

					<section class="content">
						{if isset($SUCCESS)}
							<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
								{$SUCCESS}
							</div>
						{/if}

						{if isset($ERRORS) && count($ERRORS)}
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
								<ul>
									{foreach from=$ERRORS item=error}
										<li>{$error}</li>
									{/foreach}
								</ul>
							</div>
						{/if}
						<div class="float-md">
							<button class="btn btn-primary" type="button" onclick="showAddModal()">{$ADD_NEW_DROPDOWN} <i
									class="fa fa-plus-circle">
								</i></button>
						</div>
						<hr>
						{if count($DROPDOWN_LIST)}
							<div class="table-responsive">
								<table class="table table-striped">
									<tbody>
										{foreach from=$DROPDOWN_LIST item=dropdown}
											<tr>
												<td>
													<strong>{$dropdown.dropdown_title}</strong>
												</td>
												<td>
													<div class="float-md-right">

														{if $dropdown.enabled == 1}
															<a class="btn btn-danger btn-sm" title="{$DISABLE}" href="{$dropdown.enabled_link}"><i
																	class="fa fa-toggle-off" aria-hidden="true"></i></a>
														{else}
															<a class="btn btn-success btn-sm" title="{$ENABLE}" href="{$dropdown.enabled_link}"><i
																	class="fa fa-toggle-on" aria-hidden="true"></i></a>
														{/if}
														<a class="btn btn-primary btn-sm" title="{$SETTINGS}" href="{$dropdown.setting_link}"><i
																class="fa fa-cogs" aria-hidden="true"></i></a>
														<a class="btn btn-warning btn-sm" title="{$EDIT}" href="{$dropdown.edit_link}"><i
																class="fas fa-edit fa-fw"></i></a>
														<button class="btn btn-danger btn-sm" title="{$REMOVE}" type="button"
															onclick="showDeleteModal('{$dropdown.delete_link}')"><i
																class="fas fa-trash fa-fw"></i></button>
													</div>
												</td>
											</tr>
										{/foreach}
									</tbody>
								</table>
							</div>
						{else}
							{$NO_DROPDOWN}
						{/if}
					</section>
				</div>




				<!-- Modal Form -->

				<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">{$ARE_YOU_SURE}</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								{$CONFIRM_DELETE}
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
								<a href="#" id="deleteDropdown" class="btn btn-primary">{$YES}</a>
							</div>
						</div>
					</div>
				</div>


				<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">{$ADD_NEW_DROPDOWN}</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="" method="post">
									<div class="form-group">
										<label for="DropdownTitle">{$DROPDOWN_TITLE}</label>
										<input type="text" id="DropdownTitle" name="dropdown_title" class="form-control">
									</div>
									<div class="form-group">
										<label for="DropdownLocation">{$DROPDOWN_LOCATION}</label>
										<select class="form-control" id="DropdownLocation" name="dropdown_location">
											<option value="1">top</option>
											<option value="2">footer</option>
										</select>
									</div>
									<div class="form-group">
										<label for="DropdownIcon">{$DROPDOWN_ICON}</label>
										<input type="text" id="DropdownIcon" name="dropdown_icon" class="form-control">
									</div>
									<div class="form-group">
										<label for="DropdownOrder">{$DROPDOWN_ORDER}</label>
										<input type="text" id="DropdownOrder" name="dropdown_order" class="form-control">
									</div>
									<div class="form-group">
										<input type="hidden" name="token" value="{$TOKEN}">
										<input type="submit" class="btn btn-primary" value="{$SUBMIT}">
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

			</div>

			{include file='footer.tpl'}

		</div>
	</div>
	<!-- ./wrapper -->

	{include file='scripts.tpl'}

	<script type="text/javascript">
		function showDeleteModal(id) {
			$('#deleteDropdown').attr('href', id);
			$('#deleteModal').modal().show();
		}
	</script>

	<script type="text/javascript">
		function showAddModal() {
			$('#addDropdown').attr('href');
			$('#addModal').modal().show();
		}
	</script>

</body>

</html>