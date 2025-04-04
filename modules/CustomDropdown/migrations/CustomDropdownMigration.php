<?php

require_once(__DIR__ . '/BaseMigration.php');

class CustomDropdownMigration extends BaseMigration
{
    public static function up(): void
    {
        if (self::hasTable("custom_dropdown")) {
            return;
        }

        self::createTable("custom_dropdown",
            " `id` int(11) NOT NULL AUTO_INCREMENT, 
              `dropdown_title` varchar(255) NOT NULL, 
              `dropdown_location` int(1) NOT NULL, 
              `dropdown_icon` varchar(255) NOT NULL, 
              `dropdown_order` int(11) NOT NULL, 
              `enabled` int(1) NOT NULL DEFAULT '1', 
              PRIMARY KEY (`id`)"
        );
    }

    public static function down(): void
    {
        self::dropTable("custom_dropdown");
    }
}
