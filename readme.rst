###############################################
MOBILE CROSS-PLATFORM DEVELOPMENT DECISION TOOL
###############################################
This website provides an overview of the cross-platform development tools that are still available and the ones that have been discontinued. The website currently provides
4 web pages. Each of these pages has its own functionality:

- LANDING PAGE 

Makes use of the bootstrap starter template found here: https://startbootstrap.com/template-overviews/creative/.
This page provides a clear description of the website's functionality and provides the user with 2 options on how to proceed with working with the tool.
Additionally, the landing page contains: contact information, project discription, licensing, etc??

- SEARCH TOOL PAGE

A tool that can filter the list of tracked frameworks by applying filter checkboxes or by searching for a particular framework by name. This web page is
based on an existing web page that can be found here: http://mobile-frameworks-comparison-chart.com/. Our page loads the initial framework data from the
database in one Ajax request and creates the markup in javascript before appending it to the thumbnail container DIV element. Once the thumbs have been
added. The popularity numbers are loaded in the background and inserted into the placeholders.

The filtering mechanism works completely client-side by searching for classes that have been added to the individual thumbs. Hiding and showing is done
with jQuery hide and show functions.

Finally, this page provides a simple mechanism to compare frameworks by checking the compare-checkbox on each framework thumb they want to compare in detail.
The user can choose to navigate to the compare page either by clicking the main compare button (in header) or by clicking the compare button in the framework
thumb they have selected.

Technologies used here are: bootstrap for layouting and styling. Javascript/jQuery for filtering and loading data from backend. Sass for overwriting
bootstrap styles.

- COMPARE FRAMEWORKS PAGE

The compare page provides a side-by-side overview of complete framework data that is available in the database. The framework data is organised in categories
and shown in simple tables (created with spans, bootstrap collapse panels, and background colors). The frameworks the user has selected from
the SEARCH TOOL PAGE are supplied on navigation as url parameters. These parameters are extracted on page ready and are loaded through AJAX in the background.
The loaded data is pre-formatted (meaning markup included) and only requires to be appended to the correct container DIV element. (BAD practice!!)

The user can add/delete frameworks to/from the comparison by either clicking the ADD button or the delete button. Adding a framework is done by selecting
one from the jQuery dataTable modal overlay.

Technologies used here are: bootstrap for layouting and styling. Javascript/jQuery for loading data from backend and handling user events. Sass for overwriting
bootstrap styles. jQuery dataTable for displaying a list of available frameworks that can be added to comparison.

- CONTRIBUTE PAGE

A user can contribute to the project by adding new information or keeping existing information up to date. The contribution page takes care of this functionality.
The user is presented a simple navigation tab button. Which allows to switch between adding a new framework/tool or edit an existing one. Both of the actions
require a large form that is split into steps to make it easily digestable. (5 steps to be exact)

--- ADDING A NEW FRAMEWORK VIEW
The user can fillout the form with information gathered from internet/experience. Each field is evaluated at runtime and provides feedback to the user whether or
not the supplied information is correct (format). Navigating between the form steps evaluates the complete form step and notifies the user if the previous step
was OK or NOT. The user can only submit the form to the backend if all the steps are evaluated correctly. Navigation between the form steps is done by either using
the two nav buttons at the bottom of the form or by clicking the progressbar/breadcrumb at the top of the form. If all data is correct, the user can submit the
contribution and feedback is provided about the submission (feedback inside bootstrap modal overlay). On successful submission the contribution is waiting on an
admin review before it is merged into the system.

--- EDITING AN EXISTING FRAMEWORK OR PENDING CONTRIBUTION VIEW
The other option we can do on the contribute page is editing exisiting framework data or pending contribution. The user can navigate to the editing action by selecting
edit in the segmented button on the top-side of the page. The user is first presented with a view that contains three jQuery dataTables. The first table contains a
list of frameworks that are currently approved and can be edited. The second table contains a list of contribution the user has made that are currently queued for
review by an admin. This includes new framework contributions as edits of an existing framework. The last table at the bottom contains the contributions from the user
that have been reviewed (approved or declined). The user can select an item out of each of these three tables and can start editing the entry in the form. selecting
an entry will issue a request to the backend to retrieve all the information of the framework. This information is injected into the form (each step) and the form gets
evaluated. After evaluation the form is shown to the user, which can then start making changes to the data (similar to the add new framework action). If the user is
satisfied with his/her modifications he/she can resubmit the form and wait for approval from an admin. 

--- MANAGE USERS VIEW (ADMIN ONLY)

An admin can view all the REGULAR users (non admins) and can choose to block or unblock them if he/she sees fit. This may be necessary if inappropriate conduct is
observed by a particular user. The manage users page contains three jQuery datatables with user information. The first table shows all the active users that or not
blocked, the second table shows all the blocked users, and the third table shows all the contributions a selected user has made. (selected form the first two tables).

--- MANAGE CONTRIBUTIONS VIEW (ADMIN ONLY)

Admins have the right to review a submitted contribution and make changes to the data before approving or declining the contribution. This is done by first presenting
a jquery table with all the pending contributions of all the active users. The admin can then select a contribution which is loaded from the backend together with
the referenced framework if available (not available if it is a new contribution). The referenced framework is the currently active data that is used for comparison
during the review process. This way the admin can determine if the contributions made by a user are useful and valid. Underlying differences between the referenced
framework and contributed changes are highlighted on the screen in YELLOW. Admins can then choose to decline or approve a framework with an optional feedback message
for the contributor. If the framework is approved, the referenced framework is marked as outdated and the new contribution is used in the whole website.

Technologies used on this page are: jquery dataTables, bootstrap for layout and styling, Javascript/jquery for loading/validating/navigation and handling user events,
sass for overwriting bootstrap styles. The bootstrap-validator plugin for form validation.

NOTE: admins are manually set in the database. Each regular user has a field in the database that specifies if it is an admin or not. The database maintainer or admin
(project maintainers) can manually set this field in the database to make a regular user the admin of the webpage. There can be multiple admins and there is no other way
of setting/clearing an admin. 


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

****
TODO
****
Frameworks to add:
    - Haxe http://haxe.org/ (cross-compiler)

Website TODO:
    - add framework discription (database, contribute page, compare page) --> see decision-tree/research folder in main crossmos project folder. There are some descriptions of frameworks that I have allready gathered and written
    - SEE GITHUB ISSUES WITH CURRENT FEATURES THAT STILL HAVE TO BE DEVELOPED


*********
Resources
*********




