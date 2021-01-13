1. git clone https://ecwj@bitbucket.org/ecwj/resume-builder.git
2. cd to resume-builder folder
3. run "composer install" to install neccessarily packages
4. run "cp .env.example .env" to make default env data
5. run "php artisan storage:link" to make a symbolic link to public folder
6. run "php artisan cache:clear;php artisan config:cache; php artisan view:clear; php artisan route:clear" to clear cache
7. create a database named "resume_builder"
8. run "php artisan migrate to create tables"
9. run "php artisan db:seed" to import role data
10. run "php artisan serve" to start the project