<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 1.0.0
Current Version: 1.1.0

Feature Update:
1. Admin Panel Basic Settings Update



Please User Those Command On Your Terminal To Update Full System
.
1. To Run Project Please Run This Command On Your Terminal
    composer update && composer dumpautoload  && php artisan migrate:fresh --seed && php artisan passport:install --force

2. To Update Basic Settings Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\BasicSettingsSeeder
    
3. To Update Site Sections Seeder Please Run This Command On Your Terminal
    php artisan db:seed --class=Database\\Seeders\\Admin\\SiteSectionsSeeder