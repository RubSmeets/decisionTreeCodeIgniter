/*
 * The main Javascript file of the search tool page that contains the following functionality:
 *      - Asynchronous loading and rendering of framework data from PHP (code igniter) backend
 *      - Retrieving popularity data from twitter, github and stack overflow
 *      - Storing retrieved data in HTML5 sessionStorage
 *      - Perform client-side filter of frameworks by framework name and Features
 *      - Select up to 5 frameworks for detailed comparison and navigate to detailed page
 * 
 * Resources:
 * - http://www.sitecrafting.com/blog/jquery-caching-convention/ (best practice jquery naming convention)
 * - https://codingbox.io/10-jquery-performance-tips-tricks-872f170387cc#.2m0dqkvq8 (10 tips and tricks to improve jQuery performance)
 */
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,                                 // Success-code returned from code igniter server
        maxCompared: 5,                                 // Max Number of frameworks that can be compared
        minCompared: 2,                                 // Min number of frameworks that should be selected before comparison
        compareLabelText: "Compare",                    // Compare button text
        frameworksSessionStoreKey: "frameworkData",     // HTML5-sessionStorage key for framework data
        twitterIcon: "twitter",                         // name of twitter icon
        twitterSessionStoreKey: "twitterData",          // HTML5-sessionStorage key for twitter following data
        twitterSliceTerm: "twitter.com/",               // String slice term used to split the twitter url
        twitterPrefix:"twitter",                        // Twitter link css-class prefix
        // API not supported by twitter - http://stackoverflow.com/questions/17409227/follower-count-number-in-twitter
        twitterAPI: "https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=", // Unofficial twitter API to retrieve followers
        githubIcon: "github",                           // name of github icon
        githubSessionStoreKey: "githubData",            // HTML5-sessionStorage key for github star data
        githubPrefix: "github",                         // Github link css-class prefix
        githubSliceTerm: "github.com/",                 // String slice term used to split the github url
        // Un-authenticated githubAPI requests are limited to 60/hour. --> authenticated is 5000/hour requires configuration
        githubAPI: "https://api.github.com/repos/",     // Github API to get repo star data 
        stackOverflowIcon: "stack-overflow",            // name of stack overflow icon
        stackOverflowSessionStoreKey: "stackoverflowData", // HTML5-sessionStorage key for stack overflow data
        stackOverflowPrefix: "stackoverflow",           // Stack overflow link css-class prefix
        stackOverflowSliceTerm: "tagged/",              // String slice term used to split the stack overflow url
        maxStackOverflowRequest: 25,                    // Max number of stack overflow API request per/second
        backEndBaseURL: "http://localhost/crossmos_projects/decisionTree2/publicCon/"   // Public backend base URL of code igniter backend
    }

    /* Filter-textbox element functions */
    var filterForm = {
        /* Function for initializing object variables */
        initVariables: function() {
            this.filterText = "";
            this.domCache = {};
        },
        /* jQuery caching of frequently used DOM elements */
        cacheElements: function() {
            this.domCache.filterField = $("input#filter");      // search text input field
        },
        /* Initialize text-filter functionality */
        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();
        },
        /* Bind DOM element events */
        bindEvents: function() {
            var that = this;
            this.domCache.filterField.on('input', function() {
                that.filterText = $(this).val();
                main.filterFrameworks();    // Apply filter
            });

            this.domCache.filterField.on('focus', function() {
                $(this).select();
            });

        },
        /* Function for comparing framework name with filter-text */
        CheckFrameworkName: function(filterText, framework) {
            var frameworkName;
            var textCompare;
            this.filterText = filterText;

            frameworkName = (($(framework).find(".thumb-caption"))[0].innerHTML).toLowerCase();     // get text from div
            textCompare = frameworkName.indexOf(filterText.toLowerCase());                          // check if name contains filter-text
            
            if( textCompare === -1 ) return false;
            else return true;
        },
        // Not used (maybe in future)
        clearFilterField: function() {
            this.domCache.filterField.val("");
        }
    }
    /* Mobile framework comparison tool functionality */
    var main = {
        /* Function for initializing object variables */
        initVariables: function() {
            this.amountOfFrameworks = $(".framework").length;
            this.filterTerms = [];
            this.comparedItems = [];
            this.domCache = {};
        },
        /* jQuery caching of frequently used DOM elements */
        cacheElements: function() {
            this.domCache.checkboxes = $('[type=checkbox]');
            this.domCache.frameworks = $(".framework");
            this.domCache.filterContainer = $('.filters');
            this.domCache.msg = $("#msg");
            this.domCache.clearButton = $('.btn-clear');
            this.domCache.compareCheckboxes = $('[type=checkbox].compare-checkbox');
            this.domCache.$resultAmount = $('.result-amount');
        },
        /* Initialize main functionality */
        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();

            filterForm.init();      // Call Initialize function of text filter
            this.initTooltip();     // init bootstrap tooltip
            this.resetPage();       // clear selected checkboxes
            frameworkPopularity.init(); //init the (twitter, stack overflow, github) popularity numbers
        },
        /* Mandatory Javascript init of bootstrap tooltip component */
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },
        /* Bind DOM element events */
        bindEvents: function() {
            var that = this;
            // On change state checkbox show/hide the clear selection button
            this.domCache.checkboxes.on('change', function() {
                that.toggleClearButton();
            });
            // On change state compare checkboxes determine visibility of navigation button and update url
            this.domCache.compareCheckboxes.on('change', function(e) {
                that.updateCompareUrl(this);
                that.determineCompareVisibility();
            });
            // On change filter checkboxes state, get the filter value of checkbox and apply filters
            this.domCache.filterContainer.on('change', function() {
                that.getCheckedFilterTerms();
                that.filterFrameworks();
            });
            // When clear button is pressed, clear all user input (checkboxes, input fields, compare data)
            this.domCache.clearButton.on('click', function() {
                that.domCache.$resultAmount.text("");
                that.domCache.checkboxes.each( function() {
                  if( $(this).is(':checked') ) {
                      $(this).prop("checked", false).change();  // Force change event
                  }
                });
                that.collapseAll();
            });
            // hide bootstrap Alert
            $("[data-hide]").on("click", function(){
                $(this).closest("." + $(this).attr("data-hide")).hide();
            });
        },
        /* Retrieve and store filter term from selected checkbox in filterTerms array */
        getCheckedFilterTerms: function() {
            this.filterTerms = [];
            var that = this;

            this.domCache.checkboxes.each( function() {
                if ( $(this).is(':checked') ) {
                  that.filterTerms.push($(this).val());
                }
            });
        },
        /* Apply CSS 'selected' class to feature spans that contain the checkbox filter term */
        CheckFrameworkFeature: function(filterTerms, framework) {
            var foundFeatures = 0;
            var i = 0;
            var $feature;

            for(i=0; i<filterTerms.length; i++) {
                $feature = $(framework).find('.' + filterTerms[i]);
                if ( $feature.length === 1 ) {
                  foundFeatures++;
                  $feature.addClass('selected');
                }
            }

            if (filterTerms.length === foundFeatures) return true;
            else return false;
        },
        /* Do a combined filter of checkboxes and form search */
        filterFrameworks: function() {
            var that = this;
            // Clear selected class
            $('.selected').removeClass('selected');

            this.domCache.frameworks.each( function() {
                if( that.CheckFrameworkFeature(that.filterTerms, this) && filterForm.CheckFrameworkName(filterForm.filterText, this) ) {
                    $(this).slideDown();
                    $(this).removeClass("hid");
                } else {
                    $(this).slideUp();
                    $(this).addClass("hid");
                }
            });

            this.updateResultAmountTag();
            this.nothingLeft();
        },
        /* update compare url to include the selected framework */
        updateCompareUrl: function(compareCheckbox) {
            var $frameworkLabel = $(compareCheckbox).siblings('.thumb-caption');
            var frameworkName = $($frameworkLabel[0]).text();
            var $compareButtons = $('.compare-link');
            var $mainCompareBtn = $('#goToCompareBtn');
            var href = CONST.backEndBaseURL + "compare?frameworks=";
            var compareIndex = 0;
            var i = 0;

            if($(compareCheckbox).is(':checked')) {
                // check if there are more than the MAX amount of allowed frameworks compared
                if(this.comparedItems.length === (CONST.maxCompared)) {
                    $("body").overhang({
                        type: "error",
                        message: "You can only select up to 5 frameworks for comparison.",
                        closeConfirm: "true"
                    });
                    $(compareCheckbox).prop('checked', false);  // clear checkboxe
                    return; // do not update url or push framework
                }
                this.comparedItems.push(frameworkName);
            } else {
                //remove element
                compareIndex = this.comparedItems.indexOf(frameworkName);
                this.comparedItems.splice(compareIndex, 1);
            }

            // Update href value of link button
            for(i=0; i<this.comparedItems.length; i++) {
                href += this.comparedItems[i] + ";"
            }
            // remove last ';' from href
            href = href.slice(0, -1);
            $compareButtons.prop('href', href);
            $mainCompareBtn.prop('href', href);
        },
        /* Function that hides/shows the compare button when the compare checkboxes are changed */
        determineCompareVisibility: function() {
            var $compareButtons = $('.compare-link');
            var $checkboxCompareLabels = $(".compare-label");
            var $compareCheckbox = null;
            var $checkboxLabel = null;

            if(this.comparedItems.length > (CONST.maxCompared)) return; // do nothing

            if(this.comparedItems.length > (CONST.minCompared-1)) {
                $compareButtons.each(function () {
                    $compareCheckbox = $(this).siblings(':input');
                    $checkboxLabel = $(this).siblings('label');
                    if($compareCheckbox.is(":checked")) {
                        $checkboxLabel.text("");
                        $(this).removeClass("hidden");
                    } else {
                        $(this).addClass("hidden");
                        $checkboxLabel.text(CONST.compareLabelText);
                    }
                });
                return; // go Back
            }
            // every other situation hide button
            $compareButtons.addClass("hidden");
            $checkboxCompareLabels.text(CONST.compareLabelText);  // Could be done better...
        },
        /* Enable and disable clear button */
        toggleClearButton: function() {
            if( this.domCache.checkboxes.is(":checked") ) {
              this.domCache.clearButton.prop('disabled', false);
            } else {
              this.domCache.clearButton.prop('disabled', true);
            }
        },
        /* Show the user how many results are shown after filtering (bootstrap button tag) */
        updateResultAmountTag: function() {
            var visible = this.amountOfFrameworks - $('.framework.hid').length;
            if(visible !== this.amountOfFrameworks) {
                this.domCache.$resultAmount.text(visible + "/" + this.amountOfFrameworks);
            } else {
                this.domCache.$resultAmount.text("");
            }
        },
        /* Check if there are frameworks left (if not show a message) */
        nothingLeft: function() {
            if ($('.framework.hid').length === this.domCache.frameworks.length) {
                this.domCache.msg.show();
            } else {
                this.domCache.msg.hide();
            }
        },
        /* collapse all detail panels */
        collapseAll: function() {
            var $panels = $('.caption');
            $panels.each(function() {
                $(this).find('.collapse').collapse("hide");
            });
        },
        /* Back to normal state */
        resetFrameworks: function() {
            this.domCache.frameworks.slideDown();
            this.domCache.frameworks.removeClass("hid");
        },
        /* Reset markup of checkboxes and ... */
        resetPage: function() {
            this.domCache.checkboxes.prop("checked", false);
        }
    }

    /* 
     * Object containing popularity functionality. Retrieves and sets popularity data from
     * Github, stack overflow, twitter APIs and updates the view with these values.
     * To preserve bandwidth and limit the amount of requests we store the results in
     * HTML5 sessionStorage. (some APIs block request for a fixed period of time after
     * the request amount is exceeded)
     */
    var frameworkPopularity = {
        /* Function for initializing object variables */
        initVariables: function() {
            this.twitterData = {data: []};
            this.githubData = {data: []};
            this.stackOverflowData = {data: []};
        },
        /* jQuery caching of frequently used DOM elements */
        cacheElements: function() {
        },
        /* Initialize frameworkPopularity functionality */
        init: function() {
            this.initVariables();
            this.cacheElements();
            this.loadTwitterData();
            this.loadGithubData();
            this.loadStackOverflowData();
        },
        /* Function that prepares data for Ajax request to an external API */
        loadRemoteData: function(api, iconName, sliceTerm, successCallback, errorCallback) {
            var $icon = $("a .fa-" + iconName);
            var $link = null;
            var remoteAddress = "";
            var requestUrl = "";
            var strippedRemoteAddress= [];
            var that = this;
            $icon.each(function () {
                $link = $(this).parent();
                remoteAddress = $link.prop("href");
                strippedRemoteAddress = remoteAddress.split(sliceTerm);
                requestUrl = api + strippedRemoteAddress[1];
                that.makeRequest(requestUrl, successCallback, errorCallback);
            });
        },
        /* Load twitter data from sessionStorage or remote API */
        loadTwitterData: function() {
            this.twitterData.data = this.loadLocalData(CONST.twitterSessionStoreKey);
            if(this.twitterData.data.length === 0) {
                this.loadRemoteData(CONST.twitterAPI, CONST.twitterIcon, CONST.twitterSliceTerm, this.twitterSuccessCallBack, this.twitterErrorCallBack);
            };
            this.updateMarkup(this.twitterData.data, CONST.twitterPrefix);
        },
        /* Load github data from sessionStorage or remote API */
        loadGithubData: function() {
            this.githubData.data = this.loadLocalData(CONST.githubSessionStoreKey);
            if(this.githubData.data.length === 0) {
                this.loadRemoteData(CONST.githubAPI, CONST.githubIcon, CONST.githubSliceTerm, this.githubSuccessCallBack, this.githubErrorCallBack);
            };
            this.updateMarkup(this.githubData.data, CONST.githubPrefix);
        },
        /* Load stack overflow data from sessionStorage or remote API */
        loadStackOverflowData: function() {
            this.stackOverflowData.data = this.loadLocalData(CONST.stackOverflowSessionStoreKey);
            if(this.stackOverflowData.data.length === 0) {
                this.loadRemoteStackOverflowData();
            };
            this.updateMarkup(this.stackOverflowData.data, CONST.stackOverflowPrefix);
        },
        /* Load data from sessionStorage that is identified by the localStorageKey */
        loadLocalData: function( localStorageKey ) {
            var storedData = sessionData.getData(localStorageKey);
            if(storedData != null) {
                if(storedData.data.length !== 0) {
                    return storedData.data;
                }
            }
            return [];
        },
        /* Load Stack overflow data from remote */
        loadRemoteStackOverflowData: function() {
            var $stackOverflowIcon = $("a .fa-" + CONST.stackOverflowIcon);
            /* trottle API requests of stack overflow (limited to 25/sec) */
            var i = 0;
            var numberOfIcons = $stackOverflowIcon.length;
            var fragments = Math.ceil(numberOfIcons / CONST.maxStackOverflowRequest);

            for(i=0; i<fragments; i++) {
                setTimeout(this.StackOverflowTimeOutCallback, (i*1000), (i), numberOfIcons, $stackOverflowIcon, this);
            }
        },
        /* Prepare stack overflow Ajax request data */
        StackOverflowTimeOutCallback: function(i, numberOfIcons, $icons, that) {
            var j = 0;
            var ceiling = 0
            var $stackOverflowLink = null;
            var stackOverflowAddress = "";
            var url = ""
            var strippedstackOverflowUrl= [];
            
            if((CONST.maxStackOverflowRequest*(i+1)) >= numberOfIcons) ceiling = numberOfIcons;
            else ceiling = CONST.maxStackOverflowRequest*(i+1);

            for(j=(CONST.maxStackOverflowRequest*i); j<ceiling; j++) {
                $stackOverflowLink = $($icons[j]).parent();
                stackOverflowAddress = $stackOverflowLink.prop("href");
                strippedstackOverflowUrl = stackOverflowAddress.split(CONST.stackOverflowSliceTerm);
                url = "https://api.stackexchange.com/2.2/tags/" + strippedstackOverflowUrl[1] + "/info?order=desc&sort=popular&site=stackoverflow";
                // API https://api.stackexchange.com/2.2/tags/{tags}/info?order=desc&sort=popular&site=stackoverflow limited to 30 request/sec and 300/day
                that.makeRequest(url, that.stackOverflowSuccessCallBack, that.stackOverflowErrorCallBack);
            }
        },
        /* Perform cross-domain Ajax request to specified URL */
        makeRequest: function(url, successCallback, errorCallback) {
            $.ajax({
                url: url,
                dataType : 'jsonp',
                crossDomain : true,
                success: successCallback,
                error: errorCallback
            });
        },
        /* Twitter Ajax request error callback */
        twitterErrorCallBack: function() {
            console.log("Failed to make request twitter API");
        },
        /* Twitter Ajax success callback. Stores data in sessionStorage and updates view */
        twitterSuccessCallBack: function(data) {
            if(data[0].hasOwnProperty("name")) {
                var name = data[0].screen_name;
                var followers = data[0].followers_count;
                var data = [{"name": name, "count": followers}];
                frameworkPopularity.twitterData.data.push({"name": name, "count": followers});
                sessionData.storeData(CONST.twitterSessionStoreKey, frameworkPopularity.twitterData);
                frameworkPopularity.updateMarkup(data, CONST.twitterPrefix);
            }
        },
        /* Github Ajax request error callback */
        githubErrorCallBack: function() {
            console.log("Failed to make request github API");
        },
        /* Github Ajax success callback. Stores data in sessionStorage and updates view */
        githubSuccessCallBack: function(data) {
            if(data.data.owner.hasOwnProperty("login")) {
                var name = data.data.owner["login"];
                var stars = data.data["stargazers_count"];
                var data = [{"name": name, "count": stars}];
                frameworkPopularity.githubData.data.push({"name": name, "count": stars});
                sessionData.storeData(CONST.githubSessionStoreKey, frameworkPopularity.githubData);
                frameworkPopularity.updateMarkup(data, CONST.githubPrefix);
            }
        },
        /* Stack overflow Ajax request error callback */
        stackOverflowErrorCallBack: function() {
            console.log("Failed to make request stackoverflow API");
        },
        /* Stack overflow Ajax success callback. Stores data in sessionStorage and updates view */
        stackOverflowSuccessCallBack: function(data) {
            if(data.items[0].hasOwnProperty("name")) {
                var strippedName = [];
                var name = data.items[0]["name"];
                strippedName = name.split(".");
                var count = data.items[0]["count"];
                var data = [{"name": strippedName[0], "count": count}];
                frameworkPopularity.stackOverflowData.data.push({"name": strippedName[0], "count": count});
                sessionData.storeData(CONST.stackOverflowSessionStoreKey, frameworkPopularity.stackOverflowData);
                frameworkPopularity.updateMarkup(data, CONST.stackOverflowPrefix);
            }
        },
        /* Perform actual DOM update of popularity data */
        updateMarkup: function(dataItem, prefix) {
            var i = 0;
            var $label = null;
            for(i=0; i<dataItem.length; i++) {
                $label = $("#" + prefix + "-" + dataItem[i].name.toLowerCase()).siblings("span")[0];
                $($label).text(util.formatNumber(dataItem[i].count));
            }
        }

    }
    /* Functions for interacting with HTML5 sessionStorage */
    var sessionData = {
        // Store data
        storeData: function( key, value ) {
            sessionStorage.setItem(key, JSON.stringify(value));
        },
        // retrieve data
        getData: function( key ) {
            var value = null;
            if (sessionStorage.getItem(key)) {
                // Restore the contents of the text field
                value = JSON.parse(sessionStorage.getItem(key));
            }
            return value;
        }
    }
    /* Utility functions used for formating data */
    var util = {
        // Number below 10000 are displayed normally while larger numbers are rounded and appended with 'K'
        formatNumber: function( nr ) {
            var formattedNr = "";
            if( nr > 9999 ) {
                nr = (nr / 1000).toFixed(1);
                formattedNr = nr.toLocaleString('en-US') + "K";
            } else if( nr > 999 ) {
                formattedNr = nr.toLocaleString('en-US');
            } else {
                formattedNr = nr.toString();
            }
            return formattedNr;
        }
    }
    /* 
     * Functions for loading framework data from code-igniter backend.
     * Data is stored in sessionStorage to save bandwidth on navigation between pages
     * DOM is updated with framework data (thumbnails) of each framework entry
     */
    var content = {
        /* Function for initializing object variables */
        initVariables: function() {
            this.frameworkData = {};
        },
        /* Initialize the framework contents */
        init: function() {
            this.initVariables();
            this.loadFrameworkData();
        },
        /* Load data on page ready from either sessionStorage or backend */
        loadFrameworkData: function() {
            this.frameworkData = this.loadLocalData(CONST.frameworksSessionStoreKey);
            if(this.frameworkData.length === 0) {
                this.loadRemoteData();
            };
            this.updateMarkup(this.frameworkData);
        },
        /* Perform load from sessionStorage */
        loadLocalData: function(sessionKey) {
            var storedData = sessionData.getData(sessionKey);
            if(storedData != null) {
                if(storedData.length !== 0) {
                    return storedData;
                }
            }
            return [];
        },
        /* Perform AJAX request to code-igniter backend */
        loadRemoteData: function() {
            $.ajax({
                url: CONST.backEndBaseURL + "AJ_getFrameworks",
                dataType : 'json',
                success: this.successCallback,
                error: this.errorCallback
            });
        },
        /* AJAX request success callback handler */
        successCallback: function(response) {
            //update markup and initialize main controller
            content.updateMarkup(response);

            //store result in sessionStorage
            sessionData.storeData(CONST.frameworksSessionStoreKey, response);
        },
        /* 
         * Function that creates thumbnails from received frameworkData 
         * Data is preformatted by backend to improve performance
         * TODO: should use a JS-templating library to improve readability (handlebars JS)
         */
        updateMarkup: function(data) {
            var $container = $('.col-sm-9');
            var collapseOffset = $('.filter-box').length + 1;
            var thumb = "";
            var startLink = "";
            var endLink = "";
            var twitter = "";
            var stackoverflow = "";
            var repo = "";

            // Update number of tracked tools
            var formatAmount = content.formatNumber(data.length, 4);
            for(var i=3; i>=0; i--) {
                $('#nr' + i).text(formatAmount.substr(3-i, 1));
            }

            // Insert content
            data.forEach(function(element, index) {
                twitter = '<div class="col-xs-6 centered left"><i class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">n/a</span></div>';
                stackoverflow = '<div class="col-xs-6 centered left"><i class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">n/a</span></div>';
                repo = '<div class="col-xs-6 centered right"><i class="fa fa-github" aria-hidden="true"></i><span class="github-label">n/a</span></div>';
                if(element.url !== "") {
                    startLink = '<a href="' + element.url + '" target="_blank">';
                    endLink = '</a>';
                } else {
                    startLink = "";
                    endLink = "";
                }
                if(element.twitter !== "") twitter = '<div class="col-xs-6 centered left"><a href="' +  element.twitter + '" target="_blank"><i id="twitter-' + element.twitterName + '" class="fa fa-twitter" aria-hidden="true"></i><span class="twitter-label">0000</span></a></div>';
                if(element.stackoverflow !== "") stackoverflow = '<div class="col-xs-6 centered left"><a href="' + element.stackoverflow + '" target="_blank"><i id="stackoverflow-' + element.stackoverflowName + '" class="fa fa-stack-overflow" aria-hidden="true"></i><span class="stackoverflow-label">0000</span></a></div>';
                if(element.repo !== "") repo = '<div class="col-xs-6 centered right"><a href="' + element.repo + '" target="_blank"><i id="github-' + element.repoName + '" class="fa fa-github" aria-hidden="true"></i><span class="github-label">0000</span></a></div>';

                thumb = '<div class="col-md-4 col-xs-6 framework">' +
                            '<div class="thumbnail">' +
                                startLink + '<img src="' + element.logo_name + '" alt="">' + endLink +
                                '<div class="caption">' + 
                                    '<h4 class="thumb-caption">' + element.framework + '</h4>' +
                                    element.technology +
                                    element.status +
                                    '<div class="row">' +
                                        twitter +
                                        repo +
                                    '</div>' +
                                    '<div class="row">' +
                                        stackoverflow +
                                    '</div>' +
                                    '<input id="compare-' + element.formattedFramework + '" type="checkbox" value="compare" class="custom-checkbox compare-checkbox"/>' +
                                    '<label for="compare-' + element.formattedFramework + '" class="checkbox-label compare-label">Compare</label>' +
                                    '<a class="btn btn-danger btn-xs compare-link hidden" href="html/compare.html" role="button">Go to compare</a>' +
                                    '<div>' +
                                        '<h4 class="panel-title">' +
                                            '<a data-toggle="collapse" aria-expanded="false" href="#collapse' + (index+collapseOffset) + '">' +
                                                '<div class="panel-heading feature-panel">' +
                                                    '<span class="dropIcon glyphicon glyphicon-chevron-up"></span>' +
                                                    '<span class="dropIcon glyphicon glyphicon-chevron-down"></span>' +
                                                '</div>' +
                                            '</a>' +
                                        '</h4>' +
                                        '<div id="collapse' + (index+collapseOffset) + '" class="panel-collapse collapse">' +
                                            '<div>' +
                                                '<div class="row">' +
                                                    '<div class="col-xs-6">' +
                                                        '<h4 class="featureTitle">Platform</h4>' +
                                                        element.platforms +
                                                    '</div>' +
                                                    '<div class="col-xs-6">' +
                                                        '<h4 class="featureTitle">Target</h4>' +
                                                        element.output +
                                                    '</div>' +
                                                '</div>' +
                                                '<div class="row">' +
                                                    '<div class="col-xs-6">' +
                                                        '<h4 class="featureTitle">Development Language</h4>' +
                                                        element.languages +
                                                    '</div>' +
                                                    '<div class="col-xs-6">' +
                                                        '<h4 class="featureTitle">Terms of a License</h4>' +
                                                        element.licenses +
                                                    '</div>' +
                                                '</div>' +
                                                '<div class="row">' +
                                                    '<div class="col-xs-12">' +
                                                        '<h4 class="featureTitle">Hardware Features</h4>' +
                                                        element.hardware
                                                    '</div>' +    
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>';                         

                $container.append(thumb);
            });
            // After DOM is updated we can load the main filter functionality
            main.init();
        },
        /* Function that formats number to string containing 4 digits (e.g. 20 --> "0020")  */
        formatNumber: function(num, size) {
            var s = "0000" + num;
            return s.substr(s.length-size);
        },
        /* AJAX error callback */
        errorCallback: function(e) {
            console.log("initial loading failed");
        }
    }
    /* After document is ready we can start loading the content from code-igniter backend */
    $( document ).ready(function() {
        // request frameworks from server or sessionStorage
        content.init();
    });

})(jQuery, window, document);


