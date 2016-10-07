/*
 * Resources:
 * - http://www.sitecrafting.com/blog/jquery-caching-convention/ (best practice jquery naming convention)
 * - 
 */
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,
        maxCompared: 5,
        minCompared: 2,
        compareLabelText: "Compare",
        twitterIcon: "twitter",
        twitterSessionStoreKey: "twitterData",
        twitterSliceTerm: "twitter.com/",
        twitterPrefix:"twitter",
        // API not supported by twitter - http://stackoverflow.com/questions/17409227/follower-count-number-in-twitter
        twitterAPI: "https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=",
        githubIcon: "github",
        githubSessionStoreKey: "githubData",
        githubPrefix: "github",
        githubSliceTerm: "github.com/",
        // Un-authenticated githubAPI requests are limited to 60/hour. --> authenticated is 5000/hour requires configuration
        githubAPI: "https://api.github.com/repos/",
        stackOverflowIcon: "stack-overflow",
        stackOverflowSessionStoreKey: "stackoverflowData",
        stackOverflowPrefix: "stackoverflow",
        stackOverflowSliceTerm: "tagged/",
        maxStackOverflowRequest: 25,
        backEndBaseURL: "http://localhost/crossmos_projects/decisionTree2/publicCon/",
        backEndPrivateURL: "http://localhost/crossmos_projects/decisionTree2/privateCon/"
    }

    /* Filter-textbox element functions */
    var filterForm = {
        
        initVariables: function() {
            this.filterText = "";
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.filterField = $("input#filter");
        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();
        },

        bindEvents: function() {
            var that = this;
            this.domCache.filterField.on('input', function() {
                that.filterText = $(this).val();
                main.filterFrameworks();
            });

            this.domCache.filterField.on('focus', function() {
                $(this).select();
            });

        },

        CheckFrameworkName: function(filterText, framework) {
            var frameworkName;
            var textCompare;
            this.filterText = filterText;

            frameworkName = (($(framework).find(".thumb-caption"))[0].innerHTML).toLowerCase();    // get text from div
            textCompare = frameworkName.indexOf(filterText.toLowerCase());
            
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
        initVariables: function() {
            this.filterTerms = [];
            this.comparedItems = [];
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.checkboxes = $('[type=checkbox]');
            this.domCache.frameworks = $(".framework");
            this.domCache.filterContainer = $('.filters');
            this.domCache.msg = $("#msg");
            this.domCache.clearButton = $('.btn-clear');
            this.domCache.compareCheckboxes = $('[type=checkbox].compare-checkbox');
        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();

            filterForm.init();
            this.initTooltip();
            this.resetPage();
            frameworkPopularity.init();
        },
        // Mandatory Javascript init of bootstrap tooltip component
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },

        bindEvents: function() {
            var that = this;
            this.domCache.checkboxes.on('input change', function() {
                that.toggleClearButton();
            });

            this.domCache.compareCheckboxes.on('input change', function() {
                that.updateCompareUrl(this);
                that.determineCompareVisibility();
            });

            this.domCache.filterContainer.on('input change', function() {
                that.getCheckedFilterTerms();
                that.filterFrameworks();
            });

            this.domCache.clearButton.on('click', function() {
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

        getCheckedFilterTerms: function() {
            this.filterTerms = [];
            var that = this;

            this.domCache.checkboxes.each( function() {
                if ( $(this).is(':checked') ) {
                  that.filterTerms.push($(this).val());
                }
            });
        },

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
        // Do a combined filter of checkboxes and form search
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

            this.nothingLeft();
        },
        // update compare url
        updateCompareUrl: function(compareCheckbox) {
            var $frameworkLabel = $(compareCheckbox).siblings('.thumb-caption');
            var frameworkName = $($frameworkLabel[0]).text();
            var $compareButtons = $('.compare-link');
            var $mainCompareBtn = $('#goToCompareBtn');
            var href = CONST.backEndBaseURL + "compare?frameworks=";
            var compareIndex = 0;
            var i = 0;

            if($(compareCheckbox).is(':checked')) {
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

        // Enable and disable button
        toggleClearButton: function() {
            if( this.domCache.checkboxes.is(":checked") ) {
              this.domCache.clearButton.prop('disabled', false);
            } else {
              this.domCache.clearButton.prop('disabled', true);
            }
        },
        // Check if there are frameworks left (if not show a message)
        nothingLeft: function() {
            if ($('.framework.hid').length === this.domCache.frameworks.length) {
                this.domCache.msg.show();
            } else {
                this.domCache.msg.hide();
            }
        },
        // collapse all detail panels
        collapseAll: function() {
            var $panels = $('.caption');
            $panels.each(function() {
                $(this).find('.collapse').collapse("hide");
            });
        },
        // Back to normal state
        resetFrameworks: function() {
            this.domCache.frameworks.slideDown();
            this.domCache.frameworks.removeClass("hid");
        },
        // Reset markup of page
        resetPage: function() {
            this.domCache.checkboxes.prop("checked", false);
        }
    }

    var frameworkPopularity = {
        initVariables: function() {
            this.twitterData = {data: []};
            this.githubData = {data: []};
            this.stackOverflowData = {data: []};
        },

        cacheElements: function() {

        },
        
        init: function() {
            this.initVariables();
            this.cacheElements();
            this.loadTwitterData();
            this.loadGithubData();
            this.loadStackOverflowData();
        },

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

        loadTwitterData: function() {
            this.twitterData.data = this.loadLocalData(CONST.twitterSessionStoreKey);
            if(this.twitterData.data.length === 0) {
                this.loadRemoteData(CONST.twitterAPI, CONST.twitterIcon, CONST.twitterSliceTerm, this.twitterSuccessCallBack, this.twitterErrorCallBack);
            };
            this.updateMarkup(this.twitterData.data, CONST.twitterPrefix);
        },

        loadGithubData: function() {
            this.githubData.data = this.loadLocalData(CONST.githubSessionStoreKey);
            if(this.githubData.data.length === 0) {
                this.loadRemoteData(CONST.githubAPI, CONST.githubIcon, CONST.githubSliceTerm, this.githubSuccessCallBack, this.githubErrorCallBack);
            };
            this.updateMarkup(this.githubData.data, CONST.githubPrefix);
        },

        loadStackOverflowData: function() {
            this.stackOverflowData.data = this.loadLocalData(CONST.stackOverflowSessionStoreKey);
            if(this.stackOverflowData.data.length === 0) {
                this.loadRemoteStackOverflowData();
            };
            this.updateMarkup(this.stackOverflowData.data, CONST.stackOverflowPrefix);
        },

        loadLocalData: function( localStorageKey ) {
            var storedData = sessionData.getData(localStorageKey);
            if(storedData != null) {
                if(storedData.data.length !== 0) {
                    return storedData.data;
                }
            }
            return [];
        },

        loadRemoteStackOverflowData: function() {
            var $stackOverflowIcon = $("a .fa-" + CONST.stackOverflowIcon);
            /* trottle API variables */
            var i = 0;
            var numberOfIcons = $stackOverflowIcon.length;
            var fragments = Math.ceil(numberOfIcons / CONST.maxStackOverflowRequest);

            for(i=0; i<fragments; i++) {
                setTimeout(this.StackOverflowTimeOutCallback, (i*1000), (i), numberOfIcons, $stackOverflowIcon, this);
            }
        },

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

        makeRequest: function(url, successCallback, errorCallback) {
            $.ajax({
                url: url,
                dataType : 'jsonp',
                crossDomain : true,
                success: successCallback,
                error: errorCallback
            });
        },

        twitterErrorCallBack: function() {
            console.log("Failed to make request twitter API");
        },

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

        githubErrorCallBack: function() {
            console.log("Failed to make request github API");
        },

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

        stackOverflowErrorCallBack: function() {
            console.log("Failed to make request stackoverflow API");
        },

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

        updateMarkup: function(dataItem, prefix) {
            var i = 0;
            var $label = null;
            for(i=0; i<dataItem.length; i++) {
                $label = $("#" + prefix + "-" + dataItem[i].name.toLowerCase()).siblings("span")[0];
                $($label).text(util.formatNumber(dataItem[i].count));
            }
        }

    }

    var sessionData = {
        storeData: function( key, value ) {
            sessionStorage.setItem(key, JSON.stringify(value));
        },

        getData: function( key ) {
            var value = null;
            if (sessionStorage.getItem(key)) {
                // Restore the contents of the text field
                value = JSON.parse(sessionStorage.getItem(key));
            }
            return value;
        }

    }

    var util = {
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

    var socialLogin = {
        initVariables: function() {
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$socialSignOutBtn = $("#socialSignOut");
        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();
        },

        bindEvents: function() {
            var that = this;

            this.domCache.$socialSignOutBtn.on('click', function() {
                that.socialSignOut();
            });
        },

        socialSignOut: function() {
            // Send the code to the server
            $.ajax({
                method: 'GET',
                url: CONST.backEndPrivateURL + 'AJ_logout',
                dataType: "json",
                success: socialLogin.serverSignoutCallback
            });
        },

        serverSignoutCallback: function(response) {
            if(response.srvResponseCode === CONST.successCode) {
                // redirect to private part
                window.location = response.srvMessage;
            }
        }
    }

    $( document ).ready(function() {
        main.init();
        socialLogin.init();
    });

})(jQuery, window, document);


