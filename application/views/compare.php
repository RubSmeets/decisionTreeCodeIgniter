<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Compare Frameworks Page</title>
		<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Style Sheets -->
		<link href="<?php echo base_url();?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
		<?php echo link_tag('vendor/font-awesome/css/font-awesome.min.css'); ?>
		<?php echo link_tag('css/compareFramework.css'); ?>

		<!-- Remote scripts -->
		<script src="<?php echo base_url();?>vendor/jquery/jquery.min.js"></script>
		<script src="<?php echo base_url();?>vendor/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.js"></script>

		<!-- Local scripts -->
		<script type="text/javascript" src="<?php echo base_url();?>js/compareFramework.js" ></script>

	</head>
	<body>
		<!-- Modal overlay to search frameworks (BEST DEFINED AT TOP) contains the jQuery datatable wrapper -->
		<div class="modal fade" id="addFrameworkModal" tabindex="-1" role="dialog" aria-labelledby="addFrameworkModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<table id="addFrameworksTable"></table>
				</div>
			</div>
		</div>

		<!-- HEADER -->
		<div class="jumbotron">
			<div class="container">
				<h1>Mobile Framework Comparison Tool</h1>
				<p>Perform a side-by-side detailed comparison of up to 5 of your selected tools. Compare their properties accross multiple categories including: resources, hardware features and support features.</p>
				<p>
					<a class="btn btn-primary btn-lg" href="<?php echo base_url();?>privateCon/index" role="button">Home &raquo;</a>
					<?php if(isset($email)) { ?><button id="socialSignOut" type="button" class="btn btn-danger btn-lg pull-right" data-toggle="tooltip" data-placement="top" title="Signed in as: <?php print $email ?>">Sign out</button><?php } ?>
				</p>
			</div>
		</div>
		<!-- ------------------------------------------------------ -->
		<div class="container">
			<!-- Contains ADD button to open modal and the header thumbnail images of each framework (appended inside container) -->
			<div id="compare-header" class="row compare-header">
				<div class="col-md-3 centered no-right-padding">
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-success add-framework" data-toggle="modal" data-target="#addFrameworkModal">
						<span class="glyphicon glyphicon-plus add-framework-icon"></span>
						<span>Add framework</span>
					</button>
				</div>
				<div id="frameworkheader-container" class="col-md-9 no-left-padding no-right-padding">

				</div>
			</div>
		</div>
		<div class="container">
			<!-- bootstrap alert message shown when no frameworks are being compared (defined at top of container for correct alignment) -->
			<div id="msg" class="alert alert-info" hidden>
            	<strong>Info!</strong> No framework selected for comparison. Please add a framework using the add button.
          	</div>
			<div class="row compare-body">
				<!-- TODO: before the main tool feature table there should be a short description of each tool. This could be included in the main table as well -->
				<!-- The main tool feature table. This table (created with spans) is non collapseable by the collapse button -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Tool Specifications</h4>
					</div>
					<div class="panel-body no-side-padding">
						<div class="row flex-col">
							<div class="col-md-3 no-right-padding">
								<span class="feature-heading">
									Technology <i class="glyphicon glyphicon-question-sign feature-info" data-toggle="tooltip" title="The underlying technology used by the cross-platform tool to create an application" data-placement="right"></i>
								</span>
							</div>
							<!-- Place holder for technology info -->
							<div id="toolTecCon" class="col-md-9 no-left-padding flex-col">

							</div>
						</div>
						<div class="row flex-col">
							<div class="col-md-3 no-right-padding">
								<span class="feature-heading">Announced  <i class="glyphicon glyphicon-question-sign feature-info" data-toggle="tooltip" title="The year the tool got officially announced" data-placement="right"></i></span>
							</div>
							<!-- Place holder for year announced info -->
							<div id="toolAnnCon" class="col-md-9 no-left-padding flex-col">

							</div>
						</div>
						<div class="row flex-col">
							<div class="col-md-3 no-right-padding">
								<span class="feature-heading">Version</span>
							</div>
							<!-- Place holder for tool version info -->
							<div id="toolVerCon" class="col-md-9 no-left-padding flex-col">
								
							</div>
						</div>
						<div class="row flex-col">
							<div class="col-md-3 no-right-padding">
								<span class="feature-heading">Supported Platforms</span>
							</div>
							<!-- Place holder for supported platform info -->
							<div id="toolPlaCon" class="col-md-9 no-left-padding flex-col">
								
							</div>
						</div>
						<div class="row flex-col">
							<div class="col-md-3 no-right-padding">
								<span class="feature-heading">Programming Languages</span>
							</div>
							<!-- Place holder for supported programming languages info -->
							<div id="toolLanCon" class="col-md-9 no-left-padding flex-col">
								
							</div>
						</div>
						<div class="row flex-col">
							<div class="col-md-3 no-right-padding">
								<span class="feature-heading">Output Product  <i class="glyphicon glyphicon-question-sign feature-info" data-toggle="tooltip" title="The produced application type by the cross-platform tool. E.g. web-app, native-app, hybrid-app" data-placement="right"></i></span>
							</div>
							<!-- Place holder for the type of output generated -->
							<div id="toolProCon" class="col-md-9 no-left-padding flex-col">
								
							</div>
						</div>
						<div class="row flex-col">
							<div class="col-md-3 no-right-padding">
								<span class="feature-heading">License</span>
							</div>
							<!-- Place holder for licensing info -->
							<div id="toolLicCon" class="col-md-9 no-left-padding flex-col">
								
							</div>
						</div>
						<div class="row flex-col">
							<div class="col-md-3 no-right-padding">
								<span class="feature-heading">Open-source</span>
							</div>
							<!-- Place holder for open-source info -->
							<div id="toolSrcCon" class="col-md-9 no-left-padding flex-col">
								
							</div>
						</div>
						<div class="row flex-col">
							<div class="col-md-3 no-right-padding">
								<span class="feature-heading">Cost  <i class="glyphicon glyphicon-question-sign feature-info" data-toggle="tooltip" title="The minimal required cost to create an application with the cross-platform tool" data-placement="right"></i></span>
							</div>
							<!-- Place holder for the tool pricing info -->
							<div id="toolCostCon" class="col-md-9 no-left-padding flex-col">
								
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div class="col-md-15 centered"><i class="glyphicon glyphicon-ok check"></i><span>Supported</span></div>
							<div class="col-md-15 centered"><i class="glyphicon glyphicon-remove uncheck"></i><span>Not supported</span></div>
							<div class="col-md-15 centered"><i class="glyphicon glyphicon-minus"></i><span>Not specified</span></div>
							<div class="col-md-15 centered"><i class="fa fa-plug"></i><span>Supported via plugin</span></div>
							<div class="col-md-15 centered"><i class="fa fa-users"></i><span>Supplied by third party</span></div>
						</div>
					</div>
				</div>
				<!-- The "collapse all" button is used to collapse all the panels from this point on -->
				<button id="btnCollapseAll" type="button" class="btn btn-info">
					<span class="glyphicon glyphicon-menu-hamburger pull-left"></span><span class="btn-label">Collapse All</span>
				</button>
				<!-- Table containing the framework resources -->
				<div class="panel-group">
					<div class="panel panel-default">
						<h4 class="panel-title">
							<a data-toggle="collapse" aria-expanded="true" href="#collapse1">
								<div class="panel-heading">
									<span>Resources</span>
									<span class="dropIcon pull-right glyphicon glyphicon-chevron-up"></span>
									<span class="dropIcon pull-right glyphicon glyphicon-chevron-down"></span>
								</div>
							</a>
						</h4>
						<div id="collapse1" class="panel-collapse collapse in">
							<div class="panel-body no-side-padding">
								<div class="col-md-3 no-right-padding">
									<span class="feature-heading">Website</span>
									<span class="feature-heading">Documentation</span>
									<span class="feature-heading">Tutorial</span>
									<span class="feature-heading">Video(s)</span>
									<span class="feature-heading">Book(s)</span>
									<span class="feature-heading">App Showcases</span>
									<span class="feature-heading">Market Place</span>
									<span class="feature-heading">Repository</span>
								</div>
								<!-- Placeholder for the resources that belong to a specific tool -->
								<div id="framework-resources-container" class="col-md-9 no-left-padding">
									
								</div>	
							</div>
							<!-- Footer containing a legend of icons used to describe content -->
							<div class="panel-footer">
								<div class="row">
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-ok check"></i><span>Supported</span></div>
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-remove uncheck"></i><span>Not supported</span></div>
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-minus"></i><span>Not specified</span></div>
									<div class="col-md-15 centered"><i class="fa fa-plug"></i><span>Supported via plugin</span></div>
									<div class="col-md-15 centered"><i class="fa fa-users"></i><span>Supplied by third party</span></div>
								</div>
							</div>
						</div>
					</div>
    			</div>
				<!-- Table containing the framework development specifications -->
				<div class="panel-group">
					<div class="panel panel-default">
						<h4 class="panel-title">
							<a data-toggle="collapse" aria-expanded="true" href="#collapse2">
								<div class="panel-heading">
									<span>Development Specifications</span>
									<span class="dropIcon pull-right glyphicon glyphicon-chevron-up"></span>
									<span class="dropIcon pull-right glyphicon glyphicon-chevron-down"></span>
								</div>
							</a>
						</h4>
						<div id="collapse2" class="panel-collapse collapse in">
							<div class="panel-body no-side-padding">
								<div class="col-md-3 no-right-padding">
									<span class="feature-heading">Suited for Game development</span>
									<span class="feature-heading">Cloud Development</span>
									<span class="feature-heading">Allows Prototyping</span>
									<span class="feature-heading">Multi-screen support</span>
									<span class="feature-heading">Livesync  <i class="glyphicon glyphicon-question-sign feature-info" data-toggle="tooltip" title="Livesync provides a web-development like experience during app development. It allows for instant feedback on changes to the UI and functionality without rebuilding the complete app. This features enables a rapid iteration speed during development" data-placement="right"></i></span>
									<span class="feature-heading">Publication Assist</span>
								</div>
								<!-- Placeholder for the development specification content that belongs to a specific tool -->
								<div id="framework-dev-spec-container" class="col-md-9 no-left-padding">
									
								</div>	
							</div>
							<!-- Footer containing a legend of icons used to describe content -->
							<div class="panel-footer">
								<div class="row">
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-ok check"></i><span>Supported</span></div>
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-remove uncheck"></i><span>Not supported</span></div>
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-minus"></i><span>Not specified</span></div>
									<div class="col-md-15 centered"><i class="fa fa-plug"></i><span>Supported via plugin</span></div>
									<div class="col-md-15 centered"><i class="fa fa-users"></i><span>Supplied by third party</span></div>
								</div>
							</div>
						</div>
					</div>
    			</div>
				<!-- Table containing the framework supported hardware features -->
				<div class="panel-group">
					<div class="panel panel-default">
						<h4 class="panel-title">
							<a data-toggle="collapse" aria-expanded="true" href="#collapse3">
								<div class="panel-heading">
									<span>Supported Hardware Features</span>
									<span class="dropIcon pull-right glyphicon glyphicon-chevron-up"></span>
									<span class="dropIcon pull-right glyphicon glyphicon-chevron-down"></span>
								</div>
							</a>
						</h4>
						<div id="collapse3" class="panel-collapse collapse in">
							<div class="panel-body no-side-padding">
								<div class="col-md-3 no-right-padding">
									<span class="feature-heading">Accelerometer</span>
									<span class="feature-heading">Device</span>
									<span class="feature-heading">File</span>
									<span class="feature-heading">Bluetooth</span>
									<span class="feature-heading">Camera</span>
									<span class="feature-heading">Capture</span>
									<span class="feature-heading">Compass</span>
									<span class="feature-heading">Connection</span>
									<span class="feature-heading">Contacts</span>
									<span class="feature-heading">Geolocation</span>
									<span class="feature-heading">Multi-touch Gestures</span>
									<span class="feature-heading">Native Events</span>
									<span class="feature-heading">NFC</span>
									<span class="feature-heading">Storage</span>
									<span class="feature-heading">Telephone Messages</span>
									<span class="feature-heading">Vibration</span>
								</div>
								<!-- Placeholder for the hardware features content that belongs to a specific tool -->
								<div id="framework-hardware-feature-container" class="col-md-9 no-left-padding">
									
								</div>	
							</div>
							<!-- Footer containing a legend of icons used to describe content -->
							<div class="panel-footer">
								<div class="row">
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-ok check"></i><span>Supported</span></div>
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-remove uncheck"></i><span>Not supported</span></div>
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-minus"></i><span>Not specified</span></div>
									<div class="col-md-15 centered"><i class="fa fa-plug"></i><span>Supported via plugin</span></div>
									<div class="col-md-15 centered"><i class="fa fa-users"></i><span>Supplied by third party</span></div>
								</div>
							</div>
						</div>
					</div>
    			</div>
				<!-- Table containing the framework developer support features -->
				<div class="panel-group">
					<div class="panel panel-default">
						<h4 class="panel-title">
							<a data-toggle="collapse" aria-expanded="true" href="#collapse4">
								<div class="panel-heading">
									<span>Support</span>
									<span class="dropIcon pull-right glyphicon glyphicon-chevron-up"></span>
									<span class="dropIcon pull-right glyphicon glyphicon-chevron-down"></span>
								</div>
							</a>
						</h4>
						<div id="collapse4" class="panel-collapse collapse in">
							<div class="panel-body no-side-padding">
								<div class="col-md-3 no-right-padding">
									<span class="feature-heading">On-site support</span>
									<span class="feature-heading">Hired help</span>
									<span class="feature-heading">Phone support</span>
									<span class="feature-heading">Time-delayed support</span>
									<span class="feature-heading">Community support</span>
								</div>
								<!-- Placeholder for the developer support features content that belongs to a specific tool -->
								<div id="framework-support-feature-container" class="col-md-9 no-left-padding">
									
								</div>	
							</div>
							<!-- Footer containing a legend of icons used to describe content -->
							<div class="panel-footer">
								<div class="row">
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-ok check"></i><span>Supported</span></div>
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-remove uncheck"></i><span>Not supported</span></div>
									<div class="col-md-15 centered"><i class="glyphicon glyphicon-minus"></i><span>Not specified</span></div>
									<div class="col-md-15 centered"><i class="fa fa-plug"></i><span>Supported via plugin</span></div>
									<div class="col-md-15 centered"><i class="fa fa-users"></i><span>Supplied by third party</span></div>
								</div>
							</div>
						</div>
					</div>
    			</div>
				<!-- TODO: add more panels here that contain additional tooling information -->
			</div>
		</div>
	</body>
</html>
