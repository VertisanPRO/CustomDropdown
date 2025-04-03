<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/cs-dropdown'));
}
$edit_dropdown = CustomDropdown::first('id', '=', $_GET['id']);
if (empty($edit_dropdown)) {
    Redirect::to(URL::build('/panel/cs-dropdown'));
}
$enabled = ($edit_dropdown->enabled == 1) ? 0 : 1;
CustomDropdown::updateById($_GET['id'], ['enabled' => $enabled]);
Redirect::to(URL::build('/panel/cs-dropdown'));