# Dads League
Dad's League is an old project which was created to assist my Dad in the creation of a Football Sweepstake, it is running slightly out of date dependencies and hasn't been in use since 2018. I have added DDEV to the project in order to assist in managing it's dependencies and to make it easier to run locally.

## DDEV
DDEV is a tool used to manage docker based development environments, instructions for setup can be ran here: https://ddev.readthedocs.io/en/stable/

## Running the project
The project requires a few key things being seeded into the database in order to work correctly. The steps to do this are as follows:
1. Run `ddev start` to start the project
2. Run `ddev ssh` to SSH into the container
3. Composer install `composer install`
4. NPM install `npm install`
5. Migrate the database `php artisan migrate`
6. Get the teams `php artisan cron:get-teams`
7. Seed in a new season `php artisan db:seed --class=SeasonSeeder`

This should get the project up and running.