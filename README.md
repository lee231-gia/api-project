# Goal Tracker CRUD API with Flutter

This is a simple final exam project: a Goal Tracker / Goal Management System.

It has:

- PHP CRUD API
- MySQL database for the main backend data
- Flutter and Dart frontend app
- sqflite local database for saved/offline copy in the app
- Postman collection for API testing
- Simple OOP classes so the code is easier to explain

The code is simple but still separated like the reference Flutter activity. The main custom PHP and Dart files are 599 lines total.

## Important Folder Location

The project is located here:

```text
C:\Users\Admin\Documents\Lyzel\2nd Sem\App Dev\api-project
```

Do not open `C:\Users\Admin\Documents\api_crud` for this project. That folder only has `.git`, so it will look empty.

## Project Idea

The app stores personal goals. A goal can be categorized as Personal, School, Home, or Health. Each goal also has a term:

- Short Term
- Medium Term
- Long Term

Each goal has a status:

- Not Started
- In Progress
- Completed

## Tools Used

PHP is used for the backend API. It receives HTTP requests from Postman or Flutter.

MySQL is used as the main database. The `goals` table stores the actual submitted goals.

Flutter and Dart are used for the frontend mobile/desktop application.

sqflite is used inside Flutter as a local SQLite database. It saves a local copy of goals, so if the API is not available, the app can still show the last saved goals.

Postman is used to test the API endpoints before connecting Flutter.

GitHub is used to submit the repository link.

## How The Project Uses The Reference Codes

From the PHP reference:

- `db.php` is still used for the database connection.
- `index.php` still acts like the router.
- `goals.php` is like the old `products.php`, but the resource is now goals instead of products.
- The API still uses HTTP methods: GET, POST, PUT, DELETE.
- The API still returns JSON.

From the Flutter API activity reference:

- `ApiService` is used to call the API using the `http` package.
- `Goal.fromJson()` is used like `Cat.fromJson()` to convert API JSON into a Dart object.
- `HomePage` loads data in `initState()`.
- The UI uses a dark background, blue app bar, horizontal filter chips, and cards like the cat app.

## Folder Structure

```text
api-project/
  db.php
  index.php
  goals.php
  schema.sql
  run-api.bat
  postman/Goal_Tracker_API.postman_collection.json
  goal_tracker_app/lib/main.dart
  goal_tracker_app/lib/models/goal.dart
  goal_tracker_app/lib/api_services/api_services.dart
  goal_tracker_app/lib/services/local_database.dart
  goal_tracker_app/lib/pages/home.dart
  goal_tracker_app/lib/pages/widgets/goal_card.dart
  goal_tracker_app/lib/pages/widgets/tag_filter_bar.dart
```

## Database Setup

Open MySQL and run:

```sql
SOURCE schema.sql;
```

Or copy the contents of `schema.sql` into phpMyAdmin SQL tab.

The main table is:

```sql
goals(
  id,
  title,
  category,
  term,
  status,
  notes,
  due_date,
  created_at,
  updated_at
)
```

## Environment Setup

Create `.env` from `env.example`:

```text
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=goal_tracker_db
DB_USER=root
DB_PASS=
```

If your MySQL has a password, put it in `DB_PASS`.

## Run The PHP API

From the project folder:

```bash
run-api.bat
```

The command `php -S localhost:8000 index.php` may fail on this computer because PHP is not added to Windows PATH. The file `run-api.bat` fixes that by using `C:\Apps\php\php.exe`.

You can also run this manually:

```bash
C:\Apps\php\php.exe -S localhost:8000 index.php
```

Test in browser:

```text
http://localhost:8000/api/goals
```

## API Endpoints

GET all goals:

```text
GET /api/goals
```

GET one goal:

```text
GET /api/goals?id=1
```

CREATE goal:

```text
POST /api/goals
```

Body:

```json
{
  "title": "Finish CRUD API project",
  "category": "School",
  "term": "Short Term",
  "status": "In Progress",
  "notes": "Practice explaining the code.",
  "due_date": "2026-06-10"
}
```

UPDATE goal:

```text
PUT /api/goals?id=1
```

DELETE goal:

```text
DELETE /api/goals?id=1
```

Filter by category:

```text
GET /api/goals?category=School
```

Search:

```text
GET /api/goals?search=project
```

## Run The Flutter App

Go to the Flutter folder:

```bash
cd goal_tracker_app
flutter pub get
flutter run
```

For Android emulator, the app uses:

```text
http://10.0.2.2:8000/api/goals
```

For Windows desktop, the app uses:

```text
http://localhost:8000/api/goals
```

## Main Code Explanation

`db.php`

- Contains the `Database` class.
- The job of this class is only to connect PHP to MySQL.
- It reads database settings from `.env`.
- It returns a `mysqli` connection.

`index.php`

- This is the entry point of the API.
- It checks the requested URL.
- If the URL is `/api/goals`, it creates `GoalApi` and calls `handle()`.
- This is called routing.

`goals.php`

- Contains the `GoalApi` class.
- This is the main CRUD controller.
- `handle()` checks the HTTP method.
- `read()` handles GET.
- `create()` handles POST.
- `update()` handles PUT.
- `delete()` handles DELETE.
- `goalFromInput()` reads JSON from Postman or Flutter.
- Prepared statements are used to avoid SQL injection.

`goal_tracker_app/lib/main.dart`

- Starts the Flutter app.
- Sets the dark theme.
- Opens `HomePage`.

`goal_tracker_app/lib/models/goal.dart`

- Contains the `Goal` model class.
- Converts JSON from PHP into a Dart object using `Goal.fromJson()`.
- Converts a Dart object back to JSON using `toJson()`.

`goal_tracker_app/lib/api_services/api_services.dart`

- Contains the `ApiService` class.
- Uses the `http` package.
- Sends GET, POST, PUT, and DELETE requests to the PHP API.

`goal_tracker_app/lib/services/local_database.dart`

- Contains the `LocalDatabase` class.
- Uses sqflite.
- Saves a local copy of goals for offline viewing.

`goal_tracker_app/lib/pages/home.dart`

- Contains the main screen.
- Loads goals in `initState()`.
- Controls search, filtering, add, edit, and delete.

`goal_tracker_app/lib/pages/widgets/goal_card.dart`

- Displays one goal in a card.
- Has edit and delete actions.

`goal_tracker_app/lib/pages/widgets/tag_filter_bar.dart`

- Displays horizontal filter chips.
- This follows the same idea as the attached `tag_filter_bar.dart` reference.

## OOP Explanation

OOP means Object-Oriented Programming. In this project, related data and functions are grouped inside classes.

`Database` is an object for database connection.

`GoalApi` is an object for API actions.

`Goal` is an object that represents one goal.

`ApiService` is an object that handles online API requests.

`LocalDb` is an object that handles local sqflite storage.

This is easier than putting all code in one place because each class has one main job.

## What To Say During Presentation

This project is a goal tracker. The backend is a native PHP REST API connected to MySQL. The frontend is made with Flutter and Dart. I also used sqflite in Flutter as a local database cache. I tested the API using Postman. The API supports complete CRUD: create, read, update, and delete goals.

The request flow is:

```text
Flutter or Postman -> PHP API -> MySQL -> PHP API -> JSON response
```

The Flutter app flow is:

```text
Flutter UI -> ApiService -> PHP API -> MySQL
```

If the API fails:

```text
Flutter UI -> LocalDb -> sqflite cache
```

## Common Teacher Questions

What is CRUD?

CRUD means Create, Read, Update, Delete. In this project, POST creates a goal, GET reads goals, PUT updates a goal, and DELETE removes a goal.

What is an API?

An API is a way for two applications to communicate. Here, Flutter talks to PHP using HTTP requests.

Why use MySQL?

MySQL is the main database. It stores goals permanently on the server side.

Why use sqflite?

sqflite is SQLite for Flutter. I used it as a local database/cache, so the app can still show saved goals if the API is offline.

Why use Postman?

Postman lets me test the backend API before connecting it to Flutter. It helps prove that the API works.

What is JSON?

JSON is the data format used by the API. Flutter sends JSON to PHP, and PHP returns JSON back to Flutter.

Why use prepared statements?

Prepared statements protect the database from SQL injection. Instead of directly putting user input in SQL, the values are bound safely.

What is `php://input`?

It reads the raw request body. For POST and PUT, Postman or Flutter sends JSON data, and PHP reads it using `file_get_contents('php://input')`.

What is `json_decode()`?

It converts JSON text into a PHP array.

What is `jsonEncode()` in Dart?

It converts a Dart map/object into JSON text before sending it to PHP.

What is `fromJson()`?

It converts JSON data from the API into a Dart `Goal` object.

What is `setState()`?

`setState()` tells Flutter that data changed and the screen must update.

Why is `10.0.2.2` used?

Android emulator cannot use `localhost` to access the computer. `10.0.2.2` points to the host computer from the emulator.

## GitHub

After testing, push to GitHub:

```bash
git add .
git commit -m "Create simple goal tracker CRUD API and Flutter app"
git push origin main
```

Repository:

```text
https://github.com/kodlens/api-project
```
