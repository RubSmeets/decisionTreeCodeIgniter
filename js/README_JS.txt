/* -----------------------------------------*/

1) The JS folder contains all the custom JavaScript files that belong to a particular view (html file). The
names of the main JavaScript files are the same as the names of the corresponding view file.
e.g. application/views/searchTool.php --> js/searchTool/searchTool.js 

2) Views (pages) that contain multiple seperate JavaScript files are bundled inside a folder with the same
name as the view.

3) The private folder contains the JavaScript files of the private views (only visible when logged in)
while the publicly accessable views and corresponding JavaScript files are in the root folders of their
respective type. 
e.g.:
- js/ --> contains JavaScript files of public pages
- js/private --> contains JavaScript files of private pages (requires login)  

/* -----------------------------------------*/