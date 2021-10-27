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
						<div class="container-fluid" id="content">
							<div class="card">
								<div class="card-body">
									<h5 style="display:inline">{$DROPDOWN_NAME}</h5>
									<div class="float-md-right">
										<a style="display:inline" href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
									</div>
								</div>
							</div>
							{if count($DEFAULT_PAGES)}

								<label for="type">{$SELECT_PAGE}</label>
								<form class="form-inline" role="form" action="" method="post">
									<div class="form-group">

										<select class="form-control mr-sm-2" name="addLink">

											{foreach from=$DEFAULT_PAGES item=df_pages}
												<option value="{$df_pages.id}">{$df_pages.title}</option>
											{/foreach}

											<input type="hidden" name="token" value="{$TOKEN}">
											<input type="hidden" name="order" value="{$DROPDOWN_ORDER + 1}">
											<input type="submit" class="btn btn-success" value="{$ADD_PAGE}">

										</select>
									</div>
								</form>

							{else}
								{$NO_CREATED_PAGES}
							{/if}

							{if count($IFRAME_PAGES)}
								<label for="type">{$SELECT_IFRAME_PAGE}</label>
								<form class="form-inline" role="form" action="" method="post">
									<div class="form-group">

										<select class="form-control mr-sm-2" name="addIframe">


											{foreach from=$IFRAME_PAGES item=if_pages}
												<option value="{$if_pages.id}">{$if_pages.title}</option>
											{/foreach}

											<input type="hidden" name="token" value="{$TOKEN}">
											<input type="submit" class="btn btn-success" value="{$ADD_PAGE}">

										</select>
									</div>
								</form>

							{/if}

							<br>

							{if count($DROPDOWN_PAGES)}
								<div class="table-responsive">
									<table class="table table-striped">
										<tbody>
											{foreach from=$DROPDOWN_PAGES item=dropdown_page}
												<tr>
													<td>
														<strong>{$dropdown_page.title}</strong>
													</td>
													<td>
														<div class="float-md-right form-inline">

															<a class="btn btn-warning btn-sm mr-sm-2" title="{$EDIT}" href="{$dropdown_page.edit_link}"><i
																	class="fas fa-edit fa-fw"></i></a>

															<form action="" method="post">

																<div class="form-group mr-sm-2">
																	<input type="hidden" name="token" value="{$TOKEN}">
																	<input type="hidden" class="btn btn-primary" name="deletePage"
																		value="{$dropdown_page.id}">
																	<button class="btn btn-danger btn-sm" title="{$REMOVE}" type="button" data-toggle="modal"
																		data-target="#confirm-submit{$dropdown_page.id}"><i
																			class="fas fa-trash fa-fw"></i></button>
																</div>

																<!-- Delete confirm modal -->
																<div class="modal fade" id="confirm-submit{$dropdown_page.id}" tabindex="-1" role="dialog"
																	aria-labelledby="myModalLabel">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																				<h2 class="modal-title">{$ARE_YOU_SURE}</h2>
																			</div>
																			<div class="modal-body">
																				<p>{$CONFIRM_DELETE}</p>
																			</div>
																			<div class="modal-footer">
																				<button type="button" class="btn btn-danger" data-dismiss="modal">{$NO}</button>
																				<button type="submit" class="btn btn-success">{$YES}</button>
																			</div>
																		</div>
																	</div>
																</div>

															</form>

														</div>
													</td>
												</tr>
											{/foreach}
										</tbody>
									</table>
								</div>
							{/if}

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