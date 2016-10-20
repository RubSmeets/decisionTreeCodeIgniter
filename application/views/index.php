<!DOCTYPE html>
<html lang="en">
  <head>
    <title>DecisionTree</title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"/>
    <?php echo link_tag('fonts/font-awesome/css/font-awesome.min.css'); ?>
	<?php echo link_tag('css/decisionTree.css'); ?>
	<?php if(!isset($email)) { ?><?php echo link_tag('css/bootstrap-social.css'); ?><?php } ?>
	<?php echo link_tag('css/extern/overhang.min.css'); ?>

    <!-- Remote scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="https://apis.google.com/js/api:client.js"></script>

    <!-- Local scripts -->
	<script type="text/javascript" src="<?php echo base_url();?>js/index/decisionTree.js" ></script>
	<?php if(isset($email)) {?>
		<script type="text/javascript" src="<?php echo base_url();?>js/index/socialLogout.js" ></script>
	<?php } else {?>
		<script type="text/javascript" src="<?php echo base_url();?>js/index/socialLogin.js" ></script>
	<?php }?>
	<script type="text/javascript" src="<?php echo base_url();?>js/extern/overhang.min.js"></script>	

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
			<a class="btn btn-primary btn-lg" href="html/about.html" role="button">Learn more &raquo;</a>
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
			<span class="label label-default nr">0</span>
			<span class="label label-default nr">0</span>
			<span class="label label-default nr">5</span>
			<span class="label label-default nr">5</span>
		</div>
	</div><button type="button" class="btn btn-default btn-clear" disabled>
    	Clear All <span class="badge result-amount"></span><span class="glyphicon glyphicon-trash pull-right"/>
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
    					License
    				</div>
    			</a>
    		</h4>
    		<div id="collapse5" class="panel-collapse collapse">
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
	<div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.smartface.io/" target="_blank"><img src="<?php echo base_url('img/logos/smartface.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Smartface</h4>
    			<span class="info-label nativejavascript ">Native JavaScript </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/smartface_io" target="_blank"><i id="twitter-smartface_io"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/smartface.io" target="_blank"><i id="stackoverflow-smartface"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-smartface" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-smartface" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse6">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse6" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<img src="<?php echo base_url('img/logos/notfound.png'); ?>" alt="">		<div class="caption">
    			<h4 class="thumb-caption">AppConKit</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><i class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">n/a</span></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/appconkit" target="_blank"><i id="stackoverflow-appconkit"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-appconkit" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-appconkit" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse7">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse7" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone soon">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp soon">C#</span>
								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js via">JavaScript</span>
								<span class="feature java ">Java</span>
								<span class="feature ruby soon">Ruby</span>
								<span class="feature php ">PHP</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://flex.apache.org/" target="_blank"><img src="<?php echo base_url('img/logos/apacheflex.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Apache Flex</h4>
    			<span class="info-label sourcecode ">Code translator </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/apacheflex" target="_blank"><i id="twitter-apacheflex"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/flex" target="_blank"><i id="stackoverflow-flex"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-apacheflex" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-apacheflex" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse8">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse8" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature actionscript ">ActionScript</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.applusform.com/en/" target="_blank"><img src="<?php echo base_url('img/logos/agate.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Agate</h4>
    			<span class="info-label sourcecode ">Code translator </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><i class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">n/a</span></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-agate" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-agate" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse9">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse9" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature xml ">XML</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.appcelerator.com/" target="_blank"><img src="<?php echo base_url('img/logos/appceleratortitanium.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Appcelerator Titanium</h4>
    			<span class="info-label nativejavascript ">Native JavaScript </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/appcelerator" target="_blank"><i id="twitter-appcelerator"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/appcelerator" target="_blank"><i id="stackoverflow-appcelerator"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-appceleratortitanium" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-appceleratortitanium" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse10">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse10" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								<span class="feature php ">PHP</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.appgyver.io/" target="_blank"><img src="<?php echo base_url('img/logos/appgyversteroids.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">AppGyver Steroids</h4>
    			<span class="info-label webtonative ">Web-to-native wrapper </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/appgyver" target="_blank"><i id="twitter-appgyver"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/appgyver" target="_blank"><i id="stackoverflow-appgyver"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-appgyversteroids" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-appgyversteroids" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse11">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse11" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.applicationcraft.com/" target="_blank"><img src="<?php echo base_url('img/logos/applicationcraft.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Application Craft</h4>
    			<span class="info-label appfactory ">App Factory </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/appcrafty" target="_blank"><i id="twitter-appcrafty"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/applicationcraft" target="_blank"><i id="stackoverflow-applicationcraft"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-applicationcraft" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-applicationcraft" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse12">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse12" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.chocolatechip-ui.com/" target="_blank"><img src="<?php echo base_url('img/logos/chocolatechipui.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">ChocolateChip-UI</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/chocolatechipui" target="_blank"><i id="twitter-chocolatechipui"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/chocolatechip-ui" target="_blank"><i id="stackoverflow-chocolatechip-ui"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-chocolatechipui" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-chocolatechipui" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse13">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse13" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry via">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://coronalabs.com/" target="_blank"><img src="<?php echo base_url('img/logos/corona.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Corona</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/CoronaLabs" target="_blank"><i id="twitter-coronalabs"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/corona" target="_blank"><i id="stackoverflow-corona"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-corona" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-corona" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse14">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse14" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp ">C#</span>
								<span class="feature visualeditor soon">Visual Editor</span>
								<span class="feature lua ">LUA</span>
								<span class="feature java ">Java</span>
								<span class="feature cplusplus ">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource partially">Open Source</span>
								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://dhtmlx.com/" target="_blank"><img src="<?php echo base_url('img/logos/dhtmlxtouch.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">DHTMLX Touch</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/dhtmlx" target="_blank"><i id="twitter-dhtmlx"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/dhtmlx" target="_blank"><i id="stackoverflow-dhtmlx"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-dhtmlxtouch" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-dhtmlxtouch" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse15">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse15" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://dojotoolkit.org/" target="_blank"><img src="<?php echo base_url('img/logos/dojomobile.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Dojo Mobile</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/dojo" target="_blank"><i id="twitter-dojo"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/dojo" target="_blank"><i id="stackoverflow-dojo"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-dojomobile" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-dojomobile" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse16">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse16" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor via">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.emobc.com/" target="_blank"><img src="<?php echo base_url('img/logos/emobc.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">eMobc</h4>
    			<span class="info-label sourcecode ">Code translator </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/emobcapp" target="_blank"><i id="twitter-emobcapp"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-emobc" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-emobc" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse17">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse17" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry via">Blackberry</span>
							<span class="feature windowsphone via">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature xml ">XML</span>
								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature java ">Java</span>
								<span class="feature cplusplus via">C++</span>
								<span class="feature ruby ">Ruby</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								<span class="feature php ">PHP</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://framework7.io/" target="_blank"><img src="<?php echo base_url('img/logos/framework7.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Framework7</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/idangerous" target="_blank"><i id="twitter-idangerous"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/framework7" target="_blank"><i id="stackoverflow-framework7"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-framework7" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-framework7" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse18">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse18" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry partially">Blackberry</span>
							<span class="feature android partially">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp via">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://giderosmobile.com/" target="_blank"><img src="<?php echo base_url('img/logos/gideros.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Gideros</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/GiderosMobile" target="_blank"><i id="twitter-giderosmobile"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/gideros" target="_blank"><i id="stackoverflow-gideros"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-gideros" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-gideros" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse19">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse19" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp via">C#</span>
								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature lua ">LUA</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free partially">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://handhelddesigner.com/" target="_blank"><img src="<?php echo base_url('img/logos/handhelddesigner.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Handheld Designer</h4>
    			<span class="info-label appfactory ">App Factory </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/handhelddesignr" target="_blank"><i id="twitter-handhelddesignr"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-handhelddesigner" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-handhelddesigner" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse20">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse20" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone partially">WindowsPhone</span>
							<span class="feature android partially">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature commercial"">Commercial lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://ionicframework.com/" target="_blank"><img src="<?php echo base_url('img/logos/ionic.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">ionic</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/Ionicframework" target="_blank"><i id="twitter-ionicframework"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/ionic-framework" target="_blank"><i id="stackoverflow-ionic-framework"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-ionic" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-ionic" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse21">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse21" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.iui-js.org/" target="_blank"><img src="<?php echo base_url('img/logos/iui.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">iUI</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><i class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">n/a</span></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/iui" target="_blank"><i id="stackoverflow-iui"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-iui" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-iui" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse22">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse22" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://jqtjs.com/" target="_blank"><img src="<?php echo base_url('img/logos/jqtouch.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">JQ Touch</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/jQTouch" target="_blank"><i id="twitter-jqtouch"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/jqtouch" target="_blank"><i id="stackoverflow-jqtouch"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-jqtouch" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-jqtouch" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse23">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse23" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp via">C#</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://jquerymobile.com/" target="_blank"><img src="<?php echo base_url('img/logos/jquerymobile.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">jQuery Mobile</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/jquerymobile" target="_blank"><i id="twitter-jquerymobile"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/jquery-mobile" target="_blank"><i id="stackoverflow-jquery-mobile"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-jquerymobile" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-jquerymobile" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse24">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse24" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature tizen ">Tizen</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.telerik.com/kendo-ui" target="_blank"><img src="<?php echo base_url('img/logos/kendoui.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Kendo UI</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/KendoUI" target="_blank"><i id="twitter-kendoui"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/kendo-ui" target="_blank"><i id="stackoverflow-kendo-ui"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-kendoui" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-kendoui" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse25">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse25" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp ">C#</span>
								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature java ">Java</span>
								<span class="feature ruby partially">Ruby</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								<span class="feature php ">PHP</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource partially">Open Source</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://kivy.org/#home" target="_blank"><img src="<?php echo base_url('img/logos/kivy.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Kivy</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/kivyframework" target="_blank"><i id="twitter-kivyframework"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/kivy" target="_blank"><i id="stackoverflow-kivy"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-kivy" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-kivy" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse26">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse26" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.madewithmarmalade.com/" target="_blank"><img src="<?php echo base_url('img/logos/marmalade.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Marmalade</h4>
    			<span class="info-label sourcecode runtime ">Code translator + Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/marmaladeapps" target="_blank"><i id="twitter-marmaladeapps"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/marmalade" target="_blank"><i id="stackoverflow-marmalade"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-marmalade" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-marmalade" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse27">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse27" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature tizen ">Tizen</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature lua ">LUA</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature cplusplus ">C++</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource partially">Open Source</span>
								<span class="feature free partially">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://getmoai.com/" target="_blank"><img src="<?php echo base_url('img/logos/moai.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Moai</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/getmoai" target="_blank"><i id="twitter-getmoai"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/moai" target="_blank"><i id="stackoverflow-moai"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-moai" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-moai" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse28">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse28" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature lua ">LUA</span>
								<span class="feature cplusplus ">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://adaptivejs.mobify.com/" target="_blank"><img src="<?php echo base_url('img/logos/mobifyjs.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">MobifyJS</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/mobify" target="_blank"><i id="twitter-mobify"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/mobify-js" target="_blank"><i id="stackoverflow-mobify-js"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-mobifyjs" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-mobifyjs" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse29">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse29" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://monocross.net/" target="_blank"><img src="<?php echo base_url('img/logos/monocross.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Monocross</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/Monocross_net" target="_blank"><i id="twitter-monocross_net"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/monocross" target="_blank"><i id="stackoverflow-monocross"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-monocross" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-monocross" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse30">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse30" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp ">C#</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<img src="<?php echo base_url('img/logos/notfound.png'); ?>" alt="">		<div class="caption">
    			<h4 class="thumb-caption">Mono for Android</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/MonoForAndroid" target="_blank"><i id="twitter-monoforandroid"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-monoforandroid" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-monoforandroid" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse31">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse31" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone partially">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios partially">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp ">C#</span>
								<span class="feature js partially">JavaScript</span>
								<span class="feature java ">Java</span>
								<span class="feature cplusplus partially">C++</span>
								<span class="feature html partially">HTML</span>
								<span class="feature css partially">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<img src="<?php echo base_url('img/logos/monotouch.png'); ?>" alt="">		<div class="caption">
    			<h4 class="thumb-caption">MonoTouch</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><i class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">n/a</span></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-monotouch" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-monotouch" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse32">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse32" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone partially">WindowsPhone</span>
							<span class="feature android partially">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp ">C#</span>
								<span class="feature js partially">JavaScript</span>
								<span class="feature cplusplus partially">C++</span>
								<span class="feature html partially">HTML</span>
								<span class="feature css partially">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.the-m-project.org/" target="_blank"><img src="<?php echo base_url('img/logos/themproject.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">The-M-Project</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/_themproject" target="_blank"><i id="twitter-_themproject"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-themproject" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-themproject" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse33">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse33" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone partially">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://neomades.com/en/" target="_blank"><img src="<?php echo base_url('img/logos/neomad.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">NeoMAD</h4>
    			<span class="info-label sourcecode ">Code translator </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/Neomades" target="_blank"><i id="twitter-neomades"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/neomad" target="_blank"><i id="stackoverflow-neomad"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-neomad" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-neomad" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse34">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse34" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature java ">Java</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<img src="<?php echo base_url('img/logos/nimblekit.png'); ?>" alt="">		<div class="caption">
    			<h4 class="thumb-caption">NimbleKit</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/nimblekit" target="_blank"><i id="twitter-nimblekit"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-nimblekit" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-nimblekit" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse35">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse35" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android soon">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature java partially">Java</span>
								<span class="feature cplusplus ">C++</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.nsbasic.com/" target="_blank"><img src="<?php echo base_url('img/logos/nsbappstudio.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">NSB/AppStudio</h4>
    			<span class="info-label appfactory ">App Factory </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/nsbasic" target="_blank"><i id="twitter-nsbasic"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-nsbappstudio" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-nsbappstudio" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse36">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse36" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp via">Native App</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature basic ">Basic</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								<span class="feature php ">PHP</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://onsen.io/" target="_blank"><img src="<?php echo base_url('img/logos/onsenui.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Onsen UI</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/Onsen_UI" target="_blank"><i id="twitter-onsen_ui"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/onsen-ui" target="_blank"><i id="stackoverflow-onsen-ui"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-onsenui" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-onsenui" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse37">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse37" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor via">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://phonegap.com/" target="_blank"><img src="<?php echo base_url('img/logos/phonegap.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">PhoneGap</h4>
    			<span class="info-label webtonative ">Web-to-native wrapper </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/phonegap" target="_blank"><i id="twitter-phonegap"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/cordova" target="_blank"><i id="stackoverflow-cordova"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-phonegap" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-phonegap" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse38">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse38" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature firefoxos ">Firefox OS</span>
							<span class="feature blackberry ">Blackberry</span>
							<span class="feature watchos via">Watch OS</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.qooxdoo.org/" target="_blank"><img src="<?php echo base_url('img/logos/qooxdoo.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">qooxdoo</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/qooxdoo" target="_blank"><i id="twitter-qooxdoo"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/qooxdoo" target="_blank"><i id="stackoverflow-qooxdoo"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-qooxdoo" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-qooxdoo" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse39">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse39" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.rarewire.com/rw/index.php" target="_blank"><img src="<?php echo base_url('img/logos/rarewire.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">RareWire</h4>
    			<span class="info-label appfactory ">App Factory </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/RareWire" target="_blank"><i id="twitter-rarewire"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-rarewire" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-rarewire" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse40">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse40" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android partially">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free partially">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.zebra.com/us/en/products/software/mobile-computers/rhomobile-suite.html" target="_blank"><img src="<?php echo base_url('img/logos/rhodes.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Rhodes</h4>
    			<span class="info-label webtonative ">Web-to-native wrapper </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/RhoMobile" target="_blank"><i id="twitter-rhomobile"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/rhomobile/rhodes" target="_blank"><i id="github-rhomobile"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/rhodes" target="_blank"><i id="stackoverflow-rhodes"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-rhodes" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-rhodes" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse41">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse41" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature ruby ">Ruby</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.sencha.com/" target="_blank"><img src="<?php echo base_url('img/logos/senchatouch.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Sencha Touch</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/Sencha" target="_blank"><i id="twitter-sencha"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/sencha-touch" target="_blank"><i id="stackoverflow-sencha-touch"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-senchatouch" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-senchatouch" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse42">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse42" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://www.shiva-engine.com/" target="_blank"><img src="<?php echo base_url('img/logos/shiva3d.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">ShiVa 3D</h4>
    			<span class="info-label sourcecode runtime ">Code translator + Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/ShiVaEngine" target="_blank"><i id="twitter-shivaengine"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/shiva3d" target="_blank"><i id="stackoverflow-shiva3d"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-shiva3d" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-shiva3d" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse43">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse43" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp partially">Web App</span>
								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp partially">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature lua ">LUA</span>
								<span class="feature js partially">JavaScript</span>
								<span class="feature java partially">Java</span>
								<span class="feature cplusplus ">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free partially">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://trigger.io/" target="_blank"><img src="<?php echo base_url('img/logos/triggerio.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Trigger.io</h4>
    			<span class="info-label webtonative ">Web-to-native wrapper </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/triggercorp" target="_blank"><i id="twitter-triggercorp"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/trigger.io" target="_blank"><i id="stackoverflow-trigger"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-triggerio" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-triggerio" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse44">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse44" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature java ">Java</span>
								<span class="feature cplusplus ">C++</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free partially">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://viziapps.com/" target="_blank"><img src="<?php echo base_url('img/logos/viziapps.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">ViziApps</h4>
    			<span class="info-label appfactory ">App Factory </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/ViziApps" target="_blank"><i id="twitter-viziapps"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-viziapps" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-viziapps" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse45">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse45" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://v-play.net/" target="_blank"><img src="<?php echo base_url('img/logos/vplay.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">V-Play</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/vplayengine" target="_blank"><i id="twitter-vplayengine"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-vplay" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-vplay" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse46">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse46" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry soon">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus ">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource partially">Open Source</span>
								<span class="feature free partially">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
								<span class="feature enterprise"">Enterprise Lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://wink.apache.org/index.html" target="_blank"><img src="<?php echo base_url('img/logos/wink.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">wink</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status discontinued">Discontinued</span>
<div class="row"><div class="col-xs-4 centered left"><i class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">n/a</span></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/apache-wink" target="_blank"><i id="stackoverflow-apache-wink"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-wink" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-wink" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse47">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse47" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone soon">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http" target="_blank"><img src="<?php echo base_url('img/logos/xamarin.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Xamarin</h4>
    			<span class="info-label sourcecode runtime ">Code translator + Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/xamarinhq" target="_blank"><i id="twitter-xamarinhq"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/xamarin/Xamarin.Forms" target="_blank"><i id="github-xamarin"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/xamarin" target="_blank"><i id="stackoverflow-xamarin"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-xamarin" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-xamarin" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse48">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse48" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature watchos ">Watch OS</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp ">C#</span>
								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://zeptojs.com/#" target="_blank"><img src="<?php echo base_url('img/logos/zeptojs.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Zepto.js</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/zeptojs" target="_blank"><i id="twitter-zeptojs"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/madrobby/zepto" target="_blank"><i id="github-madrobby"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/zepto" target="_blank"><i id="stackoverflow-zepto"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-zeptojs" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-zeptojs" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse49">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse49" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature firefoxos ">Firefox OS</span>
							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp via">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.nativescript.org/" target="_blank"><img src="<?php echo base_url('img/logos/nativescript.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Nativescript</h4>
    			<span class="info-label runtime nativejavascript ">Runtime + Native JavaScript </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/nativescript" target="_blank"><i id="twitter-nativescript"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/NativeScript/NativeScript" target="_blank"><i id="github-nativescript"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/nativescript" target="_blank"><i id="stackoverflow-nativescript"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-nativescript" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-nativescript" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse50">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse50" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature xml ">XML</span>
								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://facebook.github.io/react-native/" target="_blank"><img src="<?php echo base_url('img/logos/reactnative.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">React Native</h4>
    			<span class="info-label nativejavascript ">Native JavaScript </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/reactnative" target="_blank"><i id="twitter-reactnative"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/facebook/react-native" target="_blank"><i id="github-facebook"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/react-native" target="_blank"><i id="stackoverflow-react-native"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-reactnative" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-reactnative" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse51">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse51" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.fusetools.com/" target="_blank"><img src="<?php echo base_url('img/logos/fusetools.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Fusetools</h4>
    			<span class="info-label sourcecode nativejavascript ">Code translator + Native JavaScript </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/fusetools" target="_blank"><i id="twitter-fusetools"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-fusetools" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-fusetools" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse52">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse52" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js ">JavaScript</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free partially">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://tabrisjs.com/" target="_blank"><img src="<?php echo base_url('img/logos/tabrisjs.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">TabrisJS</h4>
    			<span class="info-label runtime nativejavascript ">Runtime + Native JavaScript </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/tabrisjs" target="_blank"><i id="twitter-tabrisjs"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/eclipsesource/tabris-js" target="_blank"><i id="github-eclipsesource"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/tabris" target="_blank"><i id="stackoverflow-tabris"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-tabrisjs" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-tabrisjs" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse53">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse53" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource partially">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://unity3d.com/" target="_blank"><img src="<?php echo base_url('img/logos/unity.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Unity</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/unity3d" target="_blank"><i id="twitter-unity3d"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/unity3d" target="_blank"><i id="stackoverflow-unity3d"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-unity" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-unity" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse54">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse54" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature csharp ">C#</span>
								<span class="feature js ">JavaScript</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.codenameone.com/index.html" target="_blank"><img src="<?php echo base_url('img/logos/codenameone.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Codename One</h4>
    			<span class="info-label sourcecode runtime ">Code translator + Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/codenameonefr" target="_blank"><i id="twitter-codenameonefr"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/codenameone/CodenameOne" target="_blank"><i id="github-codenameone"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/codenameone" target="_blank"><i id="stackoverflow-codenameone"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-codenameone" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-codenameone" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse55">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse55" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature java ">Java</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.mobilesmith.com/" target="_blank"><img src="<?php echo base_url('img/logos/mobilesmith.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">MobileSmith</h4>
    			<span class="info-label appfactory ">App Factory </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/TheMobileSmith" target="_blank"><i id="twitter-themobilesmith"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div></div>			<input id="compare-mobilesmith" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-mobilesmith" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse56">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse56" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature js via">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								<span class="feature html via">HTML</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="http://enyojs.com/" target="_blank"><img src="<?php echo base_url('img/logos/enyojs.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">EnyoJS</h4>
    			<span class="info-label javascript_tool ">JS framework/toolkit </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/EnyoJS" target="_blank"><i id="twitter-enyojs"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/enyojs/enyo" target="_blank"><i id="github-enyojs"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/enyo" target="_blank"><i id="stackoverflow-enyo"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-enyojs" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-enyojs" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse57">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse57" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry ">Blackberry</span>
							<span class="feature windowsphone ">WindowsPhone</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature hybridapp ">Hybrid App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature js ">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								<span class="feature html ">HTML</span>
								<span class="feature css ">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free ">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://www.qt.io/mobile-app-development/" target="_blank"><img src="<?php echo base_url('img/logos/qt.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Qt</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/qtproject" target="_blank"><i id="twitter-qtproject"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/qtproject/qt-creator" target="_blank"><i id="github-qtproject"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/qt" target="_blank"><i id="stackoverflow-qt"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-qt" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-qt" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse58">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse58" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature cplusplus ">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource partially">Open Source</span>
								<span class="feature free ">Free</span>
								<span class="feature commercial"">Commercial lic.</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://livecode.com/" target="_blank"><img src="<?php echo base_url('img/logos/livecode.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">LiveCode</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/LiveCode" target="_blank"><i id="twitter-livecode"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><a href="https://github.com/livecode/livecode" target="_blank"><i id="github-livecode"class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/livecode" target="_blank"><i id="stackoverflow-livecode"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-livecode" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-livecode" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse59">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse59" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature webapp ">Web App</span>
								<span class="feature mobilewebsite ">Mobile website</span>
								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor ">Visual Editor</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    								<span class="feature opensource ">Open Source</span>
								<span class="feature free partially">Free</span>
							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-md-4 col-xs-6 framework">
    	<div class="thumbnail">
    		<a href="https://get.adobe.com/air/" target="_blank"><img src="<?php echo base_url('img/logos/adobeair.png'); ?>" alt=""></a>		<div class="caption">
    			<h4 class="thumb-caption">Adobe AIR</h4>
    			<span class="info-label runtime ">Runtime </span>
			<span class="info-label status active">Active</span>
<div class="row"><div class="col-xs-4 centered left"><a href="https://twitter.com/adobeairruntime" target="_blank"><i id="twitter-adobeairruntime"class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div><div class="col-xs-4 centered"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div><div class="col-xs-4 centered right"><a href="http://stackoverflow.com/questions/tagged/air" target="_blank"><i id="stackoverflow-air"class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div></div>			<input id="compare-adobeair" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>
    			<label for="compare-adobeair" class="checkbox-label compare-label">Compare</label>
				<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>
    			<div>
    				<h4 class="panel-title">
    					<a data-toggle="collapse" aria-expanded="false" href="#collapse60">
    						<div class="panel-heading feature-panel">
    							<span class="dropIcon glyphicon glyphicon-chevron-up"></span>
								<span class="dropIcon glyphicon glyphicon-chevron-down"></span>
    						</div>
						</a>
					</h4>
    				<div id="collapse60" class="panel-collapse collapse">
    					<div>
							<div class="row">
								<div class="col-sm-6">
    								<h4 class="featureTitle">Platform</h4>
    							<span class="feature blackberry partially">Blackberry</span>
							<span class="feature android ">Android</span>
							<span class="feature ios ">iOS</span>
							</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Target</h4>
    								<span class="feature nativeapp ">Native App</span>
							</div>
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Development Language</h4>
    								<span class="feature visualeditor via">Visual Editor</span>
								<span class="feature js via">JavaScript</span>
								<span class="feature java via">Java</span>
								<span class="feature cplusplus via">C++</span>
								<span class="feature html via">HTML</span>
								<span class="feature actionscript ">ActionScript</span>
								<span class="feature css via">CSS</span>
								</div>
    							<div class="col-sm-6">
    								<h4 class="featureTitle">Terms of a License</h4>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
              
          <div id="msg" class="alert alert-info" hidden>
            <strong>Info!</strong> No framework fits the search criteria. Please refine your search input.
          </div>
        </div>
      </div>
    </div>

  </body>
  
</html>