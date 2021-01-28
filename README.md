# Code challenge requirements here:
http://dev2.innvatis.com/CodeChallenge3.pdf

# My project summary
It works...

## My own review:
I´d like to have had more time for completing this project than only the hours you suggested, in order to complete it in a way that I can be "proud" of what I´ve done. But, in other hands, I understand that this is meant for knowing wich decisions the programmer makes and why under pressure, if he sees that time is ticking. So here is what I decided to do.
I wanted to complete the majority of the points, so the best time to do it was using an existent and very popular template from themeforest for the Frontend UI (I used "Packet"). I could have never been able to vomplete it on time starting UI from scatch! Also I choose not to pay attention to code "beautifulness" and optimizations, in order to finish fast. The fact that I used AngularJS helped me a lot,  because of the "double binding" property I was able to write the interface for the game quicly. If you take a look you will se that almost all the code is in less than 400 lines of code lcoated on the file "\assets\js\controllers\gameCtrl.js". Optimization, yes i would have preferred to wrap most of this code in a directive for building the board... but it would have taken much more time. 

So, I had 2 paths to follow here:
1 - doing things beautiful, optimized, fully following frameworks rules and best practices, fully documented, etc. and not having the game "playable" (I estimate that I would have needed more than 15 hours of work for completing frontend, backend API services, documentation and tests in a way that I´d consider acceptable) 
2 - making the game works, no matter if there is still many things to optimize, move and clean.
I personally think that code quality is VERY important for easy maintenance and scalability... I worked more than 3 years for a company whose owner was some kind of obsessed with code quality no matter how much time would be necessary to complete the task... but also clients don´t look at the code, they just want things working... so in this case, with limited time. I have choosen the second option with no hesistation. Optimizations and changes can be always done in a future.


## Time spent

PHP API 2hs approx.
Javascript interface & functions: 4.5 hs approx
Documentation, emails, etc.: 1 hour approx


## Installation:
1 - Download or clone repository.

2 - Create a MySQL database and import the database structure and data from the folder "SQL" located in project root folder.

3 - Find the configuration file "settings.php" in project root and change the following variables, with your local or remote server information:

```
$db_host = 'localhost';                                 // your mySQL host name or IP address

$db_user = 'root';                                      // your MySQL user

$db_pwd = '';                                           // your MySQL password

$db_name = 'minesweeper';                                // the name of your MySQL database

$self_url = 'http://localhost/';                        // root url for your project

$include_url = $_SERVER['DOCUMENT_ROOT']."/";           // root physical path for your project
```

4 - get dependencies  using Bower - bower install (this will create a folder on the root, called "bower_components". If you don´t want to use it this way, you can get a zip file of this folder here: 

http://dev33.innvatis.com/bower_components.zip

5 - Everything should be working now. To navigate to the API endpoints and call the web services, in your Rest Client app (like Postman), make an HTTP Request to "http://MY_HOST_NAME_OR_IP/api/ENDPOINT_NAME"
where "ENDPOINT_NAME" can be found in the API documentation on the .html file "APISpecificationDoc.html" on the root folder of this project.
 
## Live Demo:
You can see a live demo, wich has NOT been tested in mobile devices. You will have to create an account and after that, login to the webpage to be able to play game.

Frontend URL: http://dev33.innvatis.com/

Online API endpoints documentation:  
http://dev3.innvatis.com/APISpecificationDoc.html

You can also test the API endpoints in the online version sending HTTP requests to http://dev3.innvatis.com/api/SOME_ENDPOINT following the API documentation

## BUGS FOUND!
1 - after saving a game sesssion, in order to see it refreshed in the menu list, you have to click the "show saved games" link in order to update the list (it does not happens automatically after saving or creating a new game).

2 - if you create a very big board, maybe more than 30x30, things could go slow... (optimization issues). Even bigger board, could crash on chrome.

## PROJECT STRUCTURE (Javascript & PHP)

PHP API  structure

Controllers: you can find the API controllers in the folder /api/controller. This classes inherit from the SLIM framework controller and here you can find the endpoint routes and functions associated with them.

Models: you can find the model classes in the folder /api/model. This classes inherits from two basic classes for accessing the database and manipulating objects that can be found in the folder /lib.

Views: there is only one view located in the /api/views folder and it is used to render the JSON output from the API responses.

Security: the class that manages the security is found in the /api/Middleware folder as a file called "HttpBasicAuth.php", this class extends from the SLIM Middleware class and basically denies access to the information to any request that is not providing a valid authentication token on his request header.

JAVASCRIPT Project Structure:

AngularJS (1) framework, custom javascript functions written by me (cookies, drawing game etc).

Controllers that I have written:  

\assets\js\controllers\accountCtrl

\assets\js\controllers\gameCtrl

\assets\js\controllers\mainCtrl (modified to show a list of previous saved games)

Directives I Have written:

Only one for managing right click, it is located at the bottom in:
\assets\js\directives\select.js
