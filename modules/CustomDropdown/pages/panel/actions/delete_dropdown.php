<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    try {
        CustomDropdown::deleteById($_GET['id']);
        CustomDropdownPage::deleteWhere(['dropdown_id', '=', $_GET['id']]);
    } catch (Exception $e) {
        die($e->getMessage());
    }
    Session::flash('staff_cs_dropdown', csd_trans('deleted_successfully'));
    Redirect::to(URL::build('/panel/cs-dropdown'));
}