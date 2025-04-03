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
                    <h1 class="h3 mb-0 text-gray-800">{$TITLE}</h1>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        {include file='includes/alerts.tpl'}
                        {block name="card-body"}{/block}
                    </div>
                </div>

            </div>
        </div>

        {include file='footer.tpl'}
    </div>
</div>

{include file='scripts.tpl'}
{block name="scripts"}{/block}

</body>
</html>
