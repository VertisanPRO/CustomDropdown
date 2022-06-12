<?php

class CustomDropdown_Module extends Module
{

  private $_language, $csLanguage;

  public function __construct($language, $pages, $INFO_MODULE)
  {
    $this->_language = $language;

    $this->csLanguage = $GLOBALS['csLanguage'];

    $name = $INFO_MODULE['name'];
    $author = $INFO_MODULE['author'];
    $module_version = $INFO_MODULE['module_ver'];
    $nameless_version = $INFO_MODULE['nml_ver'];
    parent::__construct($this, $name, $author, $module_version, $nameless_version);

    $pages->add('CustomDropdown', '/panel/cs-dropdown', 'pages/panel/index.php');
  }

  public function onInstall()
  {
    $this->initialise();
  }

  public function onUninstall()
  {
  }

  public function onEnable()
  {
    $this->initialise();
  }

  public function onDisable()
  {
  }

  public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template)
  {

    PermissionHandler::registerPermissions('CustomDropdown', array(
      'admincp.csdropdown' => $this->csLanguage->get('general', 'group_permision')
    ));

    if (defined('FRONT_END')) {

      $queries = new Queries();


      $dropdown_list = $queries->getWhere('custom_dropdown', array('id', '<>', 0));
      if (count($dropdown_list)) {

        foreach ($dropdown_list as $var) {

          // Add CustomDropdown
          $name = Output::getClean($var->id);
          $title = Output::getClean($var->dropdown_title);
          $location_var = $var->dropdown_location;
          $icon = $var->dropdown_icon;
          $order = $var->dropdown_order;

          if ($var->enabled == 0) {
            continue;
          }

          if ($location_var == 2) {
            $location = 'footer';
          } else {
            $location = 'top';
          }

          $navs[0]->addDropdown($name, $title, $location, $order, $icon);
        }
      }

      $dropdown_pages_list = $queries->getWhere('custom_dropdown_pages', array('id', '<>', 0));
      if (count($dropdown_pages_list)) {

        foreach ($dropdown_pages_list as $var) {

          // Add "More" dropdown pages
          $dropdown = Output::getClean($var->dropdown_id);
          $name = Output::getClean($var->id);
          $title = Output::getClean($var->page_title);
          $link = URL::build($var->page_link);
          $location = $var->page_location;
          $target = $var->page_target;
          $icon = (string) $var->page_icon;
          $order = $var->page_order;

          $navs[0]->addItemToDropdown($dropdown, $name, $title, $link, $location, $target, $icon, $order);
        }
      }
    } else if (defined('BACK_END')) {

      $icon = '<i class="nav-icon fas fa-caret-square-down"></i>';
      $cs_dropdown_title = $this->csLanguage->get('general', 'title');


      if ($user->hasPermission('admincp.csdropdown')) {
        $order = end($navs[2]->returnNav())['order'] + 1.1;
        $navs[2]->add('cs_dropdown_divider', mb_strtoupper($cs_dropdown_title, 'UTF-8'), 'divider', 'top', null, $order, '');
        $navs[2]->add('cs_dropdown_items', $cs_dropdown_title, URL::build('/panel/cs-dropdown'), 'top', null, $order + 0.1, $icon);
      }
    }
  }

  public function initialise()
  {
    $queries = new Queries();

    try {

      $queries->createTable("custom_dropdown", " `id` int(11) NOT NULL AUTO_INCREMENT, `dropdown_title` varchar(255) NOT NULL, `dropdown_location` int(1) NOT NULL, `dropdown_icon` varchar(255) NOT NULL, `dropdown_order` int(11) NOT NULL, `enabled` int(1) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=utf8");

      $queries->createTable("custom_dropdown_pages", " `id` int(11) NOT NULL AUTO_INCREMENT, `dropdown_id` int(11) NOT NULL, `page_title` varchar(255) NOT NULL, `page_link` varchar(512) NOT NULL, `page_location` varchar(255) NOT NULL DEFAULT 'top', `page_target` varchar(20) DEFAULT NULL, `page_icon` varchar(255) DEFAULT NULL, `page_order` int(11) DEFAULT '0', `default_page_id` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=utf8");
    } catch (Exception $e) {
      echo $e->getMessage();
    }

    try {

      $group = $queries->getWhere('groups', array('id', '=', 2));
      $group = $group[0];

      $group_permissions = json_decode($group->permissions, TRUE);
      $group_permissions['admincp.csdropdown'] = 1;

      $group_permissions = json_encode($group_permissions);
      $queries->update('groups', 2, array('permissions' => $group_permissions));
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  public function getDebugInfo(): array {
    return [];
  }
}
