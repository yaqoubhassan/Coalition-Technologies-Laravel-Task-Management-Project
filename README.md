# Coalition-Technologies-Laravel-Task-Management-Project
Side Note: I used Laravel on the backend and VueJS on the frontend
## Project Setup

Create a .env file in the root folder

Copy and paste the content of .env.example into .env

Create a MySQL database and name it "task_management"


## Run the following commands

```sh
composer install
```

```sh
php artisan key:generate
```

```sh
php artisan migrate
```

```sh
php artisan db:seed
```
The above command will seed the database with a list of projects for easy testing

```sh
npm install
```

```sh
npm run dev
```

```sh
php artisan serve
```
