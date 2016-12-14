/**
 * The compare frameworks page allows a user to have a side-by-side detailed comparison of each framework features.
 * The page loads all the necessary framework data and markup from the PHP backend using an AJAX request. The frameworks
 * that are selected for comparison are either provided on page navigation (added to URL) or by selecting from a
 * jQuery datatable containing a list of available frameworks.
 * 
 * Resources:
 *  - https://datatables.net/
 */
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,         // Success-code returned from code igniter server
        maxNumbComparisons: 5,  // the max number of frameworks to be compared
        add: 0,                 // constant that defines when a framework should be added to comparison
        remove: 1,              // constant that defines when a framework should be removed from comparison
        backEndBaseURL: "http://localhost/crossmos_projects/decisionTree2/publicCon/",
        backEndPrivateURL: "http://localhost/crossmos_projects/decisionTree2/privateCon/"
    }
    /* Datatable functionality for displaying available frameworks to compare */
    var DataTable = {
        /* initialize variables */
        initVariables: function() {
            this.frameworkTable = null;
        },
        /* Cache frequently used DOM elements */
        cacheElements: function() {
            this.$modalContainer = $("#addFrameworkModal");
            this.$frameworkTable = $("#addFrameworksTable");
            this.$filterFieldContainer = $('#addFrameworksTable_filter');
            this.$filterField = $('#addFrameworksTable_filter').find(':input');
        },
        /* initialize the datatable and cleanMarkup */
        init: function($frameworkTable) {
            this.initVariables();
            this.initTable($frameworkTable);
            this.cacheElements();
            this.cleanMarkup();
            this.bindEvents();
            this.hideCurrentFrameworks(CF.currentFrameworks);
        },
        /* Datatable initialization */
        initTable: function($frameworkTable) {
            this.frameworkTable = $frameworkTable.DataTable({
                // specifies where datatable should retrieve data
                ajax: {
                    url: CONST.backEndBaseURL + 'AJ_getThumbFrameworks',
                    dataSrc: "frameworks"
                },
                // provide column configuration
                "columnDefs": [
                    { "visible": false, "targets": 1 }  // hide second column
                ],
                // specifiy which columns there are and what data should be displayed and HOW it should be displayed
                columns: [
                    // First column (contains thumbnail)
                    {
                        data: 'framework',
                        "type": "html",
                        render: function (data, type, row) {
                            if (type ==='display') {
                                var thumbs = "";
                                thumbs = '<span class="thumb-framework"><img src="' + row.thumb_img + '" alt=""/></span> \
							              <span class="glyphicon glyphicon-plus pull-right thumb-add"></span> \
							              <span class="thumb-title">' + data + '</span> \
							              <span class="thumb-status ' + row.status.toLowerCase() + '">' + row.status + '</span>';
                                return thumbs;
                            } else return '';
                        }
                    },
                    // Second column (invisible)
                    {data:'framework'}  //Must provide second column in order for search to work...
                ],
                // Overwrite default configurations of datatable
                language: {
                    search: "<i class='glyphicon glyphicon-search modal-search-feedback'></i>", // search input markup
                    searchPlaceholder: "Search by framework name...",
                    zeroRecords: "No Frameworks found. Please try another search term." // text displayed when no records match search term
                },
                // Overwrite default markup of datatable
                "sAutoWidth": false,
                "iDisplayStart ": 6,
                "iDisplayLength": 6,
                "lengthChange": false,
                "bInfo": false, // hide showing entries
            });
        },
        /* After initialization we manually overwrite some markup of datatable */
        cleanMarkup: function() {
            this.$filterFieldContainer.removeClass('dataTables_filter');
            this.$filterFieldContainer.find("input").addClass("modal-search");
            this.$frameworkTable.addClass("table table-hover"); //add bootstrap class
            this.$frameworkTable.css("width","100%");
        },
        /* Bind events to DOM elements */
        bindEvents: function() {
            var that = this;
            this.$modalContainer.on('shown.bs.modal', function (){
                // On modal overlay shown focus the search field
                that.$filterField.focus();
            });
            // Handle when a row is clicked in Datatable
            this.$frameworkTable.find('tbody').on('click', 'tr', function () {
                var data = that.frameworkTable.row( this ).data();
                if(typeof data !== 'undefined') {
                    CF.sendRequest(data.framework);
                    that.$modalContainer.modal('hide');
                }
            });
            // Select current input when search field gains focus
            this.$filterField.on('focus', function() {
                $(this).select();
            });
        },
        /* Performs a selective filter that hides the currently compared frameworks from the datatable list of available frameworks */
        hideCurrentFrameworks: function(currentFrameworks) {
            var i=0;
            var regexNames = "";
            if(currentFrameworks.length !== 0) {
                for(i=0; i<currentFrameworks.length; i++) {
                    regexNames += currentFrameworks[i] + "|";
                }
                regexNames = regexNames.slice(0, -1);    //remove last "|"

                this.frameworkTable
                    .columns(1) //The index of column to search
                    .search('^(?:(?!(' + regexNames + ')).)*$\r?\n?', true, false) //The RegExp search all string that not cointains values
                    .draw();    //Force a redraw of table
            } else {
                this.frameworkTable
                    .search( '' )   // Clear all searches
                    .columns().search( '' )
                    .draw();    //Force redraw of table
            }
        }
    }

    /* Compare framework page main functions */
    var CF = {
        /* initialize variables */
        initVariables: function() {
            this.columnTransform = [4,4,4,3,15,2]; // first 3 are 3 column layout and then 4,5,6 column
            this.currentFrameworks = [];
            this.frameworkOrder = ["","","","","",""];
        },
        /* Cache frequently used DOM elements */
        cacheElements: function() {
            this.$frameworkTable = $('#addFrameworksTable');
            this.$frameworkHeaderContainer = $('#frameworkheader-container');
            this.$frameworkDevSpecContainer = $('#framework-dev-spec-container');
            this.$frameworkHardwareFeatContainer = $('#framework-hardware-feature-container');
            this.$frameworkSupportFeatContainer = $('#framework-support-feature-container');
            this.$frameworkResourcesContainer = $('#framework-resources-container');
            //Sub containers from tool specification
            this.$toolTecCon = $('#toolTecCon');
            this.$toolAnnCon = $('#toolAnnCon');
            this.$toolVerCon = $('#toolVerCon');
            this.$toolPlaCon = $('#toolPlaCon');
            this.$toolLanCon = $('#toolLanCon');
            this.$toolProCon = $('#toolProCon');
            this.$toolLicCon = $('#toolLicCon');
            this.$toolSrcCon = $('#toolSrcCon');
            this.$toolCostCon = $('#toolCostCon');

            this.$addFrameworkButton = $('.add-framework');
            this.$msg = $('#msg');
            this.$collapseButton = $('#btnCollapseAll');
        },
        /* initialize the main functionality of compare page */
        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();
            this.initTooltip();
            //this.setAffix();  // TODO: make the header appear fixed when scrolling down

            this.loadComparisonData();  // Load framework data of frameworks listed in URL
            this.nothingLeft();         // Determine if there are currently any comparisons being performed and show/hide headers and message
            this.figOutAddButton();     // Determine if the add framework button should work or not

            DataTable.init(this.$frameworkTable);

            console.log( "all init and Bindings complete!" );
        },
        /* Bind DOM element events */
        bindEvents: function() {
            // Toogle collapse button text and collapse of panels when clicked
            this.$collapseButton.on('click', function() {
                var panels = $(this).siblings('.panel-group');
                var btnLabel = $(this).children('.btn-label');
                var option = ""
                if($(btnLabel).text() === "Collapse All") {
                    $(btnLabel).text("Open All");
                    option = "hide";
                } else {
                    $(btnLabel).text("Collapse All");
                    option = "show";
                }
                $(panels).each(function() {
                    $(this).find('.collapse').collapse(option);
                });
            });
        },
        /* Mandatory Javascript init of bootstrap tooltip component */
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },
        /**
         * setAffix on header
         * TODO: currently not working properly
         */
        setAffix: function() {
            // Offset for the compare header
            $('#compare-header').affix({            
                offset: {
                    top: 360
                }
            });
        },
        /* Load comparison data from PHP backend */
        loadComparisonData: function() {
            var frameworks = this.getUrlParams();
            var i = 0;
            for(i=0; i<frameworks.length; i++) {
                this.sendRequest(frameworks[i]);
            }
        },
        /*
         * 0 for add and 1 for removal (see CONST)
         * !NOTE: frameworkOrder is needed to ensure that the background
         * color is always unique. We store the position of the framework
         * in an array. 
         */
        addRemoveShownFrameworks: function(frameworkName, addRemove) {
            var compareIndex = 0;
            if(addRemove) {
                //remove element from stored frameworks and free spot
                compareIndex = this.currentFrameworks.indexOf(frameworkName);
                this.currentFrameworks.splice(compareIndex, 1);
                compareIndex = this.frameworkOrder.indexOf(frameworkName);
                this.frameworkOrder[compareIndex] = "";
            } else {
                //add element to stored frameworks and find/fill free spot
                this.currentFrameworks.push(frameworkName);
                compareIndex = this.frameworkOrder.indexOf("");
                this.frameworkOrder[compareIndex] = frameworkName;
            }
            this.figOutAddButton();
            this.nothingLeft();
            DataTable.hideCurrentFrameworks(this.currentFrameworks);
            this.updateUrlParams();
        },
        /* Perform AJAX request */
        sendRequest: function(data) {
            $.ajax({
                method: "GET",
                url: CONST.backEndBaseURL + "AJ_getFramework",
                dataType: "json",
                data: {keyword: data},

                error: this.errorCallback,
                success: this.succesCallback
            });
        },
        /* The error callback of the AJAX request */
        errorCallback: function(jqXHR, status, errorThrown) {
            console.log("Something went wrong with request");
        },
        /* The success callback after AJAX request */
        succesCallback: function(data, status, jqXHR) {
            console.log("Successful request");
            CF.addRemoveShownFrameworks(data.framework, CONST.add);
            CF.addMarkupToPage(data);
            CF.bindEventNewItem();
        },
        /* Change bootstrap column widths depending on amount of shown frameworks in comparison (see columnTransform matrix) */
        recalculateColumns: function() {
            var $columns = $('.col-md-9 > div');
            var columnWidth = columnWidth = 'col-md-' + this.columnTransform[this.currentFrameworks.length-1];
            $columns.each(function(index) {
                $(this).alterClass('col-md-*', columnWidth);
            });
            return columnWidth;
        },
        /* Function that toggles between enable/disable of the addFramework button. Should be disabled once there are 5 frameworks visible in comparison */
        figOutAddButton: function() {
            if(this.currentFrameworks.length > (CONST.maxNumbComparisons-1)) {
                this.$addFrameworkButton.addClass('disabled');
                this.$addFrameworkButton.prop('disabled', true);
            } else {
                this.$addFrameworkButton.removeClass('disabled');
                this.$addFrameworkButton.removeProp('disabled');
            }
        },
        /* Update the URL parameters to provide feedback and history support on page navigation and page refresh. The frameworks that are specified in the URL are loaded and shown in comparison */
        updateUrlParams: function() {
            // get current url
            var i = 0;
            var newUrl = window.location.href;
            if(newUrl.indexOf('?frameworks') === -1) {
                newUrl += '?frameworks='
            } else {
                if(newUrl.indexOf('=') === -1) newUrl += '=';
                newUrl = newUrl.substring(0, (newUrl.indexOf('=') + 1));
            }
            // add currentFrameworks to url
            for(i=0; i<this.currentFrameworks.length; i++) {
                newUrl += this.currentFrameworks[i] + ";"
            }
            // remove last ';' from newUrl
            newUrl = newUrl.slice(0, -1);
            if (history.pushState) {
                window.history.pushState({path:newUrl},'',newUrl);
            }
        },
        /**
         * BAD!!! The AJAX request returns pre-formatted data and markup that can be directly added to a container in HTML. However
         * the first table contains dynamic content that is not correctly displayed in spans. A table should be used instead to
         * deal with the misalignment of content. For now this is solved by applying the flex-item class to each div and span.
         * 
         * Furthermore: using HTML in JS is not good practice. Here we could use a templating library like (handlebars JS)
         * resources
         *  - http://handlebarsjs.com/
         */
        addMarkupToPage: function(data) {
            var frameworkPos = this.frameworkOrder.indexOf(data.framework);
            var columnWidth = this.recalculateColumns();
            var frameworkClass = data.framework.replace(/[^a-zA-Z0-9]/g, "")

            var contents = '<div class="' + columnWidth + ' header-container head' + (frameworkPos+1) + '">' + data.header +	'</div>';
            this.$frameworkHeaderContainer.append(contents);
            // Add tool specification markup
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + ' flex-item">' + data.tool_specification.toolTecCon + '</div>';
            this.$toolTecCon.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + ' flex-item">' + data.tool_specification.toolAnnCon + '</div>';
            this.$toolAnnCon.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + ' flex-item">' + data.tool_specification.toolVerCon + '</div>';
            this.$toolVerCon.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + ' flex-item">' + data.tool_specification.toolPlaCon + '</div>';
            this.$toolPlaCon.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + ' flex-item">' + data.tool_specification.toolLanCon + '</div>';
            this.$toolLanCon.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + ' flex-item">' + data.tool_specification.toolProCon + '</div>';
            this.$toolProCon.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + ' flex-item">' + data.tool_specification.toolLicCon + '</div>';
            this.$toolLicCon.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + ' flex-item">' + data.tool_specification.toolSrcCon + '</div>';
            this.$toolSrcCon.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + ' flex-item">' + data.tool_specification.toolCostCon + '</div>';
            this.$toolCostCon.append(contents);
            // -----------------------------
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + '">' + data.dev_specification + '</div>';
            this.$frameworkDevSpecContainer.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + '">' + data.hardware_features + '</div>';
            this.$frameworkHardwareFeatContainer.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + '">' + data.support_features + '</div>';
            this.$frameworkSupportFeatContainer.append(contents);
            contents = '<div class="' + columnWidth + ' no-padding centered body' + (frameworkPos+1) + ' ' + frameworkClass + '">' + data.resources + '</div>';
            this.$frameworkResourcesContainer.append(contents);
        },
        /**
         * After a framework is added to comparison we can choose to remove it from the comparison-view. In order to do this
         * we need to attach a click handler to a glyphicon element in the newly added header. This function takes care of
         * adding the handler.
         */
        bindEventNewItem: function() {
            var that = this;
            // First remove all click handlers and then re-attach to include new ones
            $('.glyphicon-remove-circle').off('click')
            $('.glyphicon-remove-circle').on('click', function() {
                var parentHeaderContainer = $(this).parents('.header-container');
                var frameworkName = $(parentHeaderContainer).find('h4').text();
                var frameworkClass = frameworkName.replace(/[^a-zA-Z0-9]/g, "")
                var $parentBodyContainers = $('.' + frameworkClass);
                that.addRemoveShownFrameworks(frameworkName, CONST.remove);
                
                $(parentHeaderContainer).remove();
                $parentBodyContainers.remove();

                that.recalculateColumns();
            });
        },
        /* Function for retrieving the framework names from the URL parameter */
        getUrlParams: function () {
            var i = 0
            var vars = [], hash = [];
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                if(hash[0] === "frameworks" && hash[1]) {
                    vars = hash[1].split(';');
                }
            }
            //replace %20 with " "
            for(i=0; i < vars.length; i++) {
                vars[i] = vars[i].replace(/%20/g, " ");
            }
            return vars;
        },
        /* Hide all feature panels */
        hidePanels: function() {
            $('.compare-body').hide();
        },
        /* Show all feature panels */
        showPanels: function() {
            $('.compare-body').show();
        },
        // Check if there are frameworks being compared (if not show a message) and hide all feature panels
        nothingLeft: function() {
            if (this.currentFrameworks.length === 0) {
                this.hidePanels();
                this.$msg.show();
            } else {
                this.showPanels();
                this.$msg.hide();
            }
        }

    }
    /**
     * Wait for the document ready event before initializing the main functionality
     */
    $( document ).ready(function() {
        console.log( "Document ready!" );
        CF.init();
    });
    /**
     * Reload the page on navigation
     */
    window.addEventListener("popstate", function(e) {
        window.location.reload();
    });

    /* 
     * HELPER Function for wildcard class removal with jQuery 
     *
     * https://gist.github.com/peteboere/1517285
     * LICENSE: MIT-license 
     */
    $.fn.alterClass = function ( removals, additions ) {  
        var self = this;
        if ( removals.indexOf( '*' ) === -1 ) {
            // Use native jQuery methods if there is no wildcard matching
            self.removeClass( removals );
            return !additions ? self : self.addClass( additions );
        }
        var patt = new RegExp( '\\s' + 
                removals.
                    replace( /\*/g, '[A-Za-z0-9-_]+' ).
                    split( ' ' ).
                    join( '\\s|\\s' ) + 
                '\\s', 'g' );
        self.each( function ( i, it ) {
            var cn = ' ' + it.className + ' ';
            while ( patt.test( cn ) ) {
                cn = cn.replace( patt, ' ' );
            }
            it.className = $.trim( cn );
        });
        return !additions ? self : self.addClass( additions );
    };

})(jQuery, window, document);

