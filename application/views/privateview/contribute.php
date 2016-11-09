<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Contribute Page</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"/>
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
        <?php echo link_tag('fonts/font-awesome/css/font-awesome.min.css'); ?>
        <?php echo link_tag('css/contribute.css'); ?>
        <?php if(isset($admin)) { if($admin == 1) {?>
        <?php echo link_tag('css/contributeAdmin.css'); ?>
        <?php }} ?>

        <!-- Remote scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.js"></script>
        <script src="https://apis.google.com/js/api:client.js"></script>

        <!-- Local scripts -->
        <script type="text/javascript" src="<?php echo base_url();?>js/extern/validator.min.js" ></script><!-- form plugin -->
        <?php if(isset($admin)) { if($admin == 1) {?>
            <script type="text/javascript" src="<?php echo base_url();?>js/private/contributeAdmin.js" ></script>
        <?php } else {?>
            <script type="text/javascript" src="<?php echo base_url();?>js/private/contribute.js" ></script>
        <?php }}?>
    </head>

    <body>
        <!-- MODAL ALERT -->
        <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">User Feedback</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row flex-col">
                            <div class="col-xs-3 centered">
                                <i class="fa fa-check-circle fa-5x alert-icon-success" aria-hidden="true"></i>
                                <i class="fa fa-exclamation-circle fa-5x alert-icon-warning hide" aria-hidden="true"></i>
                                <i class="fa fa-times-circle fa-5x alert-icon-error hide" aria-hidden="true"></i>
                            </div>
                            <div class="col-xs-9">
                                <h4 class="user-feedback">Succesfully added a framework</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 centered" id="modalUserInputWrapper" hidden>
                                <button id="modalYes" class="btn btn-success btn-lg">Yes</button>
                                <button id="modalNo" class="btn btn-default btn-lg" data-toggle="modal" data-target="#alertModal">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- HEADER -->
        <div class="jumbotron">
            <div class="container">
                <h1>Start contributing in 3... 2... 1... GO!</h1>
                <p>You can use the contribution options below to either add a new cross-platform tool or edit an existing tool. Follow the form steps to complete your contribution and add them to the approval queue. Additionally, you can make changes to your pending contributions by choosing the editing option.</p>
                <p>
                    <a class="btn btn-primary btn-lg" href="<?php echo base_url();?>privateCon/" role="button">Home &raquo;</a>
                    <?php if(isset($email)) { ?><button id="socialSignOut" type="button" class="btn btn-danger btn-lg pull-right" data-toggle="tooltip" data-placement="top" title="Signed in as: <?php print $email ?>">Sign out</button><?php } ?>
                </p>
            </div>
        </div>
        <!-- BODY -->
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="btn-group contribute-options" data-toggle="buttons">
                        <label class="btn btn-default btn-lg active">
                            <input type="radio" name="options" id="addNewRad" value="add" autocomplete="off" checked><p class="glyphicon glyphicon-plus" style="display:block;"></p>Add new tool
                        </label>
                        <label class="btn btn-default btn-lg">
                            <input type="radio" name="options" id="editExistingRad" autocomplete="off" value="edit"><p class="glyphicon glyphicon-pencil" style="display:block;"></p>Edit existing tool
                        </label>
                        <?php if(isset($admin)) { if($admin == 1) {?>
                        <label class="btn btn-default btn-lg">
                            <input type="radio" name="options" id="userManageRad" autocomplete="off" value="userManage"><p class="glyphicon glyphicon-user" style="display:block;"></p>Manage users
                        </label>
                        <label class="btn btn-default btn-lg">
                            <input type="radio" name="options" id="contributeManageRad" autocomplete="off" value="contributeManage"><p class="glyphicon glyphicon-list" style="display:block;"></p>Manage contributions
                        </label>
                        <?php }} ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div id="frameworkForm" class="panel panel-default">
                        <div class="panel-body">
                            <div id="frameworkTableWrapper" hidden>
                                <div class="col-sm-6">
                                    <div class="alert alert-info">
                                        <strong><i class="fa fa-hand-o-up" aria-hidden="true"></i> Select - </strong>Make changes to an approved framework
                                    </div>
                                    <table id="searchFrameworksTable"></table>
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-warning">
                                        <strong><i class="fa fa-hand-o-up" aria-hidden="true"></i> Select - </strong>Make changes to your pending contributions
                                    </div>
                                    <table id="searchUserFrameworksTable"></table>
                                </div>
                            </div>
                            <div id="editHeaderWrapper" hidden>
                                <h3 class="edit-header">You are now editing</h3>
                                <div class="pull-right">
                                    <button id="updateEdit" class="btn btn-success btn-lg edit-button"><span class="glyphicon glyphicon-upload"></span> Update</button>
                                    <button id="removeEdit" class="btn btn-danger btn-lg edit-button"><span class="glyphicon glyphicon-trash"></span> Remove</button>
                                    <button id="cancelEdit" class="btn btn-warning btn-lg edit-button"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                                </div>
                            </div>
                            <?php if(isset($admin)) { if($admin == 1) {?>
                            <div id="adminEditHeaderWrapper" hidden>
                                <h3 class="adminEdit-header">Review</h3>
                                <div class="pull-right">
                                    <button id="approveEntry" class="btn btn-success btn-lg edit-button"><span class="glyphicon glyphicon-ok"></span> Approve</button>
                                    <button id="declineEntry" class="btn btn-danger btn-lg edit-button"><span class="glyphicon glyphicon-remove"></span> Decline</button>
                                    <button id="cancelReview" class="btn btn-warning btn-lg edit-button"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                                </div>
                            </div>
                            <?php }} ?>
                            <div id="formWrapper">
                                <div class="progress add-progress">
                                    <div class="progress-bar bordered active-step progress-step1" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;" data-my-value="1">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-circle-o fa-stack-2x"></i>
                                            <strong class="fa-stack-1x calendar-text">1</strong>
                                        </span>
                                    </div>
                                    <div class="progress-bar bordered progress-step2" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;" data-my-value="2">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-circle-o fa-stack-2x"></i>
                                            <strong class="fa-stack-1x calendar-text">2</strong>
                                        </span>
                                    </div>
                                    <div class="progress-bar bordered progress-step3" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;" data-my-value="3">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-circle-o fa-stack-2x"></i>
                                            <strong class="fa-stack-1x calendar-text">3</strong>
                                        </span>
                                    </div>
                                    <div class="progress-bar bordered progress-step4" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;" data-my-value="4">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-circle-o fa-stack-2x"></i>
                                            <strong class="fa-stack-1x calendar-text">4</strong>
                                        </span>
                                    </div>
                                    <div class="progress-bar progress-step5" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;" data-my-value="5">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-circle-o fa-stack-2x"></i>
                                            <strong class="fa-stack-1x calendar-text">5</strong>
                                        </span>
                                    </div>
                                </div>
                                <div id="formCurrent">
                                    <form id="frmStep1" class="form-horizontal container-step1 current-form">
                                        <h3>Add Framework properties</h3>
                                        <div class="form-group has-feedback">
                                            <label for="inputToolname" class="col-xs-5 control-label">Tool name (*)</label>
                                            <div class="col-xs-7">                                            
                                                <input class="form-control" id="inputToolname" name="framework" placeholder="e.g. Phonegap" pattern="[a-z|A-Z|0-9|\-|\.| ]{2,30}" data-pattern-error="Must be between 2 and 30 characters and may not contain special characters (except: '.','-')" required data-custom="unique" data-unique="true" />
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors">Specify the cross-platform tool name</div>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Is the tool still activily maintained?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="statusActive" value="Active" checked name="status"> Active
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="statusDiscontinues" value="Discontinued" name="status"> Discontinued
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label for="inputToolVersion" class="col-xs-5 control-label">Tool's current version</label>
                                            <div class="col-xs-7">
                                                <input class="form-control" id="inputToolVersion" placeholder="e.g. v5.2.1" name="framework_current_version"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors">Specify the current version of the cross-platform tool</div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label for="inputToolAnnounced" class="col-xs-5 control-label">Year of announcement</label>
                                            <div class="col-xs-7">
                                                <input type="number" class="form-control" id="inputToolAnnounced" max="9999" placeholder="e.g. 2015" name="announced"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors">The year of official announcement</div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <!-- Hidden checkbox field is added to ensure a default value when the checkbox is left unchecked
                                                The javascript will send both the hidden value and checked value to server but in php only the
                                                last value is considered valid (so put hidden first and when check occurs we ensure that the checked value
                                                is last) src: http://stackoverflow.com/questions/1809494/post-the-checkboxes-that-are-unchecked -->
                                            <label for="inputToolTechnology" class="col-xs-5 control-label">The used technology</label>
                                            <div class="col-xs-7">
                                                <input type='hidden' value='false' name='webtonative'>
                                                <input type="checkbox" id="chbWebtonative" value="true" class="custom-checkbox" name="webtonative"/>
                                                <label for="chbWebtonative" class="checkbox-label">Web-to-native wrapper</label>
                                                <input type='hidden' value='false' name='nativejavascript'>
                                                <input type="checkbox" id="chbNativejavascript" value="true" class="custom-checkbox" name="nativejavascript"/>
                                                <label for="chbNativejavascript" class="checkbox-label">Native Javascript</label>
                                                <input type='hidden' value='false' name='runtime'>
                                                <input type="checkbox" id="chbRuntime" value="true" class="custom-checkbox" name="runtime"/>
                                                <label for="chbRuntime" class="checkbox-label">Runtime</label>
                                                <input type='hidden' value='false' name='javascript_tool'>
                                                <input type="checkbox" id="chbJavascripttool" value="true" class="custom-checkbox" name="javascript_tool"/>
                                                <label for="chbJavascripttool" class="checkbox-label">JavaScript framework/library/toolkit</label>
                                                <input type='hidden' value='false' name='sourcecode'>
                                                <input type="checkbox" id="chbSourcecode" value="true" class="custom-checkbox" name="sourcecode"/>
                                                <label for="chbSourcecode" class="checkbox-label">Source-code translator</label>
                                                <input type='hidden' value='false' name='appfactory'>
                                                <input type="checkbox" id="chbAppfactory" value="true" class="custom-checkbox" name="appfactory"/>
                                                <label for="chbAppfactory" class="checkbox-label">Appfactory</label>
                                                <div class="help-block">Select multiple if applicable (e.g. runtime + source-code translator). Select none if you do not know.</div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputToolOutputType" class="col-xs-5 control-label">What type of application can you build with the tool? (*)</label>
                                            <div class="col-xs-7">
                                                <input type='hidden' value='false' name='mobilewebsite'>
                                                <input type="checkbox" id="chbMobilewebsite" value="true" class="custom-checkbox" name="mobilewebsite"/>
                                                <label for="chbMobilewebsite" class="checkbox-label">Mobile website</label>
                                                <input type='hidden' value='false' name='webapp'>
                                                <input type="checkbox" id="chbWebapp" value="true" class="custom-checkbox" name="webapp"/>
                                                <label for="chbWebapp" class="checkbox-label">Web application</label>
                                                <input type='hidden' value='false' name='nativeapp'>
                                                <input type="checkbox" id="chbNativeapp" value="true" class="custom-checkbox" name="nativeapp"/>
                                                <label for="chbNativeapp" class="checkbox-label">Native application</label>
                                                <input type='hidden' value='false' name='hybridapp'>
                                                <input type="checkbox" id="chbHybridapp" value="true" class="custom-checkbox" name="hybridapp"/>
                                                <label for="chbHybridapp" class="checkbox-label">Hybrid application</label>
                                                <div class="help-block">Select multiple if applicable (e.g. web app + website + hybrid app).</div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label for="inputToolUrl" class="col-xs-5 control-label">Website URL</label>
                                            <div class="col-xs-7">
                                                <input type="url" class="form-control" id="inputToolUrl" placeholder="e.g. http://phonegap.com" name="url"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group small-bottom-margin">
                                            <label for="inputToolLicense" class="col-xs-5 control-label">License</label>
                                            <div class="col-xs-7">
                                                <input type="checkbox" id="chbFreeLic" value="free_proprietary_lincense" class="custom-checkbox" name="license"/>
                                                <label for="chbFreeLic" class="checkbox-label">Free proprietary license</label>
                                                <input type="checkbox" id="chbIndieLic" value="indie_proprietary_lincense" class="custom-checkbox" name="license"/>
                                                <label for="chbIndieLic" class="checkbox-label">Indie proprietary license</label>
                                                <input type="checkbox" id="chbCommercialLic" value="commercial_proprietary_lincense" class="custom-checkbox" name="license"/>
                                                <label for="chbCommercialLic" class="checkbox-label">Commercial proprietary license</label>
                                                <input type="checkbox" id="chbEnterpriseLic" value="enterprise_proprietary_lincense" class="custom-checkbox" name="license"/>
                                                <label for="chbEnterpriseLic" class="checkbox-label">Enterprise proprietary license</label>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label class="col-xs-5 control-label"></label>
                                            <div class="col-xs-7">
                                                <input pattern="[a-z|A-Z|0-9|\-|\.|\(|\)|,| ]{1,120}" data-pattern-error="License may not contain special characters (except '-','.','()')" class="form-control" id="inputToolLicense" placeholder="e.g. MIT-License" name="license"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors">Seperate multiple entries with ','</div>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Is the tool free to use?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="freeTrue" value="true" name="free"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="freeFalse" value="false" checked name="free"> No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Is the tool open-source?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="opensourceTrue" value="true" name="opensource"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="opensourceFalse" value="false" checked name="opensource"> No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">        
                                            <label class="col-xs-5 control-label">Does the tool provide a trial version?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="trialTrue" value="true" name="trial"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="trialFalse" value="false" checked name="trial"> No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">        
                                            <label class="col-xs-5 control-label">Is the tool suited for game development?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="gamesTrue" value="true" name="games"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="gamesFalse" value="false" checked name="games"> No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">        
                                            <label class="col-xs-5 control-label">Does the tool provide multiple screen support?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="multi_screenTrue" value="true" name="multi_screen"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="multi_screenFalse" value="false" name="multi_screen"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="multi_screenPartially" value="partially" name="multi_screen"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="multi_screenSoon" value="soon" name="multi_screen"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="multi_screenVia" value="via" name="multi_screen"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="multi_screenUndef" value="UNDEF" checked name="multi_screen"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">        
                                            <label class="col-xs-5 control-label">Does the tool provide prototyping features?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="allows_prototypingTrue" value="true" name="allows_prototyping"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="allows_prototypingFalse" value="false" name="allows_prototyping"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="allows_prototypingPartially" value="partially" name="allows_prototyping"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="allows_prototypingSoon" value="soon" name="allows_prototyping"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="allows_prototypingVia" value="via" name="allows_prototyping"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="allows_prototypingUndef" value="UNDEF" checked name="allows_prototyping"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">        
                                            <label class="col-xs-5 control-label">Provide a logo for the tool<div class="help-block">Logo format should be ".png"<br/>Smaller than 100kB<br/>Max width and height of 200px and 80px</div></label>
                                            <div class="col-xs-7">
                                                <img id="previewLogo" src="<?php echo base_url();?>img/logos/notfound.png" height="80px"/>
                                                <input type="file" id="logo" />
                                                <div class="logo-msg help-block"></div>
                                            </div>
                                        </div>
                                    </form>
                                    <form id="frmStep2" data-toggle="validator" role="form" class="form-horizontal container-step2 current-form" hidden>                               
                                        <h3>Add Framework resources</h3>
                                        <div class="form-group has-feedback ">
                                            <label for="inputResDocs" class="col-xs-5 control-label">Official documentation</label>
                                            <div class="col-xs-7">
                                                <input type="url" class="form-control" id="inputResDocs" placeholder="e.g. http://docs.phonegap.com" name="documentation_url"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label for="inputResBooks" class="col-xs-5 control-label">Available book(s)</label>
                                            <div class="col-xs-7">
                                                <input type="url" class="form-control" id="inputResBooks" placeholder="e.g. http://phonegap.com/book" name="book"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label for="inputResVideo" class="col-xs-5 control-label">Available video</label>
                                            <div class="col-xs-7">
                                                <input type="url" class="form-control" id="inputResVideo" placeholder="e.g. https://www.youtube.com/user/PhoneGap" name="video_url"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors">Could be a link to promotion video or video tutorial or video channel</div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label for="inputResMarket" class="col-xs-5 control-label">Marketplace for components/plugins/etc</label>
                                            <div class="col-xs-7">
                                                <input type="url" class="form-control" id="inputResMarket" placeholder="e.g. http://www.plugreg.com/plugins" name="market"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label for="inputResAppshowcase" class="col-xs-5 control-label">Application gallery</label>
                                            <div class="col-xs-7">
                                                <input type="url" class="form-control" id="inputResAppshowcase" placeholder="e.g. http://phonegap.com/app" name="appshowcase"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <h3>Social activity features</h3>
                                        <div class="form-group has-feedback ">
                                            <label for="inputResTwitter" class="col-xs-5 control-label">Twitter page</label>
                                            <div class="col-xs-7">
                                                <input type="url" class="form-control" id="inputResTwitter" placeholder="e.g. https://twitter.com/phonegap" name="twitter"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label for="inputResStackoverflow" class="col-xs-5 control-label">Stackoverflow tagged questions</label>
                                            <div class="col-xs-7">
                                                <input type="url" class="form-control" id="inputResStackoverflow" placeholder="e.g. http://stackoverflow.com/questions/tagged/cordova" name="stackoverflow"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label for="inputResRepo" class="col-xs-5 control-label">Github repository</label>
                                            <div class="col-xs-7">
                                                <input type="url" class="form-control" id="inputResRepo" placeholder="e.g. https://github.com/apache/cordova-android" name="repo"/>
                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <div class="help-block with-errors">Specify if available</div>
                                            </div>
                                        </div>
                                        <h3>Specify tool vendor developer support features</h3>
                                        <div class="form-group">
                                            <label class="col-xs-5 control-label">Onsite support available?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" name="onsite_supp" id="onsiteTrue" value="true" > Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="onsite_supp" id="onsiteFalse" value="false" > No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="onsite_supp" id="onsiteUndef" value="UNDEF" checked> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-xs-5 control-label">Hired help available?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" name="hired_help" id="hiredTrue" value="true" > Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="hired_help" id="hiredFalse" value="false" > No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="hired_help" id="hiredUndef" value="UNDEF" checked> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-xs-5 control-label">Phone support available?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" name="phone_supp" id="phoneSupTrue" value="true" > Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="phone_supp" id="phoneSupFalse" value="false" > No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="phone_supp" id="phoneSupUndef" value="UNDEF" checked> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-xs-5 control-label">Time-delayed support available?<div class="help-block">Like email support.</div></label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" name="time_delayed_supp" id="delaySupTrue" value="true" > Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="time_delayed_supp" id="delaySupFalse" value="false" > No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="time_delayed_supp" id="delaySupUndef" value="UNDEF" checked> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-xs-5 control-label">Community support available?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" name="community_supp" id="communitySupTrue" value="true" > Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="community_supp" id="communitySupFalse" value="false" > No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="community_supp" id="communitySupUndef" value="UNDEF" checked> Don't know
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                    <form id="frmStep3" data-toggle="validator" role="form" class="form-horizontal container-step3 current-form" hidden>
                                        <h3>Specify which of the following platforms the tool supports</h3>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Android</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="androidTrue" value="true" checked name="android"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="androidFalse" value="false" name="android"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="androidPartially" value="partially" name="android"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="androidSoon" value="soon" name="android"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">iOS</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="iosTrue" value="true" checked name="ios"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="iosFalse" value="false" name="ios"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="iosPartially" value="partially" name="ios"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="iosSoon" value="soon" name="ios"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Blackberry</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="blackberryTrue" value="true" name="blackberry"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="blackberryFalse" value="false" checked name="blackberry"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="blackberryPartially" value="partially" name="blackberry"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="blackberrySoon" value="soon" name="blackberry"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Windows Mobile</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsMobileTrue" value="true" name="windowsmobile"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsMobileFalse" value="false" checked name="windowsmobile"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsMobilePartially" value="partially" name="windowsmobile"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsMobileSoon" value="soon" name="windowsmobile"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Windows Phone</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsPhoneTrue" value="true" name="windowsphone"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsPhoneFalse" value="false" checked name="windowsphone"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsPhonePartially" value="partially" name="windowsphone"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsPhoneSoon" value="soon" name="windowsphone"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Windows Universal Platform (WUP)</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="wupTrue" value="true" name="wup"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="wupFalse" value="false" checked name="wup"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="wupPartially" value="partially" name="wup"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="wupSoon" value="soon" name="wup"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Windows</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsTrue" value="true" name="windows"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsFalse" value="false" checked name="windows"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsPartially" value="partially" name="windows"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="windowsSoon" value="soon" name="windows"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Mac OSX</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="osxTrue" value="true" name="osx"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="osxFalse" value="false" checked name="osx"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="osxPartially" value="partially" name="osx"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="osxSoon" value="soon" name="osx"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Firefox OS</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="firefoxosTrue" value="true" name="firefoxos"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="firefoxosFalse" value="false" checked name="firefoxos"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="firefoxosPartially" value="partially" name="firefoxos"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="firefoxosSoon" value="soon" name="firefoxos"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Tizen</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="tizenTrue" value="true" name="tizen"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="tizenFalse" value="false" checked name="tizen"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="tizenPartially" value="partially" name="tizen"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="tizenSoon" value="soon" name="tizen"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Symbian</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="symbianTrue" value="true" name="symbian"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="symbianFalse" value="false" checked name="symbian"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="symbianPartially" value="partially" name="symbian"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="symbianSoon" value="soon" name="symbian"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Apple TV</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="appletvTrue" value="true" name="appletv"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="appletvFalse" value="false" checked name="appletv"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="appletvPartially" value="partially" name="appletv"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="appletvSoon" value="soon" name="appletv"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Android TV</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="androidtvTrue" value="true" name="androidtv"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="androidtvFalse" value="false" checked name="androidtv"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="androidtvPartially" value="partially" name="androidtv"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="androidtvSoon" value="soon" name="androidtv"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Watch OS</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="watchosTrue" value="true" name="watchos"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="watchosFalse" value="false" checked name="watchos"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="watchosPartially" value="partially" name="watchos"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="watchosSoon" value="soon" name="watchos"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Bada</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="badaTrue" value="true" name="bada"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="badaFalse" value="false" checked name="bada"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="badaPartially" value="partially" name="bada"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="badaSoon" value="soon" name="bada"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Web OS</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="webosTrue" value="true" name="webos"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="webosFalse" value="false" checked name="webos"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="webosPartially" value="partially" name="webos"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="webosSoon" value="soon" name="webos"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Kindle</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="kindleTrue" value="true" name="kindle"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="kindleFalse" value="false" checked name="kindle"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="kindlePartially" value="partially" name="kindle"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="kindleSoon" value="soon" name="kindle"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Other Platforms</label>
                                            <div class="col-xs-7">
                                                <div class="input-group short-input">
                                                    <span class="input-group-addon">
                                                        <input type="radio">
                                                    </span>
                                                    <input type="text" class="form-control">
                                                </div>
                                                <div class="help-block">Seperate multiple entries by ','.</div>
                                            </div>
                                        </div>
                                        <h3>Available developer features</h3>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Software Development Kit (SDK)</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="sdkTrue" value="true" name="sdk"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="sdkFalse" value="false" checked name="sdk"> No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">User interface widgets availability</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="widgetsTrue" value="true" name="widgets"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="widgetsFalse" value="false" name="widgets"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="widgetsPartially" value="partially" name="widgets"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="widgetsSoon" value="soon" name="widgets"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="widgetsVia" value="via" name="widgets"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="widgetsUndef" value="UNDEF" checked name="widgets"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Support for animation creation</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="animationsTrue" value="true" name="animations"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="animationsFalse" value="false" name="animations"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="animationsPartially" value="partially" name="animations"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="animationsSoon" value="soon" name="animations"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="animationsVia" value="via" name="animations"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="animationsUndef" value="UNDEF" checked name="animations"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Publication Assistance</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="publ_assistTrue" value="true" name="publ_assist"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="publ_assistFalse" value="false" name="publ_assist"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="publ_assistPartially" value="partially" name="publ_assist"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="publ_assistSoon" value="soon" name="publ_assist"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="publ_assistVia" value="via" name="publ_assist"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="publ_assistUndef" value="UNDEF" checked name="publ_assist"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Livesync during development</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="livesyncTrue" value="true" name="livesync"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="livesyncFalse" value="false" name="livesync"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="livesyncPartially" value="partially" name="livesync"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="livesyncSoon" value="soon" name="livesync"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="livesyncVia" value="via" name="livesync"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="livesyncUndef" value="UNDEF" checked name="livesync"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Cloud development options (e.g. cloud-build service)</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="clouddevTrue" value="true" name="clouddev"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="clouddevFalse" value="false" name="clouddev"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="clouddevPartially" value="partially" name="clouddev"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="clouddevSoon" value="soon" name="clouddev"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="clouddevVia" value="via" name="clouddev"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="clouddevUndef" value="UNDEF" checked name="clouddev"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Does the tool allow application updates without passing through appstore submission?</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="remoteupdateTrue" value="true" name="remoteupdate"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="remoteupdateFalse" value="false" name="remoteupdate"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="remoteupdatePartially" value="partially" name="remoteupdate"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="remoteupdateSoon" value="soon" name="remoteupdate"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="remoteupdateVia" value="via" name="remoteupdate"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="remoteupdateUndef" value="UNDEF" checked name="remoteupdate"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                    <form id="frmStep4" data-toggle="validator" role="form" class="form-horizontal container-step4 current-form" hidden>
                                        <h3>Specify which of the following programming languages the tool uses</h3>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">HTML</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="htmlTrue" value="true" name="html"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="htmlFalse" value="false" checked name="html"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="htmlPartially" value="partially" name="html"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="htmlSoon" value="soon" name="html"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">CSS</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="cssTrue" value="true" name="css"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cssFalse" value="false" checked name="css"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cssPartially" value="partially" name="css"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cssSoon" value="soon" name="css"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">JavaScript</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="jsTrue" value="true" name="js"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="jsFalse" value="false" checked name="js"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="jsPartially" value="partially" name="js"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="jsSoon" value="soon" name="js"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Java</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="javaTrue" value="true" name="java"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="javaFalse" value="false" checked name="java"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="javaPartially" value="partially" name="java"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="javaSoon" value="soon" name="java"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Objective C</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="objcTrue" value="true" name="objc"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="objcFalse" value="false" checked name="objc"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="objcPartially" value="partially" name="objc"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="objcSoon" value="soon" name="objc"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Swift</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="swiftTrue" value="true" name="swift"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="swiftFalse" value="false" checked name="swift"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="swiftPartially" value="partially" name="swift"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="swiftSoon" value="soon" name="swift"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">C++</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="cplusplusTrue" value="true" name="cplusplus"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cplusplusFalse" value="false" checked name="cplusplus"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cplusplusPartially" value="partially" name="cplusplus"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cplusplusSoon" value="soon" name="cplusplus"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">C#</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="csharpTrue" value="true" name="csharp"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="csharpFalse" value="false" checked name="csharp"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="csharpPartially" value="partially" name="csharp"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="csharpSoon" value="soon" name="csharp"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">PHP</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="phpTrue" value="true" name="php"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="phpFalse" value="false" checked name="php"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="phpPartially" value="partially" name="php"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="phpSoon" value="soon" name="php"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Python</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="pythonTrue" value="true" name="python"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="pythonFalse" value="false" checked name="python"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="pythonPartially" value="partially" name="python"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="pythonSoon" value="soon" name="python"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Object Pascal</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="objpascalTrue" value="true" name="objpascal"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="objpascalFalse" value="false" checked name="objpascal"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="objpascalPartially" value="partially" name="objpascal"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="objpascalSoon" value="soon" name="objpascal"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Ruby</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="rubyTrue" value="true" name="ruby"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="rubyFalse" value="false" checked name="ruby"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="rubyPartially" value="partially" name="ruby"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="rubySoon" value="soon" name="ruby"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Basic</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="basicTrue" value="true" name="basic"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="basicFalse" value="false" checked name="basic"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="basicPartially" value="partially" name="basic"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="basicSoon" value="soon" name="basic"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">XML</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="xmlTrue" value="true" name="xml"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="xmlFalse" value="false" checked name="xml"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="xmlPartially" value="partially" name="xml"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="xmlSoon" value="soon" name="xml"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">JSX</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="jsxTrue" value="true" name="jsx"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="jsxFalse" value="false" checked name="jsx"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="jsxPartially" value="partially" name="jsx"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="jsxSoon" value="soon" name="jsx"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">QML</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="qmlTrue" value="true" name="qml"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="qmlFalse" value="false" checked name="qml"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="qmlPartially" value="partially" name="qml"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="qmlSoon" value="soon" name="qml"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">LUA</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="luaTrue" value="true" name="lua"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="luaFalse" value="false" checked name="lua"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="luaPartially" value="partially" name="lua"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="luaSoon" value="soon" name="lua"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Actionscript</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="actionscriptTrue" value="true" name="actionscript"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="actionscriptFalse" value="false" checked name="actionscript"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="actionscriptPartially" value="partially" name="actionscript"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="actionscriptSoon" value="soon" name="actionscript"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Java ME</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="javameTrue" value="true" name="javame"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="javameFalse" value="false" checked name="javame"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="javamePartially" value="partially" name="javame"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="javameSoon" value="soon" name="javame"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">MXML</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="MXMLTrue" value="true" name="MXML"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="MXMLFalse" value="false" checked name="MXML"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="MXMLPartially" value="partially" name="MXML"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="MXMLSoon" value="soon" name="MXML"> Soon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">WYSIWYG (visual editor)</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="visualeditorTrue" value="true" name="visualeditor"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="visualeditorFalse" value="false" checked name="visualeditor"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="visualeditorPartially" value="partially" name="visualeditor"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="visualeditorSoon" value="soon" name="visualeditor"> Soon
                                                </label>
                                            </div>
                                        </div>
                                            <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Other Languages</label>
                                            <div class="col-xs-7">
                                                <div class="input-group short-input">
                                                    <span class="input-group-addon">
                                                        <input type="radio" aria-label="...">
                                                    </span>
                                                    <input type="text" class="form-control" aria-label="...">
                                                </div>
                                                <div class="help-block">Seperate multiple entries by ','.</div>
                                            </div>
                                        </div>
                                    </form>
                                    <form id="frmStep5" data-toggle="validator" role="form" class="form-horizontal container-step5 current-form" hidden>
                                        <h3>Specify which of the following hardware features are supported</h3>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Accelerometer</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="accelerometerTrue" value="true" name="accelerometer"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="accelerometerFalse" value="false" checked name="accelerometer"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="accelerometerPartially" value="partially" name="accelerometer"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="accelerometerSoon" value="soon" name="accelerometer"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="accelerometerVia" value="via" name="accelerometer"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Device</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="deviceTrue" value="true" name="device"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="deviceFalse" value="false" checked name="device"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="devicePartially" value="partially" name="device"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="deviceSoon" value="soon" name="device"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="deviceVia" value="via" name="device"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">File</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="fileTrue" value="true" name="file"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="fileFalse" value="false" checked name="file"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="filePartially" value="partially" name="file"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="fileSoon" value="soon" name="file"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="fileVia" value="via" name="file"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Bluetooth</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="bluetoothTrue" value="true" name="bluetooth"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="bluetoothFalse" value="false" checked name="bluetooth"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="bluetoothPartially" value="partially" name="bluetooth"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="bluetoothSoon" value="soon" name="bluetooth"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="bluetoothVia" value="via" name="bluetooth"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Camera</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="cameraTrue" value="true" name="camera"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cameraFalse" value="false" checked name="camera"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cameraPartially" value="partially" name="camera"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cameraSoon" value="soon" name="camera"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cameraVia" value="via" name="camera"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Capture</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="captureTrue" value="true" name="capture"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="captureFalse" value="false" checked name="capture"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="capturePartially" value="partially" name="capture"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="captureSoon" value="soon" name="capture"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="captureVia" value="via" name="capture"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Geolocation</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="geolocationTrue" value="true" name="geolocation"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="geolocationFalse" value="false" checked name="geolocation"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="geolocationPartially" value="partially" name="geolocation"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="geolocationSoon" value="soon" name="geolocation"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="geolocationVia" value="via" name="geolocation"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Multi-touch Gestures</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="gestures_multitouchTrue" value="true" name="gestures_multitouch"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="gestures_multitouchFalse" value="false" checked name="gestures_multitouch"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="gestures_multitouchPartially" value="partially" name="gestures_multitouch"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="gestures_multitouchSoon" value="soon" name="gestures_multitouch"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="gestures_multitouchVia" value="via" name="gestures_multitouch"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Compass</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="compassTrue" value="true" name="compass"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="compassFalse" value="false" checked name="compass"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="compassPartially" value="partially" name="compass"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="compassSoon" value="soon" name="compass"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="compassVia" value="via" name="compass"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Connection</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="connectionTrue" value="true" name="connection"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="connectionFalse" value="false" checked name="connection"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="connectionPartially" value="partially" name="connection"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="connectionSoon" value="soon" name="connection"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="connectionVia" value="via" name="connection"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Contacts</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="contactsTrue" value="true" name="contacts"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="contactsFalse" value="false" checked name="contacts"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="contactsPartially" value="partially" name="contacts"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="contactsSoon" value="soon" name="contacts"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="contactsVia" value="via" name="contacts"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Telephone Messages</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="messages_telephoneTrue" value="true" name="messages_telephone"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="messages_telephoneFalse" value="false" checked name="messages_telephone"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="messages_telephonePartially" value="partially" name="messages_telephone"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="messages_telephoneSoon" value="soon" name="messages_telephone"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="messages_telephoneVia" value="via" name="messages_telephone"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Native Events</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="nativeeventsTrue" value="true" name="nativeevents"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="nativeeventsFalse" value="false" checked name="nativeevents"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="nativeeventsPartially" value="partially" name="nativeevents"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="nativeeventsSoon" value="soon" name="nativeevents"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="nativeeventsVia" value="via" name="nativeevents"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">NFC</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="nfcTrue" value="true" name="nfc"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="nfcFalse" value="false" checked name="nfc"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="nfcPartially" value="partially" name="nfc"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="nfcSoon" value="soon" name="nfc"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="nfcVia" value="via" name="nfc"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Notifications</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="notificationTrue" value="true" name="notification"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="notificationFalse" value="false" checked name="notification"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="notificationPartially" value="partially" name="notification"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="notificationSoon" value="soon" name="notification"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="notificationVia" value="via" name="notification"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Storage</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="storageTrue" value="true" name="storage"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="storageFalse" value="false" checked name="storage"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="storagePartially" value="partially" name="storage"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="storageSoon" value="soon" name="storage"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="storageVia" value="via" name="storage"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Vibration</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="vibrationTrue" value="true" name="vibration"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="vibrationFalse" value="false" checked name="vibration"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="vibrationPartially" value="partially" name="vibration"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="vibrationSoon" value="soon" name="vibration"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="vibrationVia" value="via" name="vibration"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Accessibility</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="accessibilityTrue" value="true" name="accessibility"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="accessibilityFalse" value="false" checked name="accessibility"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="accessibilityPartially" value="partially" name="accessibility"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="accessibilitySoon" value="soon" name="accessibility"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="accessibilityVia" value="via" name="accessibility"> Via Plugin
                                                </label>
                                            </div>
                                        </div>
                                        <h3>Additional features that the tool offers</h3>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Supports inclusion of Advertisement</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="adsTrue" value="true" name="ads"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="adsFalse" value="false" name="ads"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="adsPartially" value="partially" name="ads"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="adsSoon" value="soon" name="ads"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="adsVia" value="via" name="ads"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="adsUndef" value="UNDEF" checked name="ads"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Corporate design and branding</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="cdTrue" value="true" name="cd"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cdFalse" value="false" name="cd"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cdPartially" value="partially" name="cd"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cdSoon" value="soon" name="cd"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cdVia" value="via" name="cd"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="cdUndef" value="UNDEF" checked name="cd"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="col-xs-5 control-label">Advanced security</label>
                                            <div class="col-xs-7">
                                                <label class="radio-inline">
                                                    <input type="radio" id="encryptionTrue" value="true" name="encryption"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="encryptionFalse" value="false" name="encryption"> No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="encryptionPartially" value="partially" name="encryption"> Partially
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="encryptionSoon" value="soon" name="encryption"> Soon
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="encryptionVia" value="via" name="encryption"> Supplied by third party
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" id="encryptionUndef" value="UNDEF" checked name="encryption"> Don't know
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php if(isset($admin)) { if($admin == 1) {?>
                                <!-- ro stands for ReadOnly -->
                                <div id="formRefered" class="col-xs-4" hidden>
                                    <form id="frmStep1Ro" role="form" class="form-horizontal container-ro-step1 refered-form">
                                        <h3>Original framework properties</h3>
                                        <div class="form-group">
                                            <input class="form-control" id="inputToolnameRo" name="frameworkRo" readonly/>
                                            <div class="help-block">Specify the cross-platform tool name</div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline ">
                                                <input type="radio" value="Active" name="statusRo"><span> Active</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="Discontinued" name="statusRo"><span> Discontinued</span>
                                            </label>
                                        </div>
                                        <div class="form-group ">
                                            <input class="form-control" name="framework_current_versionRo" readonly/>
                                            <div class="help-block">Specify the current version of the cross-platform tool</div>
                                        </div>
                                        <div class="form-group">
                                            <input type="number" class="form-control"  max="9999" name="announcedRo" readonly/>
                                            <div class="help-block">The year of official announcement</div>
                                        </div>
                                        <div class="form-group "> 
                                            <input type='hidden' value='false' name='webtonativeRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="webtonativeRo"/>
                                            <label class="checkbox-label-ro">Web-to-native wrapper</label>
                                            <input type='hidden' value='false' name='nativejavascriptRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="nativejavascriptRo"/>
                                            <label class="checkbox-label-ro">Native Javascript</label>
                                            <input type='hidden' value='false' name='runtimeRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="runtimeRo"/>
                                            <label class="checkbox-label-ro">Runtime</label>
                                            <input type='hidden' value='false' name='javascript_toolRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="javascript_toolRo"/>
                                            <label class="checkbox-label-ro">JavaScript framework/library/toolkit</label>
                                            <input type='hidden' value='false' name='sourcecodeRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="sourcecodeRo"/>
                                            <label class="checkbox-label-ro">Source-code translator</label>
                                            <input type='hidden' value='false' name='appfactoryRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="appfactoryRo"/>
                                            <label class="checkbox-label-ro">Appfactory</label>
                                            <div class="help-block">Select multiple if applicable (e.g. runtime + source-code translator). Select none if you do not know.</div>
                                        </div>
                                        <div class="form-group">
                                            <input type='hidden' value='false' name='mobilewebsiteRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="mobilewebsiteRo"/>
                                            <label class="checkbox-label-ro">Mobile website</label>
                                            <input type='hidden' value='false' name='webappRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="webappRo"/>
                                            <label class="checkbox-label-ro">Web application</label>
                                            <input type='hidden' value='false' name='nativeappRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="nativeappRo"/>
                                            <label class="checkbox-label-ro">Native application</label>
                                            <input type='hidden' value='false' name='hybridappRo'>
                                            <input type="checkbox"  value="true" class="custom-checkbox-ro" name="hybridappRo"/>
                                            <label class="checkbox-label-ro">Hybrid application</label>
                                            <div class="help-block">Select multiple if applicable (e.g. web app + website + hybrid app).</div>
                                        </div>
                                        <div class="form-group">
                                            <input type="url" class="form-control" name="urlRo" readonly/>
                                            <div class="help-block"></div>
                                        </div>
                                        <div class="form-group small-bottom-margin">
                                            <input type="checkbox" value="free_proprietary_lincense" class="custom-checkbox-ro" name="licenseRo"/>
                                            <label class="checkbox-label-ro">Free proprietary license</label>
                                            <input type="checkbox" value="indie_proprietary_lincense" class="custom-checkbox-ro" name="licenseRo"/>
                                            <label class="checkbox-label-ro">Indie proprietary license</label>
                                            <input type="checkbox" value="commercial_proprietary_lincense" class="custom-checkbox-ro" name="licenseRo"/>
                                            <label class="checkbox-label-ro">Commercial proprietary license</label>
                                            <input type="checkbox" value="enterprise_proprietary_lincense" class="custom-checkbox-ro" name="licenseRo"/>
                                            <label class="checkbox-label-ro">Enterprise proprietary license</label>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="licenseRo" readonly/>
                                            <div class="help-block">Seperate multiple entries with ','</div>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline ">
                                                <input type="radio"  value="true" name="freeRo"><span> Yes</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="freeRo"><span> No</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline ">
                                                <input type="radio"  value="true" name="opensourceRo"><span> Yes</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="opensourceRo"><span> No</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline ">
                                                <input type="radio"  value="true" name="trialRo"><span> Yes</span>
                                            </label>
                                            <label class="radio-inline active ">
                                                <input type="radio"  value="false" name="trialRo"><span> No</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">        
                                            <label class="radio-inline ">
                                                <input type="radio"  value="true" name="gamesRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="gamesRo"><span> No</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">        
                                            <label class="radio-inline ">
                                                <input type="radio"  value="true" name="multi_screenRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio"  value="false" name="multi_screenRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio"  value="partially" name="multi_screenRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio"  value="soon" name="multi_screenRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio"  value="via" name="multi_screenRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="multi_screenRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input ">        
                                            <label class="radio-inline ">
                                                <input type="radio"  value="true" name="allows_prototypingRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio"  value="false" name="allows_prototypingRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio"  value="partially" name="allows_prototypingRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio"  value="soon" name="allows_prototypingRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio"  value="via" name="allows_prototypingRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="allows_prototypingRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">        
                                            <img id="previewLogoRo" height="80px"/>
                                        </div>
                                    </form>
                                    <form id="frmStep2Ro" role="form" class="form-horizontal container-ro-step2 refered-form" hidden>                               
                                        <h3>Framework resources</h3>
                                        <div class="form-group">
                                            <input type="url" class="form-control" name="documentation_urlRo" readonly/>
                                            <div class="help-block"></div>
                                        </div>
                                        <div class="form-group">
                                            <input type="url" class="form-control" name="bookRo" readonly/>
                                            <div class="help-block"></div>
                                        </div>
                                        <div class="form-group">
                                            <input type="url" class="form-control" name="video_urlRo" readonly/>
                                            <div class="help-block">Could be a Link to promotion video or video tutorial or video channel</div>
                                        </div>
                                        <div class="form-group">
                                            <input type="url" class="form-control" name="marketRo" readonly/>
                                            <div class="help-block"></div>
                                        </div>
                                        <div class="form-group">
                                            <input type="url" class="form-control" name="appshowcaseRo" readonly/>
                                            <div class="help-block"></div>
                                        </div>
                                        <h3>Social activity features</h3>
                                        <div class="form-group">
                                            <input type="url" class="form-control" name="twitterRo" readonly/>
                                            <div class="help-block"></div>
                                        </div>
                                        <div class="form-group">
                                            <input type="url" class="form-control" name="stackoverflowRo" readonly/>
                                            <div class="help-block"></div>
                                        </div>
                                        <div class="form-group">
                                            <input type="url" class="form-control" name="repoRo" readonly/>
                                            <div class="help-block">Specify if available</div>
                                        </div>
                                        <h3>Developer support features</h3>
                                        <div class="form-group">
                                            <label class="radio-inline ">
                                                <input type="radio" name="onsite_suppRo"  value="true"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="onsite_suppRo"  value="false"><span> No</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="onsite_suppRo"  value="UNDEF"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="radio-inline ">
                                                <input type="radio" name="hired_helpRo"   value="true"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="hired_helpRo"   value="false"><span> No</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="hired_helpRo"   value="UNDEF"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="radio-inline ">
                                                <input type="radio" name="phone_suppRo"   value="true"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="phone_suppRo"   value="false"><span> No</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="phone_suppRo"   value="UNDEF"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group adjust-height-la">
                                            <label class="radio-inline ">
                                                <input type="radio" name="time_delayed_suppRo"   value="true"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="time_delayed_suppRo"   value="false"><span> No</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="time_delayed_suppRo"   value="UNDEF"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="radio-inline ">
                                                <input type="radio" name="community_suppRo"   value="true"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="community_suppRo"   value="false"><span> No</span>
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="community_suppRo"   value="UNDEF"><span> Don't know</span>
                                            </label>
                                        </div>
                                    </form>
                                    <form id="frmStep3Ro" role="form" class="form-horizontal container-ro-step3 refered-form" hidden>
                                        <h3>Original supported platforms</h3>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true"  name="androidRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="androidRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="androidRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="androidRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true"  name="iosRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="iosRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="iosRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="iosRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="blackberryRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="blackberryRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="blackberryRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="blackberryRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="windowsmobileRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="windowsmobileRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="windowsmobileRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="windowsmobileRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="windowsphoneRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="windowsphoneRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="windowsphoneRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="windowsphoneRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="wupRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="wupRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="wupRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="wupRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="windowsRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="windowsRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="windowsRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="windowsRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="osxRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="osxRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="osxRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="osxRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="firefoxosRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="firefoxosRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="firefoxosRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="firefoxosRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="tizenRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="tizenRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="tizenRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="tizenRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="symbianRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="symbianRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="symbianRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="symbianRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="appletvRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="appletvRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="appletvRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="appletvRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="androidtvRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="androidtvRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="androidtvRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="androidtvRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="watchosRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="watchosRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="watchosRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="watchosRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="badaRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="badaRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="badaRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="badaRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="webosRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="webosRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="webosRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="webosRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="kindleRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="kindleRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="kindleRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="kindleRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <div class="input-group short-input">
                                                <span class="input-group-addon">
                                                    <input type="radio">
                                                </span>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                            <div class="help-block">Seperate multiple entries by ','.</div>
                                        </div>
                                        <h3>Original developer features</h3>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="sdkRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="sdkRo"><span> No</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="widgetsRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="widgetsRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="widgetsRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="widgetsRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="widgetsRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="widgetsRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="animationsRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="animationsRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="animationsRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="animationsRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="animationsRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="animationsRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="publ_assistRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="publ_assistRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="publ_assistRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="publ_assistRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="publ_assistRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="publ_assistRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="livesyncRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="livesyncRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="livesyncRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="livesyncRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="livesyncRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="livesyncRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="clouddevRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="clouddevRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="clouddevRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="clouddevRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="clouddevRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="clouddevRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="remoteupdateRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="remoteupdateRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="remoteupdateRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="remoteupdateRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="remoteupdateRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="remoteupdateRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                    </form>
                                    <form id="frmStep4Ro" role="form" class="form-horizontal container-ro-step4 refered-form" hidden>
                                        <h3>Original supported programming languages</h3>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="htmlRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="htmlRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="htmlRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="htmlRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="cssRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="cssRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="cssRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="cssRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="jsRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="jsRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="jsRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="jsRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="javaRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="javaRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="javaRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="javaRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="objcRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="objcRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="objcRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="objcRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="swiftRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="swiftRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="swiftRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="swiftRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="cplusplusRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="cplusplusRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="cplusplusRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="cplusplusRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="csharpRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="csharpRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="csharpRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="csharpRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="phpRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="phpRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="phpRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="phpRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="pythonRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="pythonRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="pythonRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="pythonRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">                                           
                                            <label class="radio-inline">
                                                <input type="radio" value="true" name="objpascalRo"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="false" name="objpascalRo"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="partially" name="objpascalRo"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="soon" name="objpascalRo"> Soon
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="rubyRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="rubyRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="rubyRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="rubyRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="basicRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="basicRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="basicRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="basicRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="xmlRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="xmlRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="xmlRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="xmlRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="jsxRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="jsxRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="jsxRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="jsxRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="qmlRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="qmlRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="qmlRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="qmlRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="luaRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="luaRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="luaRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="luaRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="actionscriptRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="actionscriptRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="actionscriptRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="actionscriptRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="javameRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="javameRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="javameRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="javameRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="MXMLRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="MXMLRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="MXMLRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="MXMLRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="visualeditorRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="visualeditorRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="visualeditorRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="visualeditorRo"><span> Soon</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <div class="input-group short-input">
                                                <span class="input-group-addon">
                                                    <input type="radio" aria-label="...">
                                                </span>
                                                <input type="text" class="form-control" aria-label="...">
                                            </div>
                                            <div class="help-block">Seperate multiple entries by ','.</div>
                                        </div>
                                    </form>
                                    <form id="frmStep5Ro" role="form" class="form-horizontal container-ro-step5 refered-form" hidden>
                                        <h3>Original hardware features</h3>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="accelerometerRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="accelerometerRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="accelerometerRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="accelerometerRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="accelerometerRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="deviceRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="deviceRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="deviceRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="deviceRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="deviceRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="fileRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="fileRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="fileRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="fileRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="fileRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="bluetoothRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="bluetoothRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="bluetoothRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="bluetoothRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="bluetoothRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="cameraRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="cameraRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="cameraRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="cameraRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="cameraRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="captureRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="captureRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="captureRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="captureRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="captureRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="geolocationRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="geolocationRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="geolocationRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="geolocationRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="geolocationRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="gestures_multitouchRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="gestures_multitouchRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="gestures_multitouchRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="gestures_multitouchRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="gestures_multitouchRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="compassRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="compassRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="compassRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="compassRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="compassRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="connectionRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="connectionRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="connectionRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="connectionRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="connectionRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="contactsRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="contactsRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="contactsRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="contactsRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="contactsRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="messages_telephoneRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="messages_telephoneRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="messages_telephoneRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="messages_telephoneRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="messages_telephoneRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="nativeeventsRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="nativeeventsRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="nativeeventsRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="nativeeventsRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="nativeeventsRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="nfcRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="nfcRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="nfcRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="nfcRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="nfcRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="notificationRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="notificationRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="notificationRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="notificationRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="notificationRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="storageRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="storageRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="storageRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="storageRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="storageRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="vibrationRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="vibrationRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="vibrationRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="vibrationRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="vibrationRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="accessibilityRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false"  name="accessibilityRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="accessibilityRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="accessibilityRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="accessibilityRo"><span> Via Plugin</span>
                                            </label>
                                        </div>
                                        <h3>Additional features</h3>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="adsRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="adsRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="adsRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="adsRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="adsRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="adsRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="cdRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="cdRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="cdRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="cdRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="cdRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="cdRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                        <div class="form-group dense-input">
                                            <label class="radio-inline">
                                                <input type="radio"  value="true" name="encryptionRo"><span> Yes</span> 
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="false" name="encryptionRo"><span> No</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="partially" name="encryptionRo"><span> Partially</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="soon" name="encryptionRo"><span> Soon</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="via" name="encryptionRo"><span> Supplied by third party</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio"  value="UNDEF"  name="encryptionRo"><span> Don't know</span>
                                            </label>
                                        </div>
                                    </form>
                                </div>
                                <?php }} ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <button id="goNextStepAdd" class="btn btn-success btn-lg pull-right">Next &raquo;</button>
                                        <button id="goBackStepAdd" class="btn btn-warning btn-lg pull-left">&laquo; Back</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(isset($admin)) { if($admin == 1) {?>
                    <div id="userManagement" class="panel panel-default" hidden>
                        <div class="panel-body">
                            <div class="row">
                                <div id="userTableWrapper">
                                    <div class="col-sm-6">
                                        <div class="alert alert-info">
                                            <strong><i class="fa fa-hand-o-up" aria-hidden="true"></i> Select - </strong>Block or select an active user
                                            <i id="refreshActiveUsers" class="fa fa-refresh pull-right" aria-hidden="true"> Refresh</i>
                                        </div>
                                        <table id="activeUsersTable"></table>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="alert alert-warning">
                                            <strong><i class="fa fa-hand-o-up" aria-hidden="true"></i> Select - </strong>Unblock or select a blocked user
                                            <i id="refreshBlockedUsers" class="fa fa-refresh pull-right" aria-hidden="true"> Refresh</i>
                                        </div>
                                        <table id="blockedUsersTable"></table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div id="userContributionWrapper">
                                    <div class="col-xs-12">
                                        <h4>You are viewing all the contributions from user: <h4 id="contributionHeading"></h4></h4>
                                        <table id="userContributionTable"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="contributionManagement" class="panel panel-default" hidden>
                        <div class="panel-body">
                            <div class="row">
                                <div id="contributionTableWrapper">
                                    <div class="col-xs-12">
                                        <div class="alert alert-info">
                                            <strong><i class="fa fa-hand-o-up" aria-hidden="true"></i> Select - </strong>Approve, Adjust or decline a user contribution
                                            <i id="refreshContributionTable" class="fa fa-refresh pull-right" aria-hidden="true"> Refresh</i>
                                        </div>
                                        <table id="contributionTable"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }} ?>
                </div>
            </div>
        </div>
 <!--    
    "learning_curve",
    "perf_overhead",
    "integrate_with_existing_app",
    "iteration_speed",
    "code_sharing",
-->

    </body>
</html>

