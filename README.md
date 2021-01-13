1. cd to resume-builder folder
2. run "composer install" to install neccessarily packages
3. run "cp .env.example .env" to make default env data
4. run "php artisan storage:link" to make a symbolic link to public folder
5. run "php artisan cache:clear;php artisan config:cache; php artisan view:clear; php artisan route:clear" to clear cache
6. create a database named "resume_builder"
7. run "php artisan migrate to create tables"
8. run "php artisan db:seed" to import role and admin data
9. run "php artisan serve" to start the project

Login admin to manage all created resumes  
email: admin@admin.com  
password: 12345678