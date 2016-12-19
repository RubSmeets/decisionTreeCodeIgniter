###############################################
MOBILE CROSS-PLATFORM DEVELOPMENT DECISION TOOL
###############################################


*****************
Project Structure
*****************
Application/
    - classes/          contains data structures used throught the code-igniter applcition
    - config/           contains code-igniter configurations
        - autoload.php  libraries that are loaded automatically
        - config.php    code-igniter applciation configurations     
        - database.php  configurations for interaction with MySQL database 
        - routes.php    configure the default route of the applciation
    - controllers/      the code-igniter controllers
    - models/           contains the database interface models
    - views/            the various web pages that can be displayed to the user
        - private/      contains pages that are only visible to logged in users
    
css/                    folder that contains the styles of the views. the files in this folders are compiled version of the sass files
data_manipulation/      legacy scripts for migrating original website to code-igniter
img/                    contains image resources used throughout the website
js/                     the javascript files that power the web applications and communicate with the code-igniter backend
sass/                   the sass style files that are used to style the webpages (views)
vendor/                 external libraries, fonts and styling files that are used in the webpages

This is not the complete structure of the application. But it highlights the folders and files that are modified by me. folders
not mentioned here are still in their default configuration. 

Each folder contains an index.html file for security reasons. Users that try to access the folders are greeted with a forbidden access
web page.

For more information on the modified files and folders. Please navigate to the folder and read the README_****.txt files. these files
contain a short discription of the contents and functionality.

*********
Resources
*********




