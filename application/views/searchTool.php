<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Cross-Platform Search Tool</title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php echo link_tag('vendor/bootstrap/css/bootstrap.min.css'); ?>
	<?php echo link_tag('fonts/font-awesome/css/font-awesome.min.css'); ?>
	<?php echo link_tag('css/searchTool.css'); ?>
	<?php if(!isset($email)) { ?><?php echo link_tag('css/bootstrap-social.css'); ?><?php } ?>
	<?php echo link_tag('vendor/overhang/css/overhang.min.css'); ?>

    <!-- Remote scripts -->
    <script src="<?php echo base_url();?>vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url();?>vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="https://apis.google.com/js/api:client.js"></script>

    <!-- Local scripts -->
	<script type="text/javascript" src="<?php echo base_url();?>js/searchTool/searchTool.js" ></script>
	<?php if(isset($email)) {?>
		<script type="text/javascript" src="<?php echo base_url();?>js/searchTool/socialLogout.js" ></script>
	<?php } else {?>
		<script type="text/javascript" src="<?php echo base_url();?>js/searchTool/socialLogin.js" ></script>
	<?php }?>
	<script type="text/javascript" src="<?php echo base_url();?>vendor/overhang/js/overhang.min.js"></script>	

  </head>

  <body>
  	<?php if(!isset($email)) { ?>
  	<!-- Modal overlay to login for contribution (BEST DEFINED AT TOP)-->
	<div class="modal fade" id="contributeLoginModal" tabindex="-1" role="dialog" aria-labelledby="contributeLoginLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Contribute to the project</h4>
				</div>
				<div class="modal-body">
					<table class="table borderless">
						<tbody>
							<tr>
								<td><i class="fa fa-plus-circle fa-4x descr-icon" aria-hidden="true"></i></td>
								<td class="descr-label">You as a user can participate to the project by supplying new content or editing existing content on our contribution page</td>
							</tr>
							<tr>
								<td><i class="fa fa-sign-in fa-4x descr-icon" aria-hidden="true"></i></td>
								<td class="descr-label"><span>Register and login using your favorite social account</span></td>
							</tr>
							<tr>
								<td><i class="fa fa-check-circle-o fa-4x descr-icon" aria-hidden="true"></i></td>
								<td class="descr-label"><span>Changes and addition are verified by our admins before being adopted into the system</span></td>
							</tr>
						</tbody>
					</table>
					<div class="row">
						<div class="col-xs-12 centered">
							<button id="googleLoginBtn" type="button" class="btn btn-social btn-google">
								<span class="fa fa-google"></span> Sign in with Google
							</button>
							<!-- <button id="googleLoginBtn" type="button" class="btn btn-danger google-login-btn" data-onsuccess="onSignIn">
								<i class="fa fa-google-plus google-login-icon" aria-hidden="true"></i>
								<span class="google-login-label">Sign in with Google</span>
							</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php }?>
    <!-- HEADER -->
    <div class="jumbotron">
      <div class="container">
        <h1>Mobile Framework Comparison Tool</h1>
        <p>Compare your favorite mobile development tool with other existing tools out there. Determine which tool is best suited for your needs by filtering the list of tracked tools with your search criteria.</p>
        <p>
			<a class="btn btn-primary btn-lg" href="<?php echo base_url();?>publicCon/learnmore" role="button">Learn more &raquo;</a>
			<?php if(!isset($email)) {?>
			<a id="goToCompareBtn" class="btn btn-primary btn-lg" href="<?php echo base_url();?>publicCon/compare" role="button">Compare &raquo;</a>  
			<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#contributeLoginModal">Contribute</button>
			<?php } else {?>
			<a id="goToCompareBtn" class="btn btn-primary btn-lg" href="<?php echo base_url();?>privateCon/compare" role="button">Compare &raquo;</a>  
			<?php if(isset($email)) { ?><a id="goToContibution" href="<?php echo base_url();?>privateCon/contribute" class="btn btn-info btn-lg" role="button">Start Contributing</a><?php } ?>
			<?php if(isset($email)) { ?><button id="socialSignOut" type="button" class="btn btn-danger btn-lg pull-right" data-toggle="tooltip" data-placement="top" title="Signed in as: <?php print $email ?>">Sign out</button><?php } ?>
			<?php }?>
		</p>
      </div>
    </div>
    <!--------------------------------------------------------->
    <!-- BODY -->
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <!-- SEARCH BOX -->
          <div class="form-group has-feedback">
            <input id="filter" type="search" placeholder="Filter By Tool Name" class="form-control">
            <i class="glyphicon glyphicon-search form-control-feedback"></i>
          </div>
        </div>
      </div>
      <div class="row">
<div class="col-sm-3 filters"><div class="track-tool">
    	<h4>Number of tracked tools</h4>
    	<div class="track-tool-nr">
			<span id="nr3" class="label label-nr">0</span>
			<span id="nr2" class="label label-nr">0</span>
			<span id="nr1" class="label label-nr">0</span>
			<span id="nr0" class="label label-nr">0</span>
		</div>
	</div><button type="button" class="btn btn-default btn-clear" disabled>
    	<span class="glyphicon glyphicon-trash pull-right"></span>Clear All <span class="badge result-amount"></span>
  	</button>
    <div class="panel-group filter-box">
    	<div class="panel panel-default">
    		<h4 class="panel-title">
    			<a data-toggle="collapse" aria-expanded="false" href="#collapse0">
    				<div class="panel-heading">
    					Tool status
    				</div>
    			</a>
    		</h4>
    		<div id="collapse0" class="panel-collapse collapse in">
    			<div class="panel-body">
    				<fieldset class="fieldset accordion-content" role="tab-panel" style="display: block;" aria-hidden="false">
    					<p>Is the tool still available?</p>
    					<input id="active" type="checkbox" value="active" class="custom-checkbox"/>
        					<label for="active" class="checkbox-label">Active</label>
        					<input id="discontinued" type="checkbox" value="discontinued" class="custom-checkbox"/>
        					<label for="discontinued" class="checkbox-label">Discontinued</label>
        				</fieldset>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="panel-group filter-box">
    	<div class="panel panel-default">
    		<h4 class="panel-title">
    			<a data-toggle="collapse" aria-expanded="false" href="#collapse1">
    				<div class="panel-heading">
    					Technology
    				</div>
    			</a>
    		</h4>
    		<div id="collapse1" class="panel-collapse collapse in">
    			<div class="panel-body">
    				<fieldset class="fieldset accordion-content" role="tab-panel" style="display: block;" aria-hidden="false">
    					<p>What cross-platform technology must be used?</p>
    					<input id="nativejavascript" type="checkbox" value="nativejavascript" class="custom-checkbox"/>
        					<label for="nativejavascript" class="checkbox-label">Native JavaScript</label>
        					<input id="webtonative" type="checkbox" value="webtonative" class="custom-checkbox"/>
        					<label for="webtonative" class="checkbox-label">Web-to-native wrapper</label>
        					<input id="javascript_tool" type="checkbox" value="javascript_tool" class="custom-checkbox"/>
        					<label for="javascript_tool" class="checkbox-label">JS framework/toolkit</label>
        					<input id="sourcecode" type="checkbox" value="sourcecode" class="custom-checkbox"/>
        					<label for="sourcecode" class="checkbox-label">Code translator</label>
        					<input id="runtime" type="checkbox" value="runtime" class="custom-checkbox"/>
        					<label for="runtime" class="checkbox-label">Runtime</label>
        					<input id="appfactory" type="checkbox" value="appfactory" class="custom-checkbox"/>
        					<label for="appfactory" class="checkbox-label">App Factory</label>
        				</fieldset>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="panel-group filter-box">
    	<div class="panel panel-default">
    		<h4 class="panel-title">
    			<a data-toggle="collapse" aria-expanded="false" href="#collapse2">
    				<div class="panel-heading">
    					Platform
    				</div>
    			</a>
    		</h4>
    		<div id="collapse2" class="panel-collapse collapse in">
    			<div class="panel-body">
    				<fieldset class="fieldset accordion-content" role="tab-panel" style="display: block;" aria-hidden="false">
    					<p>Which platforms must be supported by the framework?</p>
    					<input id="android" type="checkbox" value="android" class="custom-checkbox"/>
        					<label for="android" class="checkbox-label">Android</label>
        					<input id="ios" type="checkbox" value="ios" class="custom-checkbox"/>
        					<label for="ios" class="checkbox-label">iOS</label>
        					<input id="windowsphone" type="checkbox" value="windowsphone" class="custom-checkbox"/>
        					<label for="windowsphone" class="checkbox-label">WindowsPhone</label>
        					<input id="blackberry" type="checkbox" value="blackberry" class="custom-checkbox"/>
        					<label for="blackberry" class="checkbox-label">Blackberry</label>
        					<input id="tizen" type="checkbox" value="tizen" class="custom-checkbox"/>
        					<label for="tizen" class="checkbox-label">Tizen</label>
        					<input id="firefoxos" type="checkbox" value="firefoxos" class="custom-checkbox"/>
        					<label for="firefoxos" class="checkbox-label">Firefox OS</label>
        					<input id="watchos" type="checkbox" value="watchos" class="custom-checkbox"/>
        					<label for="watchos" class="checkbox-label">Watch OS</label>
        				</fieldset>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="panel-group filter-box">
    	<div class="panel panel-default">
    		<h4 class="panel-title">
    			<a data-toggle="collapse" aria-expanded="false" href="#collapse3">
    				<div class="panel-heading">
    					Target
    				</div>
    			</a>
    		</h4>
    		<div id="collapse3" class="panel-collapse collapse">
    			<div class="panel-body">
    				<fieldset class="fieldset accordion-content" role="tab-panel" style="display: block;" aria-hidden="false">
    					<p>What type of application should the framework output?</p>
    					<input id="hybridapp" type="checkbox" value="hybridapp" class="custom-checkbox"/>
        					<label for="hybridapp" class="checkbox-label">Hybrid App</label>
        					<input id="nativeapp" type="checkbox" value="nativeapp" class="custom-checkbox"/>
        					<label for="nativeapp" class="checkbox-label">Native App</label>
        					<input id="mobilewebsite" type="checkbox" value="mobilewebsite" class="custom-checkbox"/>
        					<label for="mobilewebsite" class="checkbox-label">Mobile website</label>
        					<input id="webapp" type="checkbox" value="webapp" class="custom-checkbox"/>
        					<label for="webapp" class="checkbox-label">Web App</label>
        				</fieldset>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="panel-group filter-box">
    	<div class="panel panel-default">
    		<h4 class="panel-title">
    			<a data-toggle="collapse" aria-expanded="false" href="#collapse4">
    				<div class="panel-heading">
    					Development Language
    				</div>
    			</a>
    		</h4>
    		<div id="collapse4" class="panel-collapse collapse">
    			<div class="panel-body">
    				<fieldset class="fieldset accordion-content" role="tab-panel" style="display: block;" aria-hidden="false">
    					<p>Which development language would you like to use?</p>
    					<input id="php" type="checkbox" value="php" class="custom-checkbox"/>
        					<label for="php" class="checkbox-label">PHP</label>
        					<input id="basic" type="checkbox" value="basic" class="custom-checkbox"/>
        					<label for="basic" class="checkbox-label">Basic</label>
        					<input id="java" type="checkbox" value="java" class="custom-checkbox"/>
        					<label for="java" class="checkbox-label">Java</label>
        					<input id="ruby" type="checkbox" value="ruby" class="custom-checkbox"/>
        					<label for="ruby" class="checkbox-label">Ruby</label>
        					<input id="actionscript" type="checkbox" value="actionscript" class="custom-checkbox"/>
        					<label for="actionscript" class="checkbox-label">ActionScript</label>
        					<input id="csharp" type="checkbox" value="csharp" class="custom-checkbox"/>
        					<label for="csharp" class="checkbox-label">C#</label>
        					<input id="lua" type="checkbox" value="lua" class="custom-checkbox"/>
        					<label for="lua" class="checkbox-label">LUA</label>
        					<input id="xml" type="checkbox" value="xml" class="custom-checkbox"/>
        					<label for="xml" class="checkbox-label">XML</label>
        					<input id="html" type="checkbox" value="html" class="custom-checkbox"/>
        					<label for="html" class="checkbox-label">HTML</label>
        					<input id="css" type="checkbox" value="css" class="custom-checkbox"/>
        					<label for="css" class="checkbox-label">CSS</label>
        					<input id="js" type="checkbox" value="js" class="custom-checkbox"/>
        					<label for="js" class="checkbox-label">JavaScript</label>
        					<input id="cplusplus" type="checkbox" value="cplusplus" class="custom-checkbox"/>
        					<label for="cplusplus" class="checkbox-label">C++</label>
        					<input id="visualeditor" type="checkbox" value="visualeditor" class="custom-checkbox"/>
        					<label for="visualeditor" class="checkbox-label">Visual Editor</label>
        				</fieldset>
    			</div>
    		</div>
    	</div>
    </div>
	<div class="panel-group filter-box">
    	<div class="panel panel-default">
    		<h4 class="panel-title">
    			<a data-toggle="collapse" aria-expanded="false" href="#collapse5">
    				<div class="panel-heading">
    					Hardware Features
    				</div>
    			</a>
    		</h4>
    		<div id="collapse5" class="panel-collapse collapse">
    			<div class="panel-body">
    				<fieldset class="fieldset accordion-content" role="tab-panel" style="display: block;" aria-hidden="false">
    					<p>Which hardware features must be supported?</p>
    					<input id="accelerometer" type="checkbox" value="accelerometer" class="custom-checkbox"/>
						<label for="accelerometer" class="checkbox-label">Accelerometer</label>
						<input id="device" type="checkbox" value="device" class="custom-checkbox"/>
						<label for="device" class="checkbox-label">Device</label>
						<input id="file" type="checkbox" value="file" class="custom-checkbox"/>
						<label for="file" class="checkbox-label">File</label>
						<input id="bluetooth" type="checkbox" value="bluetooth" class="custom-checkbox"/>
						<label for="bluetooth" class="checkbox-label">Bluetooth</label>
						<input id="camera" type="checkbox" value="camera" class="custom-checkbox"/>
						<label for="camera" class="checkbox-label">Camera</label>
						<input id="capture" type="checkbox" value="capture" class="custom-checkbox"/>
						<label for="capture" class="checkbox-label">Capture</label>
						<input id="geolocation" type="checkbox" value="geolocation" class="custom-checkbox"/>
						<label for="geolocation" class="checkbox-label">Geolocation</label>
						<input id="gestures_multitouch" type="checkbox" value="gestures_multitouch" class="custom-checkbox"/>
						<label for="gestures_multitouch" class="checkbox-label">Multitouch gestures</label>
						<input id="compass" type="checkbox" value="compass" class="custom-checkbox"/>
						<label for="compass" class="checkbox-label">Compass</label>
						<input id="connection" type="checkbox" value="connection" class="custom-checkbox"/>
						<label for="connection" class="checkbox-label">Connection</label>
						<input id="contacts" type="checkbox" value="contacts" class="custom-checkbox"/>
						<label for="contacts" class="checkbox-label">Contacts</label>
						<input id="messages_telephone" type="checkbox" value="messages_telephone" class="custom-checkbox"/>
						<label for="messages_telephone" class="checkbox-label">Telephone messages</label>
						<input id="nativeevents" type="checkbox" value="nativeevents" class="custom-checkbox"/>
						<label for="nativeevents" class="checkbox-label">Native events</label>
						<input id="nfc" type="checkbox" value="nfc" class="custom-checkbox"/>
						<label for="nfc" class="checkbox-label">NFC</label>
						<input id="notification" type="checkbox" value="notification" class="custom-checkbox"/>
						<label for="notification" class="checkbox-label">Notification</label>
						<input id="storage" type="checkbox" value="storage" class="custom-checkbox"/>
						<label for="storage" class="checkbox-label">Storage</label>
						<input id="vibration" type="checkbox" value="vibration" class="custom-checkbox"/>
						<label for="vibration" class="checkbox-label">Vibration</label>
					</fieldset>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="panel-group filter-box">
    	<div class="panel panel-default">
    		<h4 class="panel-title">
    			<a data-toggle="collapse" aria-expanded="false" href="#collapse6">
    				<div class="panel-heading">
    					License
    				</div>
    			</a>
    		</h4>
    		<div id="collapse6" class="panel-collapse collapse">
    			<div class="panel-body">
    				<fieldset class="fieldset accordion-content" role="tab-panel" style="display: block;" aria-hidden="false">
    					<p>What license is required?</p>
    					<input id="free" type="checkbox" value="free" class="custom-checkbox"/>
        					<label for="free" class="checkbox-label">Free</label>
        					<input id="opensource" type="checkbox" value="opensource" class="custom-checkbox"/>
        					<label for="opensource" class="checkbox-label">Open Source</label>
        					<input id="commercial" type="checkbox" value="commercial" class="custom-checkbox"/>
        					<label for="commercial" class="checkbox-label">Commercial lic.</label>
        					<input id="enterprise" type="checkbox" value="enterprise" class="custom-checkbox"/>
        					<label for="enterprise" class="checkbox-label">Enterprise Lic.</label>
        				</fieldset>
    			</div>
    		</div>
    	</div>
    </div>
              
        </div>
		<div class="col-sm-9">
              
          <div id="msg" class="alert alert-info" hidden>
            <strong>Info!</strong> No framework fits the search criteria. Please refine your search input.
          </div>
        </div>
      </div>
    </div>

  </body>
  
</html>