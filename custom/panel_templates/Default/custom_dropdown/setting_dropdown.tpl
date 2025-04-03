{extends file="custom_dropdown/layouts/main.tpl"}

{block name="card-body"}
    <button class="btn btn-primary" type="button" onclick="showAddModal()">
        {$ADD_PAGE} <i class="fa fa-plus-circle"></i>
    </button>
    <div class="float-md-right">
        <a style="display:inline" href="{$BACK_LINK}"
           class="btn btn-warning">{$BACK}</a>
    </div>

    <hr>
    {if !empty($DROPDOWN_PAGES)}
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                {foreach from=$DROPDOWN_PAGES item=dropdown_page}
                    <tr>
                        <td>
                            <strong>{$dropdown_page.title}</strong>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end align-items-center">
                                <a class="btn btn-warning btn-sm mr-2" title="{$EDIT}"
                                   href="{$dropdown_page.edit_link}">
                                    <i class="fas fa-edit fa-fw"></i>
                                </a>
                                <form action="" method="post" class="mb-0">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="hidden" name="deletePage" value="{$dropdown_page.id}">
                                    <button class="btn btn-danger btn-sm" title="{$REMOVE}" type="button"
                                            data-toggle="modal"
                                            data-target="#confirm-submit{$dropdown_page.id}">
                                        <i class="fas fa-trash fa-fw"></i>
                                    </button>

                                    <div class="modal fade" id="confirm-submit{$dropdown_page.id}" tabindex="-1"
                                         role="dialog" aria-labelledby="confirmModalLabel{$dropdown_page.id}"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title"
                                                        id="confirmModalLabel{$dropdown_page.id}">{$ARE_YOU_SURE}</h2>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{$CONFIRM_DELETE}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">{$NO}</button>
                                                    <button type="submit"
                                                            class="btn btn-success">{$YES}</button>
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

    <!-- Modal Add -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{$SELECT_PAGE}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-inline w-100" role="form" action="" method="post">
                        <div class="form-group mr-2 flex-grow-1 w-70">
                            <select class="form-control w-100" id="addLinkSelect" name="addLink">
                                {foreach from=$DEFAULT_PAGES item=df_pages}
                                    <option value="{$df_pages.id}">{$df_pages.title}</option>
                                {/foreach}
                            </select>
                        </div>
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="order" value="{$DROPDOWN_ORDER + 1}">
                        <button type="submit" class="btn btn-success mt-2 mt-sm-0">{$ADD_PAGE}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/block}

{block name="scripts"}
{literal}
    <script type="text/javascript">
        function showAddModal() {
            $('#addModal').modal().show();
        }
    </script>
{/literal}
{/block}
