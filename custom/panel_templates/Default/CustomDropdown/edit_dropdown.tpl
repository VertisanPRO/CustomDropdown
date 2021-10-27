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

					<!-- Main content -->
					<section class="content">
						<div class="container-fluid">
							<div class="card">
								<div class="card-body">
									<h5 style="display:inline">{$EDITE_DROPDOWN} <label>{$EDIT_TITLE}</label></h5>
									<div class="float-md-right">
										<a style="display:inline" href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
									</div>

									<hr>

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

									<form action="" method="post">
										<div class="form-group">
											<label for="DropdownTitle">{$DROPDOWN_TITLE}</label>
											<input type="text" id="DropdownTitle" name="dropdown_title" class="form-control"
												value="{$EDIT_TITLE}">
										</div>
										<div class="form-group">
											<label for="DropdownLocation">{$DROPDOWN_LOCATION}</label>
											<select class="form-control" id="DropdownLocation" name="dropdown_location">
												<option value="1" {if $EDIT_LOCATION == 1} selected{/if}>{$LINK_NAVBAR}</option>
												<option value="2" {if $EDIT_LOCATION == 2} selected{/if}>{$LINK_FOOTER}</option>
											</select>
											<!-- <input type="text" id="DropdownLocation" name="dropdown_location" class="form-control" value="{$EDIT_LOCATION}"> -->
										</div>
										<div class="form-group">
											<label for="DropdownIcon">{$DROPDOWN_ICON}</label>
											<input type="text" id="DropdownIcon" name="dropdown_icon" class="form-control"
												value="{$EDIT_ICON}">
										</div>
										<div class="form-group">
											<label for="DropdownOrder">{$DROPDOWN_ORDER}</label>
											<input type="text" id="DropdownOrder" name="dropdown_order" class="form-control"
												value="{$EDIT_ORDER}">
										</div>
										<div class="form-group">
											<input type="hidden" name="token" value="{$TOKEN}">
											<input type="submit" class="btn btn-primary" value="{$SUBMIT}">
										</div>
									</form>
								</div>
							</div>

						</div>
					</section>
				</div>
			</div>

			{include file='footer.tpl'}

		</div>

	</div>
	<!-- ./wrapper -->

	{include file='scripts.tpl'}

</body>

</html>