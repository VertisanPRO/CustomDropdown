<?php

class CustomDropdownPagesMigration extends BaseMigration
{
    public static function up(): void
    {
        if (self::hasTable("custom_dropdown_pages")) {
            return;
        }

        self::createTable("custom_dropdown_pages",
            " `id` int(11) NOT NULL AUTO_INCREMENT, 
              `dropdown_id` int(11) NOT NULL, 
              `page_title` varchar(255) NOT NULL, 
              `page_link` varchar(512) NOT NULL, 
              `page_location` varchar(255) NOT NULL DEFAULT 'top', 
              `page_target` varchar(20) DEFAULT NULL, 
              `page_icon` varchar(255) DEFAULT NULL, 
              `page_order` int(11) DEFAULT '0', 
              `default_page_id` int(11) NOT NULL DEFAULT '0', 
              PRIMARY KEY (`id`)"
        );
    }

    public static function down(): void
    {
        self::dropTable("custom_dropdown_pages");
    }
}
