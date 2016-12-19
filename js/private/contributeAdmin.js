/**
 * This is the main contribution Javascript file used by the contribution page for ADMINS. It has the same basic functionality as the contributeJS file with some
 * extra features for user management and contribution management.
 * The reason for having two seperate files for admin and normal user is to simplify the selection in the backend. Allthough we have to
 * apply changes in both files when developing featues for the normal user (since the admin also has to have these features). 
 *
 * Resources:
 * - https://webdesign.tutsplus.com/tutorials/how-to-integrate-no-captcha-recaptcha-in-your-website--cms-23024 (add google captcha to form)
 * - https://www.formget.com/ajax-image-upload-php/ (image upload through ajax) (multiple uploads with codeigniter http://carlofontanos.com/ajax-multi-file-upload-in-codeigniter/)
 */
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,                 // Success-code returned from code igniter server
        formSteps: 5,                   // Amount of form steps in the form
        buttonNavOption: 0,             // Navigate through form with navButton
        progressNavOption: 1,           // Navigate through form with bootstrap progressBar
        moveForward: 1,                 // Move forward one step in form
        moveBack: -1,                   // Move backwards one step in form
        alertServerSuccess: 0,          // Modal alert message: success
        alertServerFailed: 1,           // Modal alert message: failed
        alertServerUnresponsive: 2,     // Modal alert message: server not responding
        alertDelete: 3,                 // Modal alert message: deletion of data
        alertFeedback: 4,               // Modal alert message: admin feedback
        contributionInitialState: 0,    // Initial contribution form state (displaying the datatable)
        contributionReviewState: 1,     // Second contribution form state (compare existing data with contributed data in form)
        blockUser: 1,                   // Constant used to indicate we want to block a user
        unblockUser: 0,                 // Constant used to indicate we want to unblock a user
        approve: 1,                     // Constant used to indicate that we want to approve a framework contribution
        decline: 0,                     // Constant used to indicate that we want to decline a framework contribution
        editFormInitialState: 0,        // Initial edit form state (displaying the datatables)
        editFormEditState: 1,           // Second edit form state (modify existing data in form)
        formatState: [                  // User contribution states (used for displaying)
            "Awaiting Approval",
		    "Approved",
            "Outdated",
            "Declined"
        ],
        backEndImageURL: "http://localhost/crossmos_projects/decisionTree2/img/logos/",
        backEndBaseURL: "http://localhost/crossmos_projects/decisionTree2/publicCon/",
        backEndPrivateURL: "http://localhost/crossmos_projects/decisionTree2/privateCon/"
    }

    /**
     * The main datatable containing the latest versions of the currently available frameworks
     * that could be subjected to user editting.
     */
    var DataTable = {
        /* Initialize variables used by datatable */
        initVariables: function() {
            this.frameworkTable = null;
            this.domCache = {};
        },
        /* Cache frequently used DOM elements */
        cacheElements: function() {
            this.domCache.$searchFrameworksTable = $("#searchFrameworksTable");
            this.domCache.$filterFieldContainer = $('#searchFrameworksTable_filter');
            this.domCache.$filterField = $('#searchFrameworksTable_filter').find(':input').focus();
        },
        /**
         * Initialize the complete datatable. including variables bindings and markup after initial render.
         * 
         * @param frameworkTable datatable DOM element (wrapper element of datatable)
         */
        init: function($frameworkTable) {
            this.initVariables();
            this.initTable($frameworkTable);
            this.cacheElements();
            this.cleanMarkup();
            this.bindEvents();
        },
        /* Initialize the dataTable itself */
        initTable: function($frameworkTable) {
            this.frameworkTable = $frameworkTable.DataTable({
                // Specify where the datatable should get its data from
                ajax: {
                    url: CONST.backEndPrivateURL + 'AJ_getThumbFrameworks',
                    dataSrc: "frameworks",
                    error: main.errorCallback
                },
                "columnDefs": [
                    { "visible": false, "targets": 1 }  // hide second column
                ],
                // Columns visible in datatable
                columns: [
                    // First column containing framework Thumb
                    {
                        data: 'framework',
                        "type": "html",
                        render: function (data, type, row) {
                            if (type ==='display') {
                                var thumbs = "";
                                // The thumbnail contains an image, icon, framework name, current status of framework, and the last contributor to the framework data
                                thumbs = '<span class="thumb-framework"><img src="' + row.thumb_img + '" alt=""/></span> \
							              <span class="glyphicon glyphicon-pencil pull-right thumb-add"></span> \
							              <span class="thumb-title">' + data + '</span> \
							              <span class="thumb-state ' + CONST.formatState[row.internalState].toLowerCase() + '">' + CONST.formatState[row.internalState] + '</span> \
                                          <span class="thumb-contributor"> - Last contribution by ' + row.contributor + '</span>';
                                return thumbs;
                            } else return '';
                        }
                    },
                    // Second column (invisible)
                    {data:'framework'}  //Must provide second column in order for search to work...
                ],
                // Overwrite default datatable element contents
                language: {
                    search: "<i class='glyphicon glyphicon-search edit-search-feedback'></i>",
                    searchPlaceholder: "Search by framework name...",
                    zeroRecords: "No Frameworks found. Please try another search term."
                },
                // Overwrite default styling and markup of datatable
                "sAutoWidth": false,
                "scrollY":        "356px",
                "scrollCollapse": true,
                "paging":         false,
                "bInfo": false, // hide showing entries
            });
        },
        /* After datatable Initialization we change some visuals of the table (add classes and styling) */
        cleanMarkup: function() {
            this.domCache.$filterFieldContainer.removeClass('dataTables_filter');
            this.domCache.$filterFieldContainer.find("input").addClass("edit-search");
            this.domCache.$searchFrameworksTable.addClass("table table-hover"); //add bootstrap class
            this.domCache.$searchFrameworksTable.css("width","100%");
        },
        /* Bind events to table elements */
        bindEvents: function() {
            var that = this;
            // Event that is called when a row is clicked in datatable
            this.domCache.$searchFrameworksTable.find('tbody').on('click', 'tr', function () {
                var data = that.frameworkTable.row( this ).data();
                if(typeof data !== 'undefined') {
                    main.loadFormData(data);
                }
            });
            // Select current search text when search field gains focus
            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },
        /* Function that reloads data from backend and redraws table after reload finished */
        reloadTable: function() {
            this.frameworkTable.ajax.reload();
        }

    }
    /**
     * Datatable that contains the current user contribution data.
     * When a user has added or modified an existing framework, the framework
     * is queued for review by an admin. During this queuing process the user
     * can make changes (edit/delete) his/her contributions.
     */
    var DataTableUser = {
        /* Initialize variables used by datatable */
        initVariables: function() {
            this.frameworkTable = null;
            this.domCache = {};
        },
        /* Cache frequently used DOM elements */
        cacheElements: function() {
            this.domCache.$searchFrameworksTable = $("#searchUserFrameworksTable");
            this.domCache.$filterFieldContainer = $('#searchUserFrameworksTable_filter');
            this.domCache.$filterField = $('#searchUserFrameworksTable_filter').find(':input').focus();
        },
        /**
         * Initialize the complete datatable. including variables bindings and markup after initial render.
         * 
         * @param frameworkTable datatable DOM element (wrapper element of datatable)
         */
        init: function($frameworkTable) {
            this.initVariables();
            this.initTable($frameworkTable);
            this.cacheElements();
            this.cleanMarkup();
            this.bindEvents();
        },
        /* Initialize the dataTable itself */
        initTable: function($frameworkTable) {
            this.frameworkTable = $frameworkTable.DataTable({
                // Specify where the datatable should get its data from
                ajax: {
                    url: CONST.backEndPrivateURL + 'AJ_getUserThumbFrameworks',
                    dataSrc: "frameworks",
                    error: main.errorCallback
                },
                "columnDefs": [
                    { "visible": false, "targets": 1 }  // hide second column
                ],
                // Columns visible in datatable
                columns: [
                    // First column containing framework Thumb
                    {
                        data: 'framework',
                        "type": "html",
                        render: function (data, type, row) {
                            if (type ==='display') {
                                var thumbs = "";
                                // The thumbnail contains an image, 2 icons, framework name, current status of framework, and the time it was submitted to the backend
                                thumbs = '<span class="thumb-framework"><img src="' + row.thumb_img + '" alt=""/></span> \
							              <span class="glyphicon glyphicon-pencil pull-right thumb-add"></span> \
                                          <span class="glyphicon glyphicon-remove pull-right thumb-remove"></span> \
							              <span class="thumb-title">' + data + '</span> \
							              <span class="thumb-state ' + CONST.formatState[row.internalState].toLowerCase() + '">' + CONST.formatState[row.internalState] + '</span> \
                                          <span class="thumb-time"> - ' + row.time + '</span>';
                                return thumbs;
                            } else return '';
                        }
                    },
                    // Second column (invisible)
                    {data:'framework'}  //Must provide second column in order for search to work...
                ],
                // Overwrite default datatable element contents
                language: {
                    search: "<i class='glyphicon glyphicon-search edit-search-feedback'></i>",
                    searchPlaceholder: "Search by framework name...",
                    zeroRecords: "You have no registered contributions matching the search term. Please select a framework from the approved list and start editing."
                },
                // Overwrite default styling and markup of datatable
                "sAutoWidth": false,
                "scrollY":        "356px",
                "scrollCollapse": true,
                "paging":         false,
                "bInfo": false, // hide showing entries
            });
        },
        /* After datatable Initialization we change some visuals of the table (add classes and styling) */
        cleanMarkup: function() {
            this.domCache.$filterFieldContainer.removeClass('dataTables_filter');
            this.domCache.$filterFieldContainer.find("input").addClass("edit-search");
            this.domCache.$searchFrameworksTable.addClass("table table-hover"); //add bootstrap class
            this.domCache.$searchFrameworksTable.css("width","100%");
        },
        /* Bind events to table elements */
        bindEvents: function() {
            var that = this;
            // Event that is called when a row is clicked in datatable
            this.domCache.$searchFrameworksTable.find('tbody').on('click', 'tr', function () {
                var data = that.frameworkTable.row( this ).data();
                if(typeof data !== 'undefined') {
                    main.loadFormData(data);
                }
            });
            // Select current search text when search field gains focus
            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },
        /* Function that reloads data from backend and redraws table after reload finished */
        reloadTable: function() {
            this.frameworkTable.ajax.reload();
        }
    }

    /**
     * Datatable that contains a list of all the processed user contributions. After the admin
     * has reviewed the pending contribution it can be approve or decline depending on the quality.
     * The user can view a remark message as to why a framework was declined and can make changes
     * to the contributions if needed and then resubmit.
     */
    var DataTableProcessedContributions = {
        /* Initialize variables used by datatable */
        initVariables: function() {
            this.processedContributionTableInitComplete = 1;
            this.processedContributionTable = null;
            this.domCache = {};
        },
        /* Cache frequently used DOM elements */
        cacheElements: function() {
            this.domCache.$processedContributionTable = $("#processedContributionTable");
            this.domCache.$filterFieldContainer = $('#processedContributionTable_filter');
            this.domCache.$filterField = $('#processedContributionTable_filter').find(':input').focus();
        },
        /**
         * Initialize the complete datatable. including variables bindings and markup after initial render.
         * 
         * @param processedContributionTable datatable DOM element (wrapper element of datatable)
         */
        init: function($processedContributionTable) {
            if(typeof this.processedContributionTableInitComplete === 'undefined') {
                this.initVariables();
                this.initTable($processedContributionTable);
                this.cacheElements();
                this.cleanMarkup();
                this.bindEvents();
            }
        },
        /* Initialize the dataTable itself */
        initTable: function($processedContributionTable) {
            this.processedContributionTable = $processedContributionTable.DataTable({
                // Specify where the datatable should get its data from
                ajax: {
                    url: CONST.backEndPrivateURL + 'AJ_getAllProcessedContributions',
                    dataSrc: "frameworks",
                    error: main.errorCallback
                },
                columnDefs: [
                    {className: "dt-center", targets: 4}    // center the content in the last column
                ],
                // Columns visible in datatable
                columns: [
                    {
                        width: "10%",
                        "type": "html",
                        render: function (data, type, row) {
                            if (type ==='display') {
                                var thumbs = "";
                                thumbs = '<span class="thumb-framework"><img src="' + row.thumb_img + '" alt=""/></span>';
                                return thumbs;
                            } else return '';
                        }
                    },
                    // Second column with framework name
                    {title: 'Tool Name', data: 'framework'},
                    // third column with date of approval
                    {title: 'Contribution Date', data: 'time'},
                    // Fourth column containing the status (declined or approved)
                    {
                        title: 'Status',
                        type: "html",
                        render: function (data, type, row) {
                            if (type ==='display') {
                                var state = "";
                                state = '<span class="thumb-processed ' + CONST.formatState[row.status] + '">' + CONST.formatState[row.status] + '</span>';
                                return state;
                            } else return '';
                        }
                    },
                    // Fifth column containing the admin remark in case of rejection (declined)
                    {
                        title: 'Remarks',
                        type: "html",
                        render: function (data, type, row) {
                            if (type ==='display') {
                                var remark = "";
                                if(row.remark === "") {
                                    remark = '<span class="thumb-processed readonly"><i class="fa fa-2x fa-comment-o" aria-hidden="true"></i></span>';
                                } else {
                                    remark = '<span class="thumb-processed"><a class="fa fa-2x fa-comment-o" tabindex="0" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="top" data-content="' + row.remark + '" title="Admin feedback"></a></span>';
                                }
                                return remark;
                            } else return '';
                        }
                    }
                ],
                // Overwrite default datatable element contents
                language: {
                    search: "<i class='glyphicon glyphicon-search edit-search-feedback'></i>",
                    searchPlaceholder: "Search by framework name...",
                    zeroRecords: "No Frameworks found. Please try another search term."
                },
                // Overwrite default styling and markup of datatable
                "initComplete": this.dataLoadComplete,      // callback function that is called when the table has finished loading the data
                "order": [[ 2, "desc" ]],                   // Order table rows in descending order of the third column
                "sAutoWidth": false,
                "scrollY": "344px",                         // Fixed height of table
                "scrollCollapse": true,                     // Scroll the overflow elements
                "paging": false,
                "bInfo": false,                             // hide showing entries
            });
        },
        /* After datatable Initialization we change some visuals of the table (add classes and styling) */
        cleanMarkup: function() {
            this.domCache.$filterFieldContainer.removeClass('dataTables_filter');
            this.domCache.$filterFieldContainer.find("input").addClass("edit-search");
            this.domCache.$processedContributionTable.addClass("table table-hover"); //add bootstrap class
            this.domCache.$processedContributionTable.css("width","100%");
        },
        /* Bind events to table elements */
        bindEvents: function() {
            var that = this;
            // Row item click event
            this.domCache.$processedContributionTable.find('tbody').on('click', 'td', function () {
                var framework = that.processedContributionTable.row( this ).data();
                if(typeof framework !== 'undefined') {
                    // Add markup for a selected item ----
                    that.processedContributionTable.$('tr.selected').removeClass('selected');
                    $(this).parent().addClass('selected');
                    // -----------------------------------
                    var idx = that.processedContributionTable.cell( this ).index().column;
                    if(idx < 4) { // Do nothing when clicked in remark column
                        main.loadProcessedFormData(framework);
                    }
                }
            });
            // Select current search text when search field gains focus
            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },
        /**
         * After the table has loaded the data we apply a HACK to correctly display table header
         * 
         * NOTE: This is not a clean solution but a common problem that exists with datatables.
         * Resources:
         *  - https://datatables.net/forums/discussion/2148/header-width-issue
         */
        dataLoadComplete: function(settings, json) {
            // Css hack to make the header the correct size
            $('#processedContributionTable_wrapper .dataTables_scrollHeadInner').css("width","100%");
            $('#processedContributionTable_wrapper .dataTable').css("width","100%");
            main.initPopOver();
        },
        /* Function that reloads data from backend and redraws table after reload finished */
        reloadTable: function() {
            this.processedContributionTable.ajax.reload(this.dataLoadComplete);
        }
    }

    /**
     * DatatableActive users show a list of all the non-Admin users that are registered in
     * the system using their google login. The table displays the name of the user with
     * their email address and adds the option to block a user if untolerable behaviour is
     * identified 
     */
    var DataTableActiveUser = {
        /* Initialize variables used by datatable */
        initVariables: function() {
            this.userTableInitComplete = 1;
            this.usersTable = null;
            this.domCache = {};
        },
        /* Cache frequently used DOM elements */
        cacheElements: function() {
            this.domCache.$usersTable = $("#activeUsersTable");
            this.domCache.$filterFieldContainer = $('#activeUsersTable_filter');
            this.domCache.$filterField = $('#activeUsersTable_filter').find(':input').focus();
        },
        /**
         * Initialize the complete datatable. including variables bindings and markup after initial render.
         * 
         * @param processedContributionTable datatable DOM element (wrapper element of datatable)
         */
        init: function($usersTable) {
            if(typeof this.userTableInitComplete === 'undefined') {
                this.initVariables();
                this.initTable($usersTable);
                this.cacheElements();
                this.cleanMarkup();
                this.bindEvents();
            }
        },
        /* Initialize the dataTable itself */
        initTable: function($usersTable) {
            this.usersTable = $usersTable.DataTable({
                // Specify where the datatable should get its data from
                ajax: {
                    url: CONST.backEndPrivateURL + 'AJ_getThumbUsers',
                    dataSrc: "users",
                    error: main.errorCallback
                },
                columnDefs: [
                    {className: "dt-center", targets: [3,4]}    // center the content in the last and second last column
                ],
                // Columns visible in datatable
                columns: [
                    // First column containing user email
                    {title: 'Email Address', data: 'email'},
                    // Second column with last time and date the user has contributed
                    {title: 'Last Active', data: 'lastActive'},
                    // third column with the amount of contributions divided in categories
                    {
                        title: 'Contributions',
                        type: "html",
                        data: 'contributionCount',
                        render: {
                            display: function (data, type, row) {
                                if (type ==='display') {
                                    return  '<span class="small-header">Approved: ' + data.approvedCount + "</span>" +
                                            '<span class="small-header">Awaiting: ' + data.awaitCount + "</span>" +
                                            '<span class="small-header">Declined: ' + data.declinedCount + "</span>" +
                                            '<span class="small-header">OutDated: ' + data.outDatedCount + "</span>";
                                } else return '';
                            },
                            sort: "total"
                        }
                    },
                    // Fouth column with the amount of times the user has logged in
                    {title: 'Visited', data: 'visitCount'},
                    // Fifth column containing icon that serves as a blocking button to block the user
                    {
                        title: 'Block',
                        data: null,
                        defaultContent: '<i class="fa fa-ban fa-2x ban-user" aria-hidden="true"></i>'
                    }                
                ],
                // Overwrite default datatable element contents
                language: {
                    search: "<i class='glyphicon glyphicon-search edit-search-feedback'></i>",
                    searchPlaceholder: "Search by email address...",
                    zeroRecords: "No users found. Please try another search term."
                },
                // Overwrite default styling and markup of datatable
                "sAutoWidth": false,
                "scrollY":        "356px",
                "scrollCollapse": true,
                "paging":         false,
                "bInfo": false, // hide showing entries
            });
        },
        /* After datatable Initialization we change some visuals of the table (add classes and styling) */
        cleanMarkup: function() {
            this.domCache.$filterFieldContainer.removeClass('dataTables_filter');
            this.domCache.$filterFieldContainer.find("input").addClass("edit-search");
            this.domCache.$usersTable.addClass("table"); //add bootstrap class
            this.domCache.$usersTable.css("width","100%");
        },
        /* Bind events to table elements */
        bindEvents: function() {
            var that = this;
            // Column item click event (if last column is click we need to block the user otherwise load user contributions)
            this.domCache.$usersTable.find('tbody').on('click', 'td', function () {
                var user = that.usersTable.row( this ).data();
                if(typeof user !== 'undefined') {
                    // Add markup for a selected item ----
                    that.usersTable.$('tr.selected').removeClass('selected');
                    $(this).parent().addClass('selected');
                    // -----------------------------------
                    if($(this).children().hasClass('ban-user')) {
                        main.banUser(user);
                    } else {
                        main.loadUserContributions(user);
                    }
                }
            });
            // Select current search text when search field gains focus
            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },
        /* Function that reloads data from backend and redraws table after reload finished */
        reloadTable: function() {
            this.usersTable.ajax.reload();
        }
    }

    /**
     * DatatableBlocked users show a list of all the non-Admin users that are registered in
     * the system using their google login and are blocked by an admin. The table displays 
     * the name of the user with their email address and adds the option to unblock a user.
     */
    var DataTableBlockedUser = {
        initVariables: function() {
            this.userTableInitComplete = 1;
            this.usersTable = null;
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$usersTable = $("#blockedUsersTable");
            this.domCache.$filterFieldContainer = $('#blockedUsersTable_filter');
            this.domCache.$filterField = $('#blockedUsersTable_filter').find(':input').focus();
        },

        init: function($usersTable) {
            if(typeof this.userTableInitComplete === 'undefined') {
                this.initVariables();
                this.initTable($usersTable);
                this.cacheElements();
                this.cleanMarkup();
                this.bindEvents();
            }
        },

        initTable: function($usersTable) {
            this.usersTable = $usersTable.DataTable({
                ajax: {
                    url: CONST.backEndPrivateURL + 'AJ_getThumbBlockedUsers',
                    dataSrc: "users",
                    error: main.errorCallback
                },
                columnDefs: [
                    {className: "dt-center", targets: [3,4]}
                ],
                columns: [
                    {title: 'Email Address', data: 'email'},
                    {title: 'Last Active', data: 'lastActive'},
                    {
                        title: 'Contributions',
                        type: "html",
                        data: 'contributionCount',
                        render: {
                            display: function (data, type, row) {
                                if (type ==='display') {
                                    return  '<span class="small-header">Approved: ' + data.approvedCount + "</span>" +
                                            '<span class="small-header">Awaiting: ' + data.awaitCount + "</span>" +
                                            '<span class="small-header">Declined: ' + data.declinedCount + "</span>" +
                                            '<span class="small-header">OutDated: ' + data.outDatedCount + "</span>";
                                } else return '';
                            },
                            sort: "total"
                        }
                    },
                    {title: 'Visited', data: 'visitCount'},
                    {
                        title: 'Unblock',
                        data: null,
                        defaultContent: '<i class="fa fa-times fa-2x unban-user" aria-hidden="true"></i>'
                    }                  
                ],
                language: {
                    search: "<i class='glyphicon glyphicon-search edit-search-feedback'></i>",
                    searchPlaceholder: "Search by email address...",
                    zeroRecords: "No users found. Please try another search term."
                },
                "sAutoWidth": false,
                "scrollY":        "356px",
                "scrollCollapse": true,
                "paging":         false,
                "bInfo": false, // hide showing entries
            });
        },

        cleanMarkup: function() {
            this.domCache.$filterFieldContainer.removeClass('dataTables_filter');
            this.domCache.$filterFieldContainer.find("input").addClass("edit-search");
            this.domCache.$usersTable.addClass("table"); //add bootstrap class
            this.domCache.$usersTable.css("width","100%");
        },

        bindEvents: function() {
            var that = this;

            this.domCache.$usersTable.find('tbody').on('click', 'td', function () {
                var user = that.usersTable.row( this ).data();
                if(typeof user !== 'undefined') {
                    // Add markup for a selected item
                    that.usersTable.$('tr.selected').removeClass('selected');
                    $(this).parent().addClass('selected');
                    // ------
                    if($(this).children().hasClass('unban-user')) {
                        main.unbanUser(user);
                    } else {
                        main.loadUserContributions(user);
                    }
                }
            });

            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },

        reloadTable: function() {
            this.usersTable.ajax.reload();
        }
    }

    /**
     * Datatable showing a readonly list of contribution a user has made. The admin
     * is able to select a user from the ActiveUser/BlockedUser table and view all the
     * contribution the user has made.
     */
    var DataTableContributions = {
        initVariables: function() {
            this.userContributionTable = null;
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$userContributionTable = $("#userContributionTable");
            this.domCache.$filterFieldContainer = $('#userContributionTable_filter');
            this.domCache.$filterField = $('#userContributionTable_filter').find(':input').focus();
        },

        init: function($userContributionTable) {
            this.initVariables();
            this.initTable($userContributionTable);
            this.cacheElements();
            this.cleanMarkup();
            this.bindEvents();
        },

        initTable: function($userContributionTable) {
            this.userContributionTable = $userContributionTable.DataTable({
                ajax: { 
                    url: CONST.backEndPrivateURL + 'AJ_getUserContributions?email=0',
                    dataSrc: "frameworks",
                    error: main.errorCallback
                },
                "columnDefs": [
                    { "visible": false, "targets": 1 }  // hide second column
                ],
                columns: [
                    {
                        data: 'framework',
                        "type": "html",
                        render: function (data, type, row) {
                            if (type ==='display') {
                                var thumbs = "";
                                thumbs = '<span class="thumb-framework"><img src="' + row.thumb_img + '" alt=""/></span> \
							              <span class="thumb-title">' + data + '</span> \
							              <span class="thumb-state ' + CONST.formatState[row.internalState].toLowerCase() + '">' + CONST.formatState[row.internalState] + '</span> \
                                          <span class="thumb-time"> - ' + row.time + '</span>';
                                return thumbs;
                            } else return '';
                        }
                    },
                    {data:'framework'}  //Must provide second column in order for search to work...
                ],
                language: {
                    search: "<i class='glyphicon glyphicon-search edit-search-feedback'></i>",
                    searchPlaceholder: "Search by framework name...",
                    zeroRecords: "No contributions found for user."
                },
                "sAutoWidth": false,
                "scrollY":        "356px",
                "scrollCollapse": true,
                "paging":         false,
                "bInfo": false, // hide showing entries
            });
        },

        cleanMarkup: function() {
            this.domCache.$filterFieldContainer.removeClass('dataTables_filter');
            this.domCache.$filterFieldContainer.find("input").addClass("edit-search");
            this.domCache.$userContributionTable.addClass("table"); //add bootstrap class
            this.domCache.$userContributionTable.css("width","100%");
        },

        bindEvents: function() {
            var that = this;

            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },

        reloadTable: function(email) {
            this.userContributionTable.ajax.url(CONST.backEndPrivateURL + 'AJ_getUserContributions?email=' + email).load();
        }

    }

    /**
     * Datatable showing a list of all the pending contributions made by admins and normal users.
     * The admin can then decide if he/she wants to either approve the contribution or decline it
     * after reviewing or making changes.
     */
    var DataTablePendingContributions = {
        initVariables: function() {
            this.contributionTableInitComplete = 1;
            this.contributionTable = null;
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$contributionTable = $("#contributionTable");
            this.domCache.$filterFieldContainer = $('#contributionTable_filter');
            this.domCache.$filterField = $('#contributionTable_filter').find(':input').focus();
        },

        init: function($contributionTable) {
            if(typeof this.contributionTableInitComplete === 'undefined') {
                this.initVariables();
                this.initTable($contributionTable);
                this.cacheElements();
                this.cleanMarkup();
                this.bindEvents();
            }
        },

        initTable: function($contributionTable) {
            this.contributionTable = $contributionTable.DataTable({
                ajax: {
                    url: CONST.backEndPrivateURL + 'AJ_getAllPendingContributions',
                    dataSrc: "frameworks",
                    error: main.errorCallback
                },
                columnDefs: [
                    {className: "dt-center", targets: 4}
                ],
                columns: [
                    {
                        width: "10%",
                        "type": "html",
                        render: function (data, type, row) {
                            if (type ==='display') {
                                var thumbs = "";
                                thumbs = '<span class="thumb-framework"><img src="' + row.thumb_img + '" alt=""/></span>';
                                return thumbs;
                            } else return '';
                        }
                    },
                    {title: 'Tool Name', data: 'framework'},
                    {title: 'Contributor', data: 'contributor'},
                    {title: 'Contribution Date', data: 'time'},
                    {title: 'Type', data: 'type'} 
                ],
                language: {
                    search: "<i class='glyphicon glyphicon-search edit-search-feedback'></i>",
                    searchPlaceholder: "Search by framework name...",
                    zeroRecords: "No Frameworks found. Please try another search term."
                },
                "order": [[ 3, "desc" ]],
                "sAutoWidth": false,
                "scrollY":        "356px",
                "scrollCollapse": true,
                "paging":         false,
                "bInfo": false, // hide showing entries
            });
        },

        cleanMarkup: function() {
            this.domCache.$filterFieldContainer.removeClass('dataTables_filter');
            this.domCache.$filterFieldContainer.find("input").addClass("edit-search");
            this.domCache.$contributionTable.addClass("table table-hover"); //add bootstrap class
            this.domCache.$contributionTable.css("width","100%");
        },

        bindEvents: function() {
            var that = this;

            this.domCache.$contributionTable.find('tbody').on('click', 'tr', function () {
                var data = that.contributionTable.row( this ).data();
                if(typeof data !== 'undefined') {
                    main.updateAdminHeader(data);   // Reflect in the view which contribution we are reviewing
                    main.loadReviewFormData(data);  // load the contribution data into the form and load the previous approved data of the framework (if available) to compare it with
                }
            });

            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },

        reloadTable: function() {
            this.contributionTable.ajax.reload();
        }
    }

    /* Main contribute functionality */
    var main = {
        /* Variables used throught contribution page */
        initVariables: function() {
            this.addState = 1;              // variable used for form navigation      
            this.domCache = {};             // object that contains the cached DOM elements
            this.validForms = [0,0,0,0,1];  // last form can't be invalid (only radio buttons)
            this.formSubmitData = [];       // variable holding the clientside cached form submit data
            this.editFrameworkRef = 0;      // stores the database ID of the referenced framework (referenced means the previous version of the framework data --> new frameworks have reference=0)
            this.editFrameworkName = "";    // The name of the framework that is being editted
            this.editFrameworkId = 0;       // the Database ID of the framework that is currently being editted
            this.reviewFrameworkId = 0;     // the Database ID if the reviewed contribution
            this.reviewFrameworkRef = 0;    // the database ID of the previous version of the framework (if available)
            this.reviewModifiedBy = 0;      // the Database ID of the user that has made the contribution that is being reviewed
            this.uniqueName = "";           // Holds the result of remote framework name lookup
            this.alertFunction = 0;         // specifies the function of the alert modal
        },
        /* Cache the frequently used DOM elements */
        cacheElements: function() {
            this.domCache.$contributeOptions = $('[name="options"]');     
            this.domCache.$frameworkTableWrapper = $('#frameworkTableWrapper');
            this.domCache.$frameworkFormWrapper = $('#formWrapper');
            this.domCache.$editHeaderWrapper = $('#editHeaderWrapper');
            this.domCache.$editTitle = $('.edit-header');
            this.domCache.$removeFramework = $('#removeEdit');
            this.domCache.$goNextAddBtn = $('#goNextStepAdd');
            this.domCache.$goBackAddBtn = $('#goBackStepAdd').hide();
            this.domCache.$formSteps = $('form.current-form');
            this.domCache.$progressSegments = $('.progress-bar'); 
            this.domCache.$alertModal = $('#alertModal');
            this.domCache.$modalUserInput = $('#modalUserInputWrapper');   
            this.domCache.$modalUserFeedbackMsg = $('.user-feedback');
            this.domCache.$modalSuccessIcon = $('.alert-icon-success');
            this.domCache.$modalWarningIcon = $('.alert-icon-warning');
            this.domCache.$modalErrorIcon = $('.alert-icon-error');
            this.domCache.$logoInput = $('#logo');
            // admin dom caches
            this.domCache.$frameworkForm = $('#frameworkForm');
            this.domCache.$userManagement = $('#userManagement');
            this.domCache.$refreshActiveUsers = $('#refreshActiveUsers');
            this.domCache.$refreshBlockedUsers = $('#refreshBlockedUsers');   
            this.domCache.$contributionHeading = $('#contributionHeading');
            this.domCache.$contributionManagement = $('#contributionManagement');
            this.domCache.$refreshContributionTable = $('#refreshContributionTable');
            this.domCache.$adminEditHeader = $('#adminEditHeaderWrapper');
            this.domCache.$formStepsRo = $('form.refered-form');
            this.domCache.$adminReviewTitle = $('.adminEdit-header');
            this.domCache.$modalAdminFeedback = $('#modalAdminFeedbackWrapper');
        },
        /* Initialize the main contribution page functionality. Also call the init functions of the framework datatables */
        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();
            this.initTooltip();

            DataTable.init($('#searchFrameworksTable')); // init before cache to ensure that markup is generated
            DataTableUser.init($('#searchUserFrameworksTable'));
            DataTableProcessedContributions.init($('#processedContributionTable'));
            DataTableContributions.init($("#userContributionTable"));
        },
        /* Mandatory Javascript init of bootstrap tooltip and popOver component */
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },
        initPopOver: function() {
            $('[data-toggle="popover"]').popover();
        },
        /* call init function of user datatables */
        initUserTables: function() {
            DataTableActiveUser.init($('#activeUsersTable'));
            DataTableBlockedUser.init($('#blockedUsersTable'));
        },
        /* call init function of pending contribution datatable */
        initContributionTable: function() {
            DataTablePendingContributions.init($('#contributionTable'));
        },
        /* Bind events to the DOM elements */
        bindEvents: function() {
            var that = this;
            /* Handle switch between add new framework, edit existing framework, manage users, or manage contributions */
            this.domCache.$contributeOptions.on('change', function() {
                that.resetForm();
                switch($(this).val()) {
                    case "userManage":
                        that.initUserTables();
                        that.showUserManagement();
                        break;
                    case "contributeManage":
                        that.initContributionTable();
                        that.showContributionManagement(CONST.contributionInitialState, false);
                        DataTablePendingContributions.reloadTable();
                        break;
                    case "add":
                        that.showForm();
                        that.showAddForm();
                        break;
                    default:
                        // We want to edit an existing framework
                        that.showForm();
                        that.showEditForm(CONST.editFormInitialState);
                        break;
                }
            });
            // Alert Modal events
            $('#modalYes').on('click', function() {
                if(that.alertFunction === CONST.alertDelete) {
                    that.removeFrameworkData();
                }
            });
            $('#modalFeedbackSubmit').on('click', function() {
                var inputEl = $(this).siblings('.admin-feedback-input');
                var data = {
                    name: $(inputEl).prop('name'),
                    value: $(inputEl).val()
                }
                that.cacheAdditionalSubmitData(data);
                that.submitReview(CONST.decline);
            });

            // Admin approve header buttons
            $('#cancelReview').on('click', function() {
                that.showContributionManagement(CONST.contributionInitialState, false);
            });
            $('#approveEntry').on('click', function() {
                that.submitReview(CONST.approve);
            });
            $('#declineEntry').on('click', function() {
                var msg = "You are about to permanently decline one of a user's contribution. Please provide feedback for the user as to why the framework was rejected.";
                that.showModal(msg, CONST.alertFeedback);
            });
            // Form management events
            $('#cancelEdit').on('click', function() {
                that.showEditForm(CONST.editFormInitialState);
            });
            this.domCache.$removeFramework.on('click', function() {
                var msg = "You are about to permanently delete one of your contribution. Do you wish to proceed?";
                that.showModal(msg, CONST.alertDelete);
            });
            $('#updateEdit').on('click', function() {
                that.validateCompleteForm();
                if(that.validForms.indexOf(0) !== -1) {
                    main.showModal(("Validation of form failed. Please correct the invalid field and retry."), CONST.alertServerFailed);
                } else {
                    that.submitData();
                }
            });
            // Image preview event
            this.domCache.$logoInput.on('change', function() {
                var file = this.files[0];
                var imagefile = file.type;
                var match = "image/png";
                if(!(imagefile == match)) {
                    $('.logo-msg').html("Please Select A valid Image File: Only png images are allowed");
                    return;
                } else {
                    var reader = new FileReader();
                    reader.onload = main.previewImageIsLoaded;
                    reader.readAsDataURL(file);
                    $('.logo-msg').html("");
                }
            });
            // button form nav
            this.domCache.$goNextAddBtn.on('click', function() {
                that.goNavStep(CONST.moveForward, CONST.buttonNavOption);
            });
            this.domCache.$goBackAddBtn.on('click', function() {
                that.goNavStep(CONST.moveBack, CONST.buttonNavOption);
            });
            //progress bar nav
            this.domCache.$progressSegments.on('click', function() {
                var step = $(this).data('myValue');
                that.goNavStep(step, CONST.progressNavOption);
            });
            //Add custom validator for framework name
            $('#frmStep1').validator({
                custom: {
                    unique: function(el) {
                        // is called on every input event (we check the focus meta-tag to hold off the validation until the user has left the input field)
                        if(!($(el).is(":focus"))) {
                            if(that.uniqueName === "false") {
                                that.uniqueName = "";
                                return "Tool name is allready used. Consider editing an existing framework.";
                            }
                        }
                    }
                }
            });
            // When a framework name is specified it should be unique. Therefore, we check the user input
            // with our PHP backend to see if the name allready exists. We do some basic checks on the
            // input before sending it to the backend.
            // NOTE: a manual trigger of the input event is done to perform the above validation
            $('#inputToolname').on('change', function() {
                var inputLength = $(this).val().length
                var inputVal = $(this).val().trim();    // no spaces or tabs
                var specialChars = "";
                if(inputLength > 2 && inputLength < 30) {
                    specialChars = inputVal.replace(/[a-z|A-Z|0-9|\-|\.| ]*/g,"");
                    if(specialChars.length === 0) {
                        $.ajax({
                            url: CONST.backEndPrivateURL + "AJ_frameworkExists",
                            dataType: "json",
                            data: {name: inputVal},
                        }).done(function(response) {
                            if(response.hasOwnProperty("srvResponseCode")) {
                                main.uniqueName = response.srvMessage.msg;
                                $('#inputToolname').trigger('input'); // trigger input to force custom validation (does not work with ajax directly)
                            } else {
                                console.log("error on server");
                                main.uniqueName = false;
                            }
                        }).fail(function(jqXHR, textStatus) {
                            that.errorCallback(jqXHR, textStatus, null);
                        });
                    }
                }
            });
            //form field validation events
            this.domCache.$formSteps.validator().on('submit', function (e, step) {
                $('.progress-step' + step).removeClass('active-step valid-step faulty-step');
                if (e.isDefaultPrevented()) {
                    $('.progress-step' + step).addClass('faulty-step');
                    that.validForms[step-1] = 0;
                } else {
                    $('.progress-step' + step).addClass('valid-step');
                    that.validForms[step-1] = 1;
                    that.cacheSubmitData(this);

                    e.preventDefault(); // to stop the form from being submitted
                }
            });
            // Refresh handlers
            $('#refreshProcessedContributionTable').on('click', function() {
                DataTableProcessedContributions.reloadTable();
            });
            this.domCache.$refreshActiveUsers.on('click', function() {
                DataTableActiveUser.reloadTable();
            });
            this.domCache.$refreshBlockedUsers.on('click', function() {
                DataTableBlockedUser.reloadTable();
            });
            this.domCache.$refreshContributionTable.on('click', function() {
                DataTablePendingContributions.reloadTable();
            });
        },
        /**
         * This function is called by the navigation button events (navButton and navProgressBar).
         * Depending on the navigation option used, we move backwards or forwards through the form.
         * 
         * @param step contains the navigation direction in case of button navigation (CONST.moveForward or CONST.moveBack)
         * @param navOption can be navButton or navProgressBar 
         */
        goNavStep: function(step, navOption) {

            if(navOption === CONST.progressNavOption && step === this.addState) return; //do nothing
            
            // hide current container
            this.hideCurrentForm();
            this.hideCurrentRoForm();

            // Trigger validation
            this.triggerSubmitStep(this.addState);
            // go to next
            if(navOption === CONST.buttonNavOption) {
                if(this.addState === CONST.formSteps && step > 0) {
                    // we are submitting
                    if(this.domCache.$adminEditHeader.is(":visible")) {
                        that.submitReview(CONST.approve);
                    } else {
                        this.submitData();
                    }
                    return;
                } else {
                    this.addState += step;
                }
            } else {
                this.addState = step;
            }
            // Show next container
            this.showNextForm();
            this.showNextRoForm();
        },
        /* Figure out the visibility of the navButtons */
        figureOutNavBtn: function() {
            if(this.addState > 1) {
                this.domCache.$goBackAddBtn.show();
            } else {
                this.domCache.$goBackAddBtn.hide();
            }
            if(this.addState >= CONST.formSteps) {
                if(this.domCache.$adminEditHeader.is(":visible")) this.domCache.$goNextAddBtn.text('Approve');
                else this.domCache.$goNextAddBtn.text('Submit');
                if(this.validForms.indexOf(0) !== -1) this.domCache.$goNextAddBtn.prop('disabled', true);
                else this.domCache.$goNextAddBtn.prop('disabled', false);
            } else {
                this.domCache.$goNextAddBtn.prop('disabled', false);
                this.domCache.$goNextAddBtn.text('Next \u00bb');
            } 
        },
        // Add additional fields to submit data cache (add to first form element in cache)
        cacheAdditionalSubmitData: function(entry) {
            this.formSubmitData['frmStep1'].push(entry);
        },
        /**
         *  Retrieve entered data from one form step and store it in variable
         * 
         * @param form The current form-step form
         */
        cacheSubmitData: function(form) {
            var data = $(form).serializeArray();
            var name = $(form).prop('id');
            this.formSubmitData[name] = data;
        },
        /**
         * Combine all seperate form-step data and add some additional data if needed and then perform AJAX request that
         * submits the data to the backend for further validation.
         */
        submitData: function() {
            var data = [];
            var i = 0;
            for(i=1; i<(CONST.formSteps+1); i++) {
                data = data.concat(this.formSubmitData['frmStep'+i]);
            }

            // Add framework reference to data (only set when editing)
            data.push({name:"reference", value:this.editFrameworkRef});

            $.ajax({
                method: "POST",
                url: CONST.backEndPrivateURL + "AJ_addFramework",
                dataType: "json",
                data: {framework: data, currentEditName:this.editFrameworkName},

                error: this.errorCallback,
                success: this.succesCallback
            });
        },
        /* Error callback of AJAX request */
        errorCallback: function(jqXHR, status, errorThrown) {
            // If the server is responding with a non-standard response than we have to check if it is a page redirect.
            if(jqXHR.responseText !== "") {
                if(jqXHR.responseText.indexOf("css/index.css") !== -1) {
                    // Do page redirect to index
                    window.location.replace(CONST.backEndBaseURL);
                }
            } else {
                main.showModal(("Action not completed. server not responding..."), CONST.alertServerFailed);
                // show first form
                main.hideAllForms();
                main.addState = 1;
                main.showNextForm();
            }
        },
        /* callback that is called when AJAX request was Succesful. */
        succesCallback: function(response, status, jqXHR) {
            // Check responsecode
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode !== CONST.successCode) {
                    main.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
                main.showModal("Succesfully submitted contribution", CONST.alertServerSuccess);
                main.uploadLogo(response.srvMessage.framework, main.logoUploadComplete);
                main.resetEditInterface();
            } else {
                main.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },
        /**
         * A custom AJAX request is required to upload the logo in the background.
         * Uploading of the logo is only done when the data was Succesfully submitted in the backend.
         * This is required because we have to have a database entry before we can link the logo to
         * the entry. If the framework data upload failed (e.g. invalid validation) then we don't have
         * to bother uploading the image
         */
        uploadLogo: function(data, succesCallback) {
            // Check if input field is set and upload image with ajax
            var $logoFile = $('#logo');
            if($logoFile.val()) {
                var tmpFormData = new FormData();
                tmpFormData.append("logo", $logoFile[0].files[0]);
                tmpFormData.append("framework", data);

                $.ajax({
                    url: CONST.backEndPrivateURL + 'AJ_uploadLogo', // Url to which the request is send
                    type: "POST",             // Type of request to be send, called as method
                    mimeType: "multipart/form-data",
                    data: tmpFormData,        // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData:false,        // To send DOMDocument or non processed data file it is set to false
                    success: succesCallback,
                    error: this.errorCallback
                });
            }
        },
        /* Success callback of the AJAX logo request */
        logoUploadComplete: function(msg) {
            var response = JSON.parse(msg);
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode !== CONST.successCode) {
                    main.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
                DataTableUser.reloadTable();
            } else {
                main.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },
        /**
         * An admin can make changes to the logo of a pending contribution. This requires
         * a seperate upload function.
         * 
         * NOTE: functionality is similar to normal logo upload. This should be further abstracted
         * behind a function
         */
        adminUploadLogo: function(data, succesCallback) {
            // Check if input field is set and upload image with ajax
            var $logoFile = $('#logo');
            if($logoFile.val()) {
                var tmpFormData = new FormData();
                tmpFormData.append("logo", $logoFile[0].files[0]);
                tmpFormData.append("framework_id", data);

                $.ajax({
                    url: CONST.backEndPrivateURL + 'AJ_adminUploadLogo', // Url to which the request is send
                    type: "POST",             // Type of request to be send, called as method
                    mimeType: "multipart/form-data",
                    data: tmpFormData,        // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData:false,        // To send DOMDocument or non processed data file it is set to false
                    success: succesCallback,
                    error: this.errorCallback
                });
                return true;
            }
            return false;
        },
        /* Success callback of the AJAX admin logo request */
        adminLogoUploadComplete: function(msg) {
            var response = JSON.parse(msg);
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode !== CONST.successCode) {
                    main.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                    return;
                }
                main.submitReviewData(CONST.approve); // proceed with approval process
            } else {
                main.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },
        /* Load framework data from backend using AJAX request and update the form with data */
        loadFormData: function(data) {
            // cache identifier and name for editing purposes (update/delete)
            this.editFrameworkName = data.framework;
            this.editFrameworkId = data.framework_id;

            $.ajax({
                method: "GET",
                url: CONST.backEndPrivateURL + "AJ_getFramework",
                dataType: "json",
                data: {name: data.framework, state: data.internalState},

                error: this.errorCallback,
                success: this.loadFormDataCallback
            });
        },
        /* Perform an AJAX request to load framework data of a selected processed contribution (see processed contribution table) */
        loadProcessedFormData: function(data) {
            // cache name for editing purposes (set id to zero to allow only editing)
            this.editFrameworkName = data.framework;
            this.editFrameworkId = 0;

            $.ajax({
                method: "GET",
                url: CONST.backEndPrivateURL + "AJ_getProcessedFramework",
                dataType: "json",
                data: {id: data.framework_id},

                error: this.errorCallback,
                success: this.loadFormDataCallback
            });
        },
        /* The success callback of the loadForm data AJAX request */
        loadFormDataCallback: function(response) {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode === CONST.successCode) {
                    main.updateFrom(response.srvMessage);
                    main.validateCompleteForm();
                    main.showEditForm(CONST.editFormEditState);
                }else {
                    main.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
            } else {
                main.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },
        /* Function that loops through the received form data and inserts the values into the correct form elements */
        updateFrom: function(frameworkData) {
            var $el = null;
            for (var key in frameworkData) {
                if (frameworkData.hasOwnProperty(key)) {
                    $el = $(":input[name='" + key + "']");
                    if($el.length !== 0) {
                        // Set radio buttons
                        if($el.is(':radio')) {
                            $($el).filter(":radio[value='" + frameworkData[key] + "']").prop('checked', true);
                        // Set checkboxes
                        } else if($el.is(':checkbox')) {
                            $el.prop('checked', false);
                            if(frameworkData[key].indexOf("|") !== -1) {
                                var str = "";
                                var temp = frameworkData[key].split("|");
                                temp.forEach(function(element) {
                                    if(element.indexOf("_") !== -1) $($el).filter(":checkbox[value='" + element + "']").prop('checked', true);
                                    else if(element !== "") str += element + ", ";
                                });
                                if(str !== "") {
                                    str = str.slice(0, -2); // trim last ", " from string
                                    $($el[$el.length-1]).val(str); // the input[type:text] field is always the last one in the array
                                }
                            } else {
                                $($el).filter(":checkbox[value='" + frameworkData[key] + "']").prop('checked', true);
                            }
                        } else {
                            // Set text fields
                            $($el).val(frameworkData[key]);
                        }
                    } else if(key == "logo_name") {
                        // Set preview logo
                        $('#previewLogo').attr('src', (CONST.backEndImageURL + frameworkData[key]));
                    }
                }
            }
            // Store the reference for resubmitting framework data
            this.editFrameworkRef = frameworkData.reference;
        },
        /**
         * This functions updates the readonly form that is used for comparison
         * between the previous framework data (if available) and the newly added
         * user contribution data during the review process. Differences between
         * the two are highlighted in yellow background color of the elements
         * 
         * NOTE: the form is readonly meaning no changes can be made to the
         * form elements
         * 
         * @param frameworkData the user contribution data
         * @param frameworkDataAdj the previous contribution data if specified
         */
        updateReadOnlyForm: function(frameworkData, frameworkDataAdj) {
            var $el = null;
            var $temp = null;
            for (var key in frameworkData) {
                if (frameworkData.hasOwnProperty(key)) {
                    $el = $(":input[name='" + key + "Ro']");
                    if($el.length !== 0) {
                        if($el.is(':radio')) {
                            $el.parent().addClass('disabled');
                            $el.prop('disabled', true);
                            $($el).siblings().removeClass('highlight-diff');
                            $temp = $($el).filter(":radio[value='" + frameworkData[key] + "']");
                            $temp.prop('checked', true);
                            $temp.prop('disabled', false);
                            $temp.parent().removeClass('disabled');
                            if(frameworkData[key] !== frameworkDataAdj[key]) {
                                $($temp).siblings().addClass('highlight-diff');
                            }
                        } else if($el.is(':checkbox')) {
                            $el.prop('checked', false);
                            if(frameworkData[key].indexOf("|") !== -1) {
                                var str = "";
                                var temp = frameworkData[key].split("|");
                                temp.forEach(function(element) {
                                    if(element.indexOf("_") !== -1) {
                                        $($el).filter(":checkbox[value='" + element + "']").prop('checked', true);
                                    } else if(element !== "") str += element + ", ";
                                });
                                if(str !== "") {
                                    str = str.slice(0, -2); // trim last ", " from string
                                    $($el[$el.length-1]).val(str); // the input[type:text] field is always the last one in the array
                                }
                            } else {
                                $($el).filter(":checkbox[value='" + frameworkData[key] + "']").prop('checked', true);
                                if(frameworkData[key] !== frameworkDataAdj[key]) $($el).next('label').addClass('highlight-diff');
                                else $($el).next('label').removeClass('highlight-diff');
                            }
                        } else {
                            // text field
                            $($el).val(frameworkData[key]);
                            if(frameworkData[key] !== frameworkDataAdj[key]) {
                                $($el).addClass('highlight-diff');
                            } else {
                                $($el).removeClass('highlight-diff');
                            }
                        }
                    } else if(key == "logo_name") {
                        $('#previewLogoRo').attr('src', (CONST.backEndImageURL + frameworkData[key]));
                    }
                }
            }
        },
        /* The user is able to delete their own PENDING framework contributions. This function takes care of the AJAX request */
        removeFrameworkData: function() {
            $.ajax({
                method: "POST",
                url: CONST.backEndPrivateURL + "AJ_removeFrameworkEdit",
                dataType: "json",
                data: {name: this.editFrameworkName, identifier: this.editFrameworkId},

                error: this.errorCallback,
                success: this.removeFrameworkEditCallback
            });
        },
        /* Callback that is called when the framework is Succesfully deleted */
        removeFrameworkEditCallback: function(response) {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode === CONST.successCode) {
                    main.showModal("Succesfully removed contribution", CONST.alertServerSuccess);
                    main.resetEditInterface();
                } else {
                    main.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
            } else {
                main.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },
        /* Function for banning (blocking) user */
        banUser: function(user) {
            this.makeUserManagementRequest(user, CONST.blockUser);
        },
        /* Function for unbanning (unblocking) user */
        unbanUser: function(user) {
            this.makeUserManagementRequest(user, CONST.unblockUser);
        },
        /* Make the actual AJAX request for blocking or unblocking user */
        makeUserManagementRequest: function(user, action) {
            $.ajax({
                method: "POST",
                url: CONST.backEndPrivateURL + "AJ_manageUser",
                dataType: "json",
                data: {email: user.email, action: action},

                error: this.errorCallback,
                success: this.userManagementCallback
            });
        },
        /* Provide feedback to admin if user management ajax request was Succesful */
        userManagementCallback: function(response) {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode === CONST.successCode) {
                    main.showModal(response.srvMessage, CONST.alertServerSuccess);
                    DataTableActiveUser.reloadTable();
                    DataTableBlockedUser.reloadTable();
                } else {
                    main.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
            } else {
                main.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },
        /* Function for displaying the a readonly list of all the contributions a user has made. List is shown in datatable */
        loadUserContributions: function(user) {
            this.domCache.$contributionHeading.text(user.email);
            DataTableContributions.reloadTable(user.email);
        },
        /*  */
        loadUserContributionsCallback: function() {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode === CONST.successCode) {
                    main.showModal(response.srvMessage, CONST.alertServerSuccess);
                } else {
                    main.showModal(("User contribution data load not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
            } else {
                main.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },
        /**
         * Function for loading review form data. Meaning the pending contribution data
         * and reference data of a contribution if available. 
         */
        loadReviewFormData: function(data) {
            this.reviewFrameworkId = data.framework_id;
            this.reviewFrameworkRef = data.reference;
            this.reviewModifiedBy = data.modified_by;
            $.ajax({
                method: "GET",
                url: CONST.backEndPrivateURL + "AJ_getAdminFramework",
                dataType: "json",
                data: {framework_id: data.framework_id},

                error: this.errorCallback,
                success: this.loadReviewFormDataCallback
            });
        },
        /* Callback function of a Succesful AJAX load review data request */
        loadReviewFormDataCallback: function(response) {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode === CONST.successCode) {
                   if(response.srvMessage.length === 1) {
                       // We have a new framework that needs to be reviewed
                       main.updateFrom(response.srvMessage[0]);
                       main.validateCompleteForm();
                       main.showContributionManagement(CONST.contributionReviewState, false);
                    } else {
                       // We have an adjusted framework that needs to be reviewed and compared with original [0] == requested [1] == refered original
                       main.updateFrom(response.srvMessage[0]);
                       main.validateCompleteForm();
                       main.updateReadOnlyForm(response.srvMessage[1], response.srvMessage[0]);
                       main.showContributionManagement(CONST.contributionReviewState, true);
                   }
                }else {
                    main.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
            } else {
                main.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },
        /* Function for submitting a review */
        submitReview: function(action) {
            if (action === CONST.approve) {
                /* first upload image if needed (to ensure that the latest image is available if admin has modified it)
                 * after image is updated we go through with approval proccess. If no image is modified the framework is
                 * instantly approved. */
                if(this.adminUploadLogo(this.reviewFrameworkId, this.adminLogoUploadComplete)) {
                    return;
                }
            } 
            this.submitReviewData(action);
        },
        /* Function that gathers the data and performs the actual AJAx request for submitting a review */
        submitReviewData: function(action) {
            var data = [];
            var i = 0;
            for(i=1; i<(CONST.formSteps+1); i++) {
                data = data.concat(this.formSubmitData['frmStep'+i]);
            }
            // Add review data
            data.push({name:"reference", value:this.reviewFrameworkRef});
            data.push({name:"modified_by", value:this.reviewModifiedBy});

            $.ajax({
                method: "POST",
                url: CONST.backEndPrivateURL + "AJ_submitContribution",
                dataType: "json",
                data: {
                    framework: data,
                    toolId: this.reviewFrameworkId,
                    action: action
                },

                error: this.errorCallback,
                success: this.submitReviewSuccessCallback
            });
        },
        /* Callback function that is called when the review is Succesfully submitted with AJAX request */
        submitReviewSuccessCallback: function(response) {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode === CONST.successCode) {
                    main.showModal(response.srvMessage, CONST.alertServerSuccess);
                    DataTablePendingContributions.reloadTable();
                    main.showContributionManagement(CONST.contributionInitialState, false);
                    DataTableUser.reloadTable(); //update user edits
                    DataTable.reloadTable(); //update approved
                }else {
                    main.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
            } else {
                main.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },
        /* Perform a complete form validation (validate all the form steps at once) Validation is triggered by submission */
        validateCompleteForm: function() {
            this.triggerSubmitStep(1);
            this.triggerSubmitStep(2);
            this.triggerSubmitStep(3);
            this.triggerSubmitStep(4);
            this.triggerSubmitStep(5);
            this.addState = 1;
            this.hideAllForms();
            this.hideAllRoForms();
            this.showNextForm();    // set active the current form step
            this.showNextRoForm();
        },
        /* Hide and show the correct DOM elements for adding a new framework */
        showAddForm: function() {
            $('#addNewRad').prop('checked', true);
            $('#addNewRad').parent().addClass('active');
            $('#editExistingRad').parent().removeClass('active');
    
            this.domCache.$frameworkTableWrapper.hide();
            this.domCache.$editHeaderWrapper.hide();
            this.resetForm();
            this.domCache.$frameworkFormWrapper.show();
        },
        /* Hide and show the correct DOM elements for editing existing framework data or contributions */
        showEditForm: function(state) {
            $('#editExistingRad').prop('checked', true);
            $('#editExistingRad').parent().addClass('active');
            $('#addNewRad').parent().removeClass('active');
            if(state === CONST.editFormInitialState) {
                this.domCache.$frameworkTableWrapper.show();
                this.domCache.$editHeaderWrapper.hide();
                this.domCache.$frameworkFormWrapper.hide();
            } else {
                this.domCache.$frameworkTableWrapper.hide();
                this.domCache.$editHeaderWrapper.show();
                if(this.editFrameworkId !== 0) {
                    this.domCache.$removeFramework.show();
                } else {
                    this.domCache.$removeFramework.hide();
                }
                this.domCache.$frameworkFormWrapper.show();
            }
        },
        /* Helper functions for triggering the submit event on the correct form element */
        triggerSubmitStep: function(step) {
            //trigger submit
            $('.container-step' + step).trigger('submit', [step]);
        },
        
        updateAdminHeader: function(data) {
            this.domCache.$adminReviewTitle.text('Reviewing ' + data.framework + ' by ' + data.contributor);
        },
        /* Hide current form step (addState specifies the current step) */
        hideCurrentForm: function() {
            //hide current container
            $('.container-step' + this.addState).hide();
            //remove validation classes current
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
        },
        /* Similar to hideCurrentForm function. Hides the current readonly form */
        hideCurrentRoForm: function() {
            //hide current container
            $('.container-ro-step' + this.addState).hide();
        },
        /* Hide all form steps */
        hideAllForms: function() {
            this.domCache.$formSteps.hide();
        },
        /* Hide all readonly form steps */
        hideAllRoForms: function() {
            this.domCache.$formStepsRo.hide();
        },
        /* Show the next form step */
        showNextForm: function() {
            $('.container-step' + this.addState).show();
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
            $('.progress-step' + this.addState).addClass('active-step');
            
            // Figure-out previous/next button visibility
            this.figureOutNavBtn();
        },
        /* Show the next readonly form step */
        showNextRoForm: function() {
            $('.container-ro-step' + this.addState).show();
        },
        /**
         * Show the admin approve form (makes the wrapper elements of the form and readonly form steps visible) and resizes the column
         * (inside the contribution management) 
         */
        showApproveForm: function(withComparison) {
            this.domCache.$adminEditHeader.show();
            this.showFrameworkForm();
            if(withComparison) {
                $('#formCurrent').addClass('col-xs-8');
                $('#formRefered').show();
            } else {
                $('#formCurrent').removeClass('col-xs-8');
                $('#formRefered').hide();
            }
        },
        /* Hide the admin approve form (inside the contribution management) */
        hideApproveForm: function() {
            $('#formCurrent').removeClass('col-xs-8');
            $('#formRefered').hide();
            this.domCache.$adminEditHeader.hide();
        },
        /* Hide the contribution management view */
        hideContributionManagement: function() {
            this.hideApproveForm();
            this.domCache.$contributionManagement.hide();
        },
        /* Show the form  */
        showFrameworkForm: function() {
            $('#editHeaderWrapper').hide();
            $('#frameworkTableWrapper').hide();
            $('#formWrapper').show();
            $('#formCurrent').show();
        },
        /* Show the form wrapper */
        showForm: function() {
            this.domCache.$userManagement.hide();
            this.hideContributionManagement();
            this.domCache.$frameworkForm.show();
        },
        /* Show user management view */
        showUserManagement: function() {
            this.domCache.$userManagement.show();
            this.domCache.$frameworkForm.hide();
            this.hideContributionManagement();
        },
        /* Show contribution management view */
        showContributionManagement: function(state, withComparison) {
            this.domCache.$userManagement.hide();
            if(state == CONST.contributionInitialState) {
                this.domCache.$frameworkForm.hide();
                this.hideApproveForm();
                this.domCache.$contributionManagement.show();
            } else {
                this.domCache.$contributionManagement.hide();
                this.showApproveForm(withComparison);
                this.domCache.$frameworkForm.show();
            }
        },
        /* Show the feedback bootstrap modal that provides useful information of successful or failed actions of the user or admin */
        showModal: function(message, alertFunction) {
            this.alertFunction = alertFunction;
            this.domCache.$modalUserFeedbackMsg.text(message);
            switch(alertFunction) {
                case CONST.alertDelete:
                    this.domCache.$modalErrorIcon.addClass('hide');
                    this.domCache.$modalSuccessIcon.addClass('hide');
                    this.domCache.$modalWarningIcon.removeClass('hide');
                    this.domCache.$modalUserInput.show();
                    this.domCache.$modalAdminFeedback.hide();
                    break;
                case CONST.alertServerFailed:
                    this.domCache.$modalErrorIcon.removeClass('hide');
                    this.domCache.$modalSuccessIcon.addClass('hide');
                    this.domCache.$modalWarningIcon.addClass('hide');
                    this.domCache.$modalUserInput.hide();
                    this.domCache.$modalAdminFeedback.hide();
                    break;
                case CONST.alertFeedback:
                    this.domCache.$modalErrorIcon.addClass('hide');
                    this.domCache.$modalSuccessIcon.addClass('hide');
                    this.domCache.$modalWarningIcon.removeClass('hide');
                    this.domCache.$modalUserInput.hide();
                    this.domCache.$modalAdminFeedback.show();
                    break
                default:
                    this.domCache.$modalErrorIcon.addClass('hide');
                    this.domCache.$modalSuccessIcon.removeClass('hide');
                    this.domCache.$modalWarningIcon.addClass('hide');
                    this.domCache.$modalUserInput.hide();
                    this.domCache.$modalAdminFeedback.hide();
            }
            this.domCache.$alertModal.modal('show');
        },
        /* Hide the feedback modal */
        hideModal: function() {
            this.domCache.$alertModal.modal('hide');
        },
        /* Reset the edit interface. By displaying the datatable and hiding the form */
        resetEditInterface: function() {
            this.resetForm();
            this.showEditForm(CONST.editFormInitialState);
            DataTableUser.reloadTable(); //update user edits
            DataTable.reloadTable(); //update approved
        },
        /* Clear all the entered form data and move to form step one */
        resetForm: function() {
            var i = 0;
            for(i=0; i<this.domCache.$formSteps.length; i++) {
                this.domCache.$formSteps[i].reset();
            }
            $('#previewLogo').attr('src', (CONST.backEndImageURL + 'notfound.png'));
            $('.logo-msg').html("");
            // reset reference & current framework edit
            this.editFrameworkRef = 0;
            this.editFrameworkName = "";
            this.editFrameworkId = 0;
            // reset form validation
            this.validForms = [0,0,0,0,1];
            $('.progress-step2,.progress-step3,.progress-step4,.progress-step5').removeClass('active-step valid-step faulty-step');
            // show first form
            this.hideAllForms();
            this.addState = 1;
            this.showNextForm();
        },
        /* When file reader has finished loading we update the source of our image */
        previewImageIsLoaded: function(e) {
            $('#previewLogo').attr('src', e.target.result);
        },
        /**
         * Helper function for formatting string.
         */
        formatString: function(str) {
            var temp = str.replace(/[^0-9a-zA-Z]+/g, "");
            return temp.toLowerCase();
        }
    }
    /**
     * handles the social logout from google.
     * 
     * Function described here handle the interaction with the Google Client API and Code igniter backend for logging out a user. After successful logout
     * the user is redirect to the same page on the public section (logged out) 
     */
    var socialLogout = {
        /* Initialize variables */
        initVariables: function() {
            this.domCache = {};
            this.auth2 = null;
        },
        /* Cache frequently used DOM elements */
        cacheElements: function() {
            this.domCache.$socialSignOutBtn = $("#socialSignOut");
        },
        /* Initialize social logout functionality */
        init: function() {
            var that = this;
            this.initVariables();
            this.cacheElements();
            //Init the google authentication api
            gapi.load('auth2', function(){
                // Retrieve the singleton for the GoogleAuth library and set up the client.
                that.auth2 = gapi.auth2.init({
                    client_id: '814864177982-qhde0ik7hkaandtoaaa0515niinslg94.apps.googleusercontent.com',
                    cookie_policy: 'single_host_origin'
                });
                that.bindEvents();
            });
        },
        /* bind DOM element events */
        bindEvents: function() {
            var that = this;
            this.domCache.$socialSignOutBtn.on('click', function() {
                that.socialSignOut();
            });
        },
        /* Sign out the user on the PHP (code igniter) backend */
        socialSignOut: function() {
            // Send the code to the server
            $.ajax({
                method: 'GET',
                url: CONST.backEndPrivateURL + 'AJ_logout',
                dataType: "json",
                success: socialLogout.serverSignoutCallback,
                error: socialLogout.serverSignoutErrorCallback
            });
        },
        /* Sign out the user on google for this application */
        //NOTE that sign out will not work if you are running from localhost.
        signOutGoogle: function() {
            if (this.auth2.isSignedIn.get()) {
                this.auth2.signOut();
            }
        },
        /* Error handler for the logout AJAX request */
        serverSignoutErrorCallback: function() {
            console.log("log out on server failed, server onreachable");
        },
        /* After successful sign out redirect user to public index page */
        serverSignoutCallback: function(response) {
            if(response.srvResponseCode === CONST.successCode) {
                // Log out with google
                socialLogout.signOutGoogle();
                // redirect to public part (and clear history)
                window.location.replace(response.srvMessage);
            }
        }
    }
    /* Wait for document ready before Initializing main and logout functionality */
    $( document ).ready(function() {
        main.init();
        socialLogout.init();
    });

})(jQuery, window, document);