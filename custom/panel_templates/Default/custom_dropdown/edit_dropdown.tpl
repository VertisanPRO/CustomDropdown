{extends file="custom_dropdown/layouts/main.tpl"}

{block name="card-body"}
    <h5 style="display:inline">{$EDITE_DROPDOWN} <label>{$EDIT_TITLE}</label></h5>
    <div class="float-md-right">
        <a style="display:inline" href="{$BACK_LINK}"
           class="btn btn-warning">{$BACK}</a>
    </div>
    <hr>
    <form action="" method="post">
        <div class="form-group">
            <label for="DropdownTitle">{$DROPDOWN_TITLE}</label>
            <input type="text" id="DropdownTitle" name="dropdown_title"
                   class="form-control" value="{$EDIT_TITLE}">
        </div>
        <div class="form-group">
            <label for="DropdownLocation">{$DROPDOWN_LOCATION}</label>
            <select class="form-control" id="DropdownLocation" name="dropdown_location">
                <option value="1" {if $EDIT_LOCATION == 1} selected{/if}>{$LINK_NAVBAR}
                </option>
                <option value="2" {if $EDIT_LOCATION == 2} selected{/if}>{$LINK_FOOTER}
                </option>
            </select>
        </div>
        <div class="form-group">
            <label for="DropdownIcon">{$DROPDOWN_ICON}</label>
            <input type="text" id="DropdownIcon" name="dropdown_icon"
                   class="form-control" value="{$EDIT_ICON}">
        </div>
        <div class="form-group">
            <label for="DropdownOrder">{$DROPDOWN_ORDER}</label>
            <input type="text" id="DropdownOrder" name="dropdown_order"
                   class="form-control" value="{$EDIT_ORDER}">
        </div>
        <div class="form-group">
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
        </div>
    </form>
{/block}