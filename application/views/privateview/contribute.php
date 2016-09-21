<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Contribute</title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"/>

    <!-- Remote scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- Local scripts -->

    </head>

    <body>
        <!-- HEADER -->
        <div class="jumbotron">
            <div class="container">
                <h1>We are at contribution page</h1>
                <p>Compare your favorite mobile development tool with other existing tools out there. Determine which tool is best suited for your needs by filtering the list of tracked tools with your search criteria.</p>
                <p>
                    <a class="btn btn-primary btn-lg" href="<?php echo base_url();?>privateCon/" role="button">Home &raquo;</a>
                    <?php if(isset($email)) { ?><button id="socialSignOut" type="button" class="btn btn-danger btn-lg pull-right" data-toggle="tooltip" data-placement="top" title="Signed in as: <?php print $email ?>">Sign out</button><?php } ?>
                </p>
            </div>
        </div>
    
    </body>
</html>

