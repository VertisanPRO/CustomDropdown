<?php

class CustomDropdown_Module extends Module
{
    public const ID = 'csd';
    public const NAME = 'CustomDropdown';
    public const VERSION = '1.3.0';
    public const NML_VER = '2.2.0';
    public const AUTHOR = '<a href="https://github.com/VertisanPRO" target="_blank" rel="nofollow noopener">Vertisan</a>';

    public function __construct()
    {
        parent::__construct($this, self::NAME, self::AUTHOR, self::VERSION, self::NML_VER);

        // Register the Page for Administration Panel
        global $pages;
        $pages->add(self::NAME, '/panel/cs-dropdown', 'pages/panel/index.php');
    }

    public function onInstall(): void
    {
        $this->initialise();
    }

    public function onEnable(): void
    {
        $this->initialise();
    }

    public function onUninstall(): void
    {
        // Remove tables when module removed
        CustomDropdownMigration::down();
        CustomDropdownPagesMigration::down();
        Log::getInstance()->log('custom_dropdown', 'Custom Dropdown module uninstall successfully.');
    }

    public function onDisable(): void
    {
        Log::getInstance()->log('custom_dropdown', 'Custom Dropdown module disable successfully.');
    }

    public function onPageLoad(User $user, Pages $pages, Cache $cache, $smarty, iterable $navs, Widgets $widgets, TemplateBase $template): void
    {
        PermissionHandler::registerPermissions(self::NAME, [
            'admincp.csdropdown' => csd_trans('group_permission')
        ]);

        if (defined('FRONT_END')) {
            $dropdown_list = CustomDropdown::allWithRelations(true);
            foreach ($dropdown_list as $dropdown) {
                $location = ($dropdown->dropdown_location == 2) ? 'footer' : 'top';
                $navs[0]->addDropdown(
                    $dropdown->id,
                    csd_trans($dropdown->dropdown_title, $dropdown->dropdown_title),
                    $location,
                    $dropdown->dropdown_order,
                    $dropdown->dropdown_icon
                );
                foreach ($dropdown->pages as $page) {
                    $intersection = array_intersect($page->allowed_groups, $user->getAllGroupIds());
                    if (!empty($intersection)) {
                        $navs[0]->addItemToDropdown(
                            $dropdown->id,
                            $page->id,
                            csd_trans($page->page_title, $page->page_title),
                            URL::build($page->page_link),
                            $location,
                            $page->page_target ? '_blank' : null,
                            (string)$page->page_icon,
                            $page->page_order
                        );
                    }
                }
            }
        } elseif (defined('BACK_END')) {
            if ($user->hasPermission('admincp.csdropdown')) {
                $icon = '<i class="nav-icon fas fa-caret-square-down"></i>';
                $cs_dropdown_title = csd_trans('title');
                $navs[2]->add('cs_divider', $cs_dropdown_title, 'divider', 'top', null, 10.8, '');
                $navs[2]->add('cs_dropdown', $cs_dropdown_title, URL::build('/panel/cs-dropdown'), 'top', null, 10.8, $icon);
            }
        }
    }

    public function initialise(): void
    {
        try {
            // Creating tables
            CustomDropdownMigration::up();
            CustomDropdownPagesMigration::up();

            // Updating rights for a group admin
            $group = Group::find(2);
            if ($group) {
                $group_permissions = json_decode($group->permissions, true);
                $group_permissions['admincp.csdropdown'] = 1;
                DB::getInstance()->update('groups', 2, ['permissions' => json_encode($group_permissions)]);
            }
            Log::getInstance()->log('custom_dropdown', 'Custom Dropdown module enable successfully.');
        } catch (Exception $e) {
            Log::getInstance()->log('custom_dropdown', 'Error during module installation: ' . $e->getMessage());
        }
    }

    public function getDebugInfo(): array
    {
        return [
            'all_dropdowns' => CustomDropdown::all(),
            'all_dropdown_pages' => CustomDropdownPage::all(),
            'all_dropdowns_active' => CustomDropdown::allActive(),
            'all_dropdown_relations' => CustomDropdown::allWithRelations(false)
        ];
    }
}
