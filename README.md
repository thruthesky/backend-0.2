# Backend-0.2
Backend server for Restful APIs


# Resources

* The best resource to understand Backend is to read and run the tests.


# TODO


* anonymous cannot login, logout, edit information.
* anonymous can post with password.


## Test on post\_config, post\_post

* Not login user becomes anonymous.

````
config('abc')->countAll()
config('abc')->countPost()
config('abc')->countComment()
config('abc')->timeLastPost();
config('abc')->timeFirstPost();
config('abc')->timeLastComment();
config('abc')->timeFirstComment();
````



## Sample Site

* Simple community site
* Buy and sell / Online shopping mall site.



## Installation

* Once installed, it shouldn't be re-installed unless the user manually removes the database.



## File upload with cosutomizalbe downloda

* `file` table will holds the uploaded file information.
* `file.finish` will be 0 until the file is really related to its object(parent).
	* files with `file.finish=0` becomes 24 hours old, then it will be deleted.

* when a file uploaded, it will return `file.idx`, any of file upload form and its related form should keep the `file.idx` and pass it over the parents' form submission. So, 
* You will upload a file without resizing.
* When you download image, you can customize.
* You can choose image type, width, height, quality, resize type.
	* This will help on image optimization.

**for jpeg**
````
?route=download&type=jpeg&width=80&hegiht=120&quality=100&resize=crop
````

**for png**
````
?route=download&type=png&width=100&height=120&resize=center
````



## Real Message System

To communiate between users.

* Each room has a configuration in `chat_config` table.
* Each chat message is save in `chat_message` table.
* For the same reason of `data integrity` of post, it does not hold `chat_config.time_last_message`. Do SQL query to get it.
* Users and Chat Rooms are N:M relation for group chatting. So, `chat_relation` table will have the relation ship.
* If a user leaves a chat room, then he will loose all the data.
	* When a last user leaves from a chat room, the cat room will be destroyed.
	* This condition perfectly makes it work like facebook chat or kakaotalk.
* For new message indication, everty time a user gets a message from a room, the time of the message will be recorded in `chat_relation.time_of_last_message`. When a user visits(checks) the chat rooms, any chat room has newer message then the `chat_relation.time_of_last_message`, then the room has a new message that the user didn't read.


# Interface

Is more likey a router.



# Permission

When read/write/delete data, it needs security level to limit/allow who can do 'CRUD' of data.

It is stated in interface.


guest = anonymous, not logged in user, visitor.
anyone = anyone where logged in or not logged in.
owner = who owns the data.
admin = admin level user.


# REST API

Only 'route' method can use 'success()' or 'error()'.

Any methods that are not 'route' should return a value.



# Installation

## How to install


````
http://localhost/www/backend-0.2/?route=install
````


## How to code on installation

You can put any php script ending "_install.php" under a module folder.

Those files will be run in installation process.




# Unit Test

Test script under module folder will be run on "all test run", "class test" and "method test".

Test script must end with with "_test.php".


##  Run all tests

To run all the tests, just access

````
?route=test
````


## Run a test class



To test class

````
?route=model.class
````


## Run a test method



To test class

````
?route=model.class.method
````




# Class Hierarchy


````
Base => Taxonomy
Baes => Taxonomy => Entity
Base => Taxonomy => Entity => User
Base => Taxonomy => Entity => Meta
Base => Taxonomy => Entity => Meta => Config
Base => Taxonomy => Entity => Forum => Config
Base => Taxonomy => Entity => Forum => Post
Base => Taxonomy => Entity => File
````


# Security

## HTTP Input Variable Check

The "run_route()" will check the input of the HTTP as stated in the route.

In a route, you might see the `variables`.

````
add_route('register', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'register',
    'variables' => [
        'required' => [ 'id', 'password' ],
        'optional' => [ 'domain', 'name', 'middle_name', 'last_name',
            'nickname', 'email', 'gender', 'birth_year', 'birth_month', 'birth_day', 'landline',
                        'mobile', 'address', 'country', 'province', 'city', 'zipcode' ],
        'system' => [ 'route' ]
    ]
]);
````

The `variables` states what variables the interface accepts.

* If any variables that are not states above has delivered to interface, then the `run_route()` will reject with error.
* The variables of `required` in `variables` are required. If any of the required variables is missing, then `run_route()` will reject the request with error.
* `optional` variables are optional.
* `system` variables are the variables that are used by the system and interface. `system` variables are not related in content like user information, forum data and any kind of data on database. 
* `required` and `optional` variables are related in content( data, database data). Any data like user information, forum posts, logs that shuold be saved in the database must be in one of `required` or `optional` variables.
* The input variable `route` can be omitted since all access needs a route.






## HTTP variable type checking

* For security enhancement, types of HTTP variable are checked by the system.

* Since many of HTTP variables and routers have same(similiar) names and types, it may be one good idea to check type of HTTP variables in one place. 

See `check_http_variables_type()` for details.





# Interface

Interfaces are the methods that are directly called by API call.

All interfaces must be recorded in `{module_name}_interface.php`

For instance

````
model/user/user_interface.php
````




# Data Relation and Database


## User

### Anonymous User.

Users who are not logged in with their ID and password will login as anonymous. Meaning, All users are logged in as anonymous when they first visit the site.


* Anonymous is a user who did not log in with his password but treated as logged in.
* Anonymous user cannot login, logout, edit his information.
* But can post/edit/delete with password.

## meta table

Meta table holds meta datas.

It has model, model_idx, code and value fields plus idx, created and updated.

### model

meta.model field is for a big category like "user", "forum", "file".

### model_idx

meta.model_idx is to associate the meta data with the object(entity/record) of the model.


### code
meta.code is a sub-category for the meta. It would be a property of a entity like "facebook address", "google plus address" of a user.



## Post

Post can be served in many ways like forum posts, blog posts, group( cafe ) posts, etc. So, the name of the functionality shouldn't be something like 'forum'. Instead, It should be a simple `post` to serve variety of functionality.



### post_config table

Post categories ( or settings ) are saved in `post_config` table.


### post_data table

Posts are saved in `post_data` table.
Comments are saved in `post_data` table together with post for easy managibility and for easy search.


### Utility properties.

`post_config` table does not has a field like `post_config.no_of_posts` to hold the number of posts due to the `data integrity`. For instance, you can get no of posts easy by querying `SELECT COUNT(*) FROM post_data WHERE idx_config=123` and it is fast enough.

And for the same reason, `post_config` table has no field like `post_config.time_of_last_post` to maintain when was the time that a post created on that post category. You can query it and it is really fast enough.

MySQL/MariaDB has query cache funtionality also.

Having extra fields to keep post information may take extra care and often produce bugs that break `data integrity`.




# Constants

## Okay and ERROR Constant

Among other constant, there is one thing to notice. That's OK and ERROR.

````
define('OK', 0);
define('ERROR', FALSE);
````

OK tells an action was success while ERROR tells the action was an error.

But both have falsy value. Meaning when you code like below

	if ( OK ) { ... }  // this will NOT run.
	else { ... }       // this WILL run.


so, you need to use `===` to compare it was success or error. `is_error()`, `is_success()` handles this nicely.




# Meta_Injector


Meta Injecotr is a handy method to manage mata data of an entity. When `meta()` method is called, it creates an object of Meta_Injector with the model and model\_idx of the entity and returns the object.


````
user( 'abc' )->meta()->get();        // gets all meta data of user 'abc'
post( 1 )->meta()->get('birthday');  // gets bitrh meta data of post no 1.
user( 'abc' )->meta()->set([...]);   // sets an array of meta to user 'abc'.
config( 33 )->meta()->set( 'title', '...' ); // sets title meta to post config 33.
user( 'def' )->meta()->delete();     // delete all meta of user 'def'
user( 'def' )->meta()->delete( 'birthday' ); // delete birthday meta of user 'def'.
````

