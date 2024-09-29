
# Task Management System

a task management system where users can create, update, delete, and
list tasks. The system should be built using Laravel, connected to a MySQL
database for most CRUD operations, and integrated with Firestore to store real-
time updates of task statuses.


## setup locale
* first step
```bash
cp .env.example .env
```

```bash
composer install
```
 
 * second step
update ".env" file with your database credentials

* third step
```bash
php artisan migrate 
```

```bash
php artisan db:seed UserSeeder
```
* fourth step

update the file "storage\app\credentials\task-management-firebase-credentials.json"  with your firestore credentials

* last step

```bash
php artisan queue:work
```

```bash
php artisan serve
```


###### API postman documentation  in the file "API_Collection.postman_collection" 
#### developed by Tarek Badry 
