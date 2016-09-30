(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,
        blockUser: 1,
        unblockUser: 0,
        alertServerSuccess: 0,
        alertServerFailed: 1,
        alertServerUnresponsive: 2,
        alertDelete: 3,
        formatState: [
            "Awaiting Approval",
		    "Approved"
        ],
        backEndBaseURL: "http://localhost/crossmos_projects/decisionTree2/publicCon/",
        backEndPrivateURL: "http://localhost/crossmos_projects/decisionTree2/privateCon/"
    }

    /* DatatableActive users functionality */
    var DataTableActiveUser = {
        initVariables: function() {
            this.userTableInitComplete = 1;
            this.usersTable = null;
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$usersTable = $("#activeUsersTable");
            this.domCache.$filterFieldContainer = $('#activeUsersTable_filter');
            this.domCache.$filterField = $('#activeUsersTable_filter').find(':input').focus();
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
                    url: CONST.backEndPrivateURL + 'AJ_getThumbUsers',
                    dataSrc: "users"
                },
                columnDefs: [
                    {className: "dt-center", targets: [2,3]}
                ],
                columns: [
                    {title: 'Email Address', data: 'email'},
                    {title: 'Last Active', data: 'lastActive'},
                    {title: 'Visited', data: 'visitCount'},
                    {
                        title: 'Block',
                        data: null,
                        defaultContent: '<i class="fa fa-ban fa-2x ban-user" aria-hidden="true"></i>'
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
                    if($(this).children().hasClass('ban-user')) {
                        mainAdmin.banUser(user);
                    } else {
                        mainAdmin.loadUserContributions(user);
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
    /* Datatable functionality for blocked users table */
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
                    dataSrc: "users"
                },
                columnDefs: [
                    {className: "dt-center", targets: [2,3]}
                ],
                columns: [
                    {title: 'Email Address', data: 'email'},
                    {title: 'Last Active', data: 'lastActive'},
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
                        mainAdmin.unbanUser(user);
                    } else {
                        mainAdmin.loadUserContributions(user);
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

    /* Datatable functionality */
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
                    dataSrc: "frameworks" 
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

    /* Datatable Contributions functionality */
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
                    dataSrc: "frameworks"
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
                var data = that.frameworkTable.row( this ).data();
                if(typeof data !== 'undefined') {
                    //
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

    /* Main Admin contribute functionality */
    var mainAdmin = {
        initVariables: function() {
            this.domCache = {};
            this.alertFunction = 0; // specifies the function of the alert modal
        },

        cacheElements: function() {
            this.domCache.$contributeOptions = $('[name="options"]');
            this.domCache.$frameworkForm = $('#frameworkForm');
            this.domCache.$userManagement = $('#userManagement');
            this.domCache.$refreshActiveUsers = $('#refreshActiveUsers');
            this.domCache.$refreshBlockedUsers = $('#refreshBlockedUsers');
            this.domCache.$alertModal = $('#alertModal');
            this.domCache.$modalUserInput = $('#modalUserInputWrapper');   
            this.domCache.$modalUserFeedbackMsg = $('.user-feedback');
            this.domCache.$modalSuccessIcon = $('.alert-icon-success');
            this.domCache.$modalWarningIcon = $('.alert-icon-warning');
            this.domCache.$modalErrorIcon = $('.alert-icon-error');
            this.domCache.$contributionHeading = $('#contributionHeading');
            this.domCache.$contributionManagement = $('#contributionManagement');
        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();

            DataTableContributions.init($("#userContributionTable"));
        },

        bindEvents: function() {
            var that = this;
            
            this.domCache.$contributeOptions.on('change', function() {
                if($(this).val() === "userManage") {
                    that.initUserTables();
                    that.showUserManagement();
                } else if($(this).val() === "contributeManage") {
                    that.initContributionTable();
                    that.showContributionManagement();
                } else {
                    that.showForm();
                }
            });

            this.domCache.$refreshActiveUsers.on('click', function() {
                DataTableActiveUser.reloadTable();
            });
            this.domCache.$refreshBlockedUsers.on('click', function() {
                DataTableBlockedUser.reloadTable();
            });

        },

        initUserTables: function() {
            DataTableActiveUser.init($('#activeUsersTable'));
            DataTableBlockedUser.init($('#blockedUsersTable'));
        },

        initContributionTable: function() {
            DataTablePendingContributions.init($('#contributionTable'));
        },

        banUser: function(user) {
            this.makeUserManagementRequest(user, CONST.blockUser);
        },

        unbanUser: function(user) {
            this.makeUserManagementRequest(user, CONST.unblockUser);
        },

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

        errorCallback: function() {
            console.log("Something went wrong with request");
        },

        userManagementCallback: function(response) {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode === CONST.successCode) {
                    mainAdmin.showModal(response.srvMessage, CONST.alertServerSuccess);
                    DataTableActiveUser.reloadTable();
                    DataTableBlockedUser.reloadTable();
                } else {
                    mainAdmin.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
            } else {
                mainAdmin.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },

        loadUserContributions: function(user) {
            this.domCache.$contributionHeading.text(user.email);
            DataTableContributions.reloadTable(user.email);
        },

        loadUserContributionsCallback: function() {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode === CONST.successCode) {
                    mainAdmin.showModal(response.srvMessage, CONST.alertServerSuccess);
                } else {
                    mainAdmin.showModal(("User contribution data load not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
            } else {
                mainAdmin.showModal("Server not responding", CONST.alertServerUnresponsive);
            }
        },

        showForm: function() {
            this.domCache.$userManagement.hide();
            this.domCache.$contributionManagement.hide();
            this.domCache.$frameworkForm.show();
        },

        showUserManagement: function() {
            this.domCache.$userManagement.show();
            this.domCache.$frameworkForm.hide();
            this.domCache.$contributionManagement.hide();
        },

        showContributionManagement: function() {
            this.domCache.$userManagement.hide();
            this.domCache.$frameworkForm.hide();
            this.domCache.$contributionManagement.show();
        },

        showModal: function(message, alertFunction) {
            this.alertFunction = alertFunction;
            this.domCache.$modalUserFeedbackMsg.text(message);
            if(alertFunction === CONST.alertDelete) {
                this.domCache.$modalErrorIcon.addClass('hide');
                this.domCache.$modalSuccessIcon.addClass('hide');
                this.domCache.$modalWarningIcon.removeClass('hide');
                this.domCache.$modalUserInput.show();
            } else if(alertFunction === CONST.alertServerFailed) {
                this.domCache.$modalErrorIcon.removeClass('hide');
                this.domCache.$modalSuccessIcon.addClass('hide');
                this.domCache.$modalWarningIcon.addClass('hide');
                this.domCache.$modalUserInput.hide();
            } else {
                this.domCache.$modalErrorIcon.addClass('hide');
                this.domCache.$modalSuccessIcon.removeClass('hide');
                this.domCache.$modalWarningIcon.addClass('hide');
                this.domCache.$modalUserInput.hide();
            }
            
            this.domCache.$alertModal.modal('show');

        }
    }

    $( document ).ready(function() {
        mainAdmin.init();
    });

})(jQuery, window, document);