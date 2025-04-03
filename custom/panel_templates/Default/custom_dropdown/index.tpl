{extends file="custom_dropdown/layouts/main.tpl"}

{block name="card-body"}
    <div class="float-md">
        <button class="btn btn-primary" type="button" onclick="showAddModal()">
            {$ADD_NEW_DROPDOWN} <i class="fa fa-plus-circle"></i>
        </button>
    </div>
    <hr>
    {if !empty($DROPDOWN_LIST)}
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{$DROPDOWN_TITLE}</th>
                    <th>{$DROPDOWN_LOCATION}</th>
                    <th>{$DROPDOWN_ORDER}</th>
                    <th class="text-right">{$SETTINGS}</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$DROPDOWN_LIST item=dropdown}
                    <tr>
                        <td>
                            <strong>{$dropdown.dropdown_title}</strong>
                        </td>
                        <td>
                            <strong>{if $dropdown.dropdown_location == 1}Top{else}Bottom{/if}</strong>
                        </td>
                        <td>
                            <strong>{$dropdown.dropdown_order}</strong>
                        </td>
                        <td>
                            <div class="float-md-right">
                                {if $dropdown.enabled == 1}
                                    <a class="btn btn-success btn-sm" href="{$dropdown.enabled_link}">
                                        <i class="fa fa-toggle-on" aria-hidden="true"></i>
                                    </a>
                                {else}
                                    <a class="btn btn-danger btn-sm" href="{$dropdown.enabled_link}">
                                        <i class="fa fa-toggle-off" aria-hidden="true"></i>
                                    </a>
                                {/if}
                                <a class="btn btn-primary btn-sm" title="{$SETTINGS}" href="{$dropdown.setting_link}">
                                    <i class="fa fa-cogs" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-warning btn-sm" title="{$EDIT}" href="{$dropdown.edit_link}">
                                    <i class="fas fa-edit fa-fw"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" title="{$REMOVE}" type="button"
                                        onclick="showDeleteModal('{$dropdown.delete_link}')">
                                    <i class="fas fa-trash fa-fw"></i>
                                </button>
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

    <!-- Modal Delete -->
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
    <!-- Modal Add -->
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
                                <option value="1">Top</option>
                                <option value="2">Bottom</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="DropdownIcon">{$DROPDOWN_ICON}</label>
                            <input type="text" id="DropdownIcon" name="dropdown_icon" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="DropdownOrder">{$DROPDOWN_ORDER}</label>
                            <input type="text" id="DropdownOrder" name="dropdown_order" value="6" class="form-control">
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
{/block}

{block name="scripts"}
    <script type="text/javascript">
        function showDeleteModal(id) {
            $('#deleteDropdown').attr('href', id);
            $('#deleteModal').modal().show();
        }

        function showAddModal() {
            $('#addModal').modal().show();
        }
    </script>
{/block}
