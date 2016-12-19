Code igniter controllers are the glue between the views and models. They handle the routing of our webapplication, 
capture the AJAX requests, and manage the user sessions.

- routing:  transition between web pages. Supply transition with meta-data used by views to determine which parts should be
            visible or not.

- AJAX request: provides the entry point for the AJAX requests and extracts the information from the request. Depending on
                the request the controller orders the model to interact with the database or not.

- user sessions:    the controller performs checks on the state of the user session. It can manage the user sessions by creating
                    new sessions and editing existing ones. Session information can be verified and used to restrict access to 
                    particular content and functionality

There are currently two controllers. A public controller and private controller. The public one is responsible for handling the
request and routes made by a public visitor. Meaning someone who is not registered and logged in. While the private controller
handles the routes and interactions of the logged in users and admins.