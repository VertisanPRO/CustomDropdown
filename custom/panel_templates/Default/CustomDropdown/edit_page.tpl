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
									</hr>

									<form action="" method="post">
										<div class="form-group">
											<label for="DropdownTitle">{$PAGE_TITLE}</label>
											<input type="text" id="DropdownTitle" name="page_title" class="form-control"
												value="{$EDIT_TITLE}">
										</div>
										<div class="form-group">
											<label for="PageLink">{$PAGE_LINK}</label>
											<input type="text" id="PageLink" name="page_link" class="form-control" value="{$EDIT_LINK}">
										</div>
										<div class="form-group">
											<label for="PageTarget">{$PAGE_TARGET}</label>
											<select class="form-control" id="PageTarget" name="page_target">
												<option value="0">{$NO}</option>
												<option value="1">{$YES}</option>
											</select>
										</div>
										<div class="form-group">
											<label for="PageIcon">{$PAGE_ICON}</label>
											<input type="text" id="PageIcon" name="page_icon" class="form-control" value="{$EDIT_ICON}">
										</div>
										<div class="form-group">
											<label for="PageOrder">{$PAGE_ORDER}</label>
											<input type="text" id="PageOrder" name="page_order" class="form-control" value="{$EDIT_ORDER}">
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