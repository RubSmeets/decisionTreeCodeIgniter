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

        <!-- Remote scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script> <!-- form plugin -->

        <!-- Local scripts -->
        <script type="text/javascript" src="<?php echo base_url();?>js/private/contribute.js" ></script>

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
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div id="addForm" class="panel panel-default">
                        <div class="panel-body">
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
                            <form data-toggle="validator" role="form" class="form-horizontal">
                                <div class="container-step1">
                                    <h3>Add Framework properties</h3>
                                    <div class="form-group has-feedback">
                                        <label for="inputToolname" class="col-xs-3 control-label">Tool name</label>
                                        <div class="col-xs-9">
                                            <input class="form-control" id="inputToolname" placeholder="e.g. Phonegap" data-custom-tag="framework" required/>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors">Specify the cross-platform tool name</div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback not-required">
                                        <label for="inputToolVersion" class="col-xs-3 control-label">Tool's current version</label>
                                        <div class="col-xs-9">
                                            <input class="form-control" id="inputToolVersion" placeholder="e.g. v5.2.1" data-custom-tag="framework_current_version"/>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors">Specify the current version of the cross-platform tool</div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback not-required">
                                        <label for="inputToolAnnounced" class="col-xs-3 control-label">Year of announcement</label>
                                        <div class="col-xs-9">
                                            <input type="number" class="form-control" id="inputToolAnnounced" max="9999" placeholder="e.g. 2015" data-custom-tag="announced"/>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors">The year of official announcement</div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback not-required">
                                        <label for="inputToolUrl" class="col-xs-3 control-label">Website URL</label>
                                        <div class="col-xs-9">
                                            <input type="url" class="form-control" id="inputToolUrl" placeholder="e.g. http://phonegap.com" defaultValue="UNDEF" data-custom-tag="url"/>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback not-required">
                                        <label for="inputToolLicense" class="col-xs-3 control-label">License</label>
                                        <div class="col-xs-9">
                                            <input pattern="[^\|]+" data-pattern-error="License may not contain '|' character" class="form-control" id="inputToolLicense" placeholder="e.g. 'MIT-License' or 'proprietary commercial license'" defaultValue="UNDEF" data-custom-tag="license"/>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Is the tool free to use?</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="freeRad" id="freeTrue" value="true" data-custom-tag="free"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="freeRad" id="freeFalse" value="false" checked data-custom-tag="free"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Is the tool open-source?</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="opensourceRad" id="opensourceTrue" value="true" data-custom-tag="opensource"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="opensourceRad" id="opensourceFalse" value="false" checked data-custom-tag="opensource"> No
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                <div class="container-step2" hidden>
                                    <h3>Add Framework resources</h3>
                                    <div class="form-group has-feedback">
                                        <label for="inputResDocs" class="col-xs-3 control-label">Official documentation URL</label>
                                        <div class="col-xs-9">
                                            <input type="url" class="form-control" id="inputResDocs" placeholder="e.g. http://docs.phonegap.com" defaultValue="UNDEF" data-custom-tag="documentation_url"/>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="inputResBooks" class="col-xs-3 control-label">Available book(s) URL</label>
                                        <div class="col-xs-9">
                                            <input type="url" class="form-control" id="inputResBooks" placeholder="e.g. http://phonegap.com/book" defaultValue="UNDEF" data-custom-tag="book"/>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="inputResVideo" class="col-xs-3 control-label">Available video URL</label>
                                        <div class="col-xs-9">
                                            <input type="url" class="form-control" id="inputResVideo" placeholder="e.g. https://www.youtube.com/user/PhoneGap" defaultValue="UNDEF" data-custom-tag="video_url"/>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors">Could be a link to promotion video or video tutorial or video channel</div>
                                        </div>
                                    </div>
                                    <h3>Specify tool vendor developer support features</h3>
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Onsite support available?</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="onsiteRad" id="onsiteTrue" value="true" data-custom-tag="onsite_supp"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="onsiteRad" id="onsiteFalse" value="false" checked data-custom-tag="onsite_supp"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Hired help available?</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="hiredRad" id="hiredTrue" value="true" data-custom-tag="hired_help"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="hiredRad" id="hiredFalse" value="false" checked data-custom-tag="hired_help"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Phone support available?</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="phoneSupRad" id="phoneSupTrue" value="true" data-custom-tag="phone_supp"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="phoneSupRad" id="phoneSupFalse" value="false" checked data-custom-tag="phone_supp"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Time-delayed support available?<div class="help-block">Like email support.</div></label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="delaySupRad" id="delaySupTrue" value="true" data-custom-tag="time_delayed_supp"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="delaySupRad" id="delaySupFalse" value="false" checked data-custom-tag="time_delayed_supp"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Community support available?</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="communitySupRad" id="communitySupTrue" value="true" data-custom-tag="community_supp"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="communitySupRad" id="communitySupFalse" value="false" checked data-custom-tag="community_supp"> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-step3" hidden>
                                     <h3>Specify which of the following platforms the tool supports</h3>
                                     <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Android</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="androidRad" id="androidTrue" value="true" checked data-custom-tag="android"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="androidRad" id="androidFalse" value="false" data-custom-tag="android"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">iOS</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="iosRad" id="iosTrue" value="true" checked data-custom-tag="ios"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="iosRad" id="iosFalse" value="false" data-custom-tag="ios"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Blackberry</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="blackberryRad" id="blackberryTrue" value="true" data-custom-tag="blackberry"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="blackberryRad" id="blackberryFalse" value="false" checked data-custom-tag="blackberry"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Windows Mobile</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="windowsMobileRad" id="windowsMobileTrue" value="true" data-custom-tag="windowsmobile"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="windowsMobileRad" id="windowsMobileFalse" value="false" checked data-custom-tag="windowsmobile"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Windows Phone</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="windowsPhoneRad" id="windowsPhoneTrue" value="true" data-custom-tag="windowsphone"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="windowsPhoneRad" id="windowsPhoneFalse" value="false" checked data-custom-tag="windowsphone"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Windows Universal Platform (WUP)</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="wupRad" id="wupTrue" value="true" data-custom-tag="wup"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="wupRad" id="wupFalse" value="false" checked data-custom-tag="wup"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Windows</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="windowsRad" id="windowsTrue" value="true" data-custom-tag="windows"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="windowsRad" id="windowsFalse" value="false" checked data-custom-tag="windows"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Mac OSX</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="osxRad" id="osxTrue" value="true" data-custom-tag="osx"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="osxRad" id="osxFalse" value="false" checked data-custom-tag="osx"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Firefox OS</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="firefoxosRad" id="firefoxosTrue" value="true" data-custom-tag="firefoxos"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="firefoxosRad" id="firefoxosFalse" value="false" checked data-custom-tag="firefoxos"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Tizen</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="tizenRad" id="tizenTrue" value="true" data-custom-tag="tizen"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="tizenRad" id="tizenFalse" value="false" checked data-custom-tag="tizen"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Symbian</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="symbianRad" id="symbianTrue" value="true" data-custom-tag="symbian"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="symbianRad" id="symbianFalse" value="false" checked data-custom-tag="symbian"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Apple TV</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="appletvRad" id="appletvTrue" value="true" data-custom-tag="appletv"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="appletvRad" id="appletvFalse" value="false" checked data-custom-tag="appletv"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Android TV</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="androidtvRad" id="androidtvTrue" value="true" data-custom-tag="androidtv"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="androidtvRad" id="androidtvFalse" value="false" checked data-custom-tag="androidtv"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Watch OS</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="watchosRad" id="watchosTrue" value="true" data-custom-tag="watchos"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="watchosRad" id="watchosFalse" value="false" checked data-custom-tag="watchos"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Bada</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="badaRad" id="badaTrue" value="true" data-custom-tag="bada"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="badaRad" id="badaFalse" value="false" checked data-custom-tag="bada"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Web OS</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="webosRad" id="webosTrue" value="true" data-custom-tag="webos"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="webosRad" id="webosFalse" value="false" checked data-custom-tag="webos"> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-step4" hidden>
                                    <h3>Specify which of the following programming languages the tool uses</h3>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindlePartially" value="partially" data-custom-tag="kindle"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleSoon" value="soon" data-custom-tag="kindle"> Soon
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindlePartially" value="partially" data-custom-tag="kindle"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleSoon" value="soon" data-custom-tag="kindle"> Soon
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindlePartially" value="partially" data-custom-tag="kindle"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleSoon" value="soon" data-custom-tag="kindle"> Soon
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindlePartially" value="partially" data-custom-tag="kindle"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleSoon" value="soon" data-custom-tag="kindle"> Soon
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindlePartially" value="partially" data-custom-tag="kindle"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleSoon" value="soon" data-custom-tag="kindle"> Soon
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindlePartially" value="partially" data-custom-tag="kindle"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleSoon" value="soon" data-custom-tag="kindle"> Soon
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindlePartially" value="partially" data-custom-tag="kindle"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleSoon" value="soon" data-custom-tag="kindle"> Soon
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindlePartially" value="partially" data-custom-tag="kindle"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleSoon" value="soon" data-custom-tag="kindle"> Soon
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group dense-input">
                                        <label class="col-xs-3 control-label">Kindle</label>
                                        <div class="col-xs-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleTrue" value="true" data-custom-tag="kindle"> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleFalse" value="false" checked data-custom-tag="kindle"> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindlePartially" value="partially" data-custom-tag="kindle"> Partially
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="kindleRad" id="kindleSoon" value="soon" data-custom-tag="kindle"> Soon
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-step5" hidden>
                                </div>
                            </form>
                            <button id="goNextStepAdd" class="btn btn-success btn-lg pull-right">Next &raquo;</button>
                            <button id="goBackStepAdd" class="btn btn-warning btn-lg pull-left">&laquo; Back</button>
                        </div>
                    </div>
                    <div id="editForm" class="panel panel-default" hidden>
                        <div class="panel-body">
                            Basic panel example Edit 
                        </div>
                    </div>
                </div>
            </div>
        </div>
 <!--    
 # framework props

    "market",
    "twitter",
    "stackoverflow",
    "appshowcase",
    "clouddev",
    "learning_curve",
    "allows_prototyping",
    "perf_overhead",
    "integrate_with_existing_app",
    "iteration_speed",
    "remoteupdate",
    "repo",
    "trial",
    "games",
    "multi_screen",

    # Development
    "publ_assist",
    "livesync",
    "code_sharing",

    #technology
    "webtonative",
    "nativejavascript",
    "runtime",
    "javascript_tool",
    "sourcecode",
    "appfactory",
    #output product
    "mobilewebsite",
    "webapp",
    "nativeapp",
    "hybridapp",
    #programming languages
    "html",
    "csharp",
    "css",
    "basic",
    "cplusplus",
    "java",
    "javame",
    "js",
    "jsx",
    "lua",
    "objc",
    "swift",
    "php",
    "python",
    "ruby",
    "actionscript",
    "MXML",
    "visualeditor",
    "xml",
    "qml",
    #additional features
    "ads",
    "cd",
    "encryption",
    "sdk",
    "widgets",
    "animations",
    #supported device features
    "accelerometer",
    "device",
    "file",
    "bluetooth",
    "camera",
    "capture",
    "geolocation",
    "gestures_multitouch",
    "compass",
    "connection",
    "contacts",
    "messages_telephone",
    "nativeevents",
    "nfc",
    "notification",
    "accessibility",
    "status",
    "storage",
    "vibration"
] -->

    </body>
</html>

