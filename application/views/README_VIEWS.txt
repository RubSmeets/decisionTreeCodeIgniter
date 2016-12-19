/* -----------------------------------------*/

The views folder is located under the code-igniter application folder and contains the various pages of our website. In
code-igniter we can use controllers to redirect users between the available views. Currently our website has 4 unique
views, being:
- index.php (landing page that provides basic information and navigation between searchTool and wizard (not yet implemented))
- searchTool.php (Search tool that allows the user to search tools by name and features)
- compare.php (allows the user to perform a side-by-side detailed comparison between up-to 5 selected frameworks)
- contribute.php (provides a form and lists that contain existing framework data for the user to edit or add content to (also contains admin features))

NOTE: contribute.php is located under the privateview/ folder because this page is not publicly available. A user has to register
with his/her google account before the contribute page is accessable.
/* -----------------------------------------*/