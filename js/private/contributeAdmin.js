/*
 * Resources:
 * - https://webdesign.tutsplus.com/tutorials/how-to-integrate-no-captcha-recaptcha-in-your-website--cms-23024 (add google captcha to form)
 * - 
 */
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,
        formSteps: 5,
        buttonNavOption: 0,
        progressNavOption: 1,
        moveForward: 1,
        moveBack: -1,
        alertServerSuccess: 0,
        alertServerFailed: 1,
        alertServerUnresponsive: 2,
        alertDelete: 3,
        contributionInitialState: 0,
        contributionReviewState: 1,
        blockUser: 1,
        unblockUser: 0,
        approve: 1,
        decline: 0,
        editFormInitialState: 0,
        editFormEditState: 1,
        formatState: [
            "Awaiting Approval",
		    "Approved"
        ],
        backEndBaseURL: "http://localhost/crossmos_projects/decisionTree2/publicCon/",
        backEndPrivateURL: "http://localhost/crossmos_projects/decisionTree2/privateCon/"
    }

    /* Datatable functionality */
    var DataTable = {
        initVariables: function() {
            this.frameworkTable = null;
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$searchFrameworksTable = $("#searchFrameworksTable");
            this.domCache.$filterFieldContainer = $('#searchFrameworksTable_filter');
            this.domCache.$filterField = $('#searchFrameworksTable_filter').find(':input').focus();
        },

        init: function($frameworkTable) {
            this.initVariables();
            this.initTable($frameworkTable);
            this.cacheElements();
            this.cleanMarkup();
            this.bindEvents();
        },

        initTable: function($frameworkTable) {
            this.frameworkTable = $frameworkTable.DataTable({
                ajax: {
                    url: CONST.backEndPrivateURL + 'AJ_getThumbFrameworks',
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
							              <span class="glyphicon glyphicon-pencil pull-right thumb-add"></span> \
							              <span class="thumb-title">' + data + '</span> \
							              <span class="thumb-state ' + CONST.formatState[row.internalState].toLowerCase() + '">' + CONST.formatState[row.internalState] + '</span> \
                                          <span class="thumb-contributor"> - Contributed by ' + row.contributor + '</span>';
                                return thumbs;
                            } else return '';
                        }
                    },
                    {data:'framework'}  //Must provide second column in order for search to work...
                ],
                language: {
                    search: "<i class='glyphicon glyphicon-search edit-search-feedback'></i>",
                    searchPlaceholder: "Search by framework name...",
                    zeroRecords: "No Frameworks found. Please try another search term."
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
            this.domCache.$searchFrameworksTable.addClass("table table-hover"); //add bootstrap class
            this.domCache.$searchFrameworksTable.css("width","100%");
        },

        bindEvents: function() {
            var that = this;

            this.domCache.$searchFrameworksTable.find('tbody').on('click', 'tr', function () {
                var data = that.frameworkTable.row( this ).data();
                if(typeof data !== 'undefined') {
                    main.loadFormData(data);
                }
            });

            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },

        reloadTable: function() {
            this.frameworkTable.ajax.reload();
        }

    }
    /* Datatable functionality user table */
    var DataTableUser = {
        initVariables: function() {
            this.frameworkTable = null;
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$searchFrameworksTable = $("#searchUserFrameworksTable");
            this.domCache.$filterFieldContainer = $('#searchUserFrameworksTable_filter');
            this.domCache.$filterField = $('#searchUserFrameworksTable_filter').find(':input').focus();
        },

        init: function($frameworkTable) {
            this.initVariables();
            this.initTable($frameworkTable);
            this.cacheElements();
            this.cleanMarkup();
            this.bindEvents();
        },

        initTable: function($frameworkTable) {
            this.frameworkTable = $frameworkTable.DataTable({
                ajax: {
                    url: CONST.backEndPrivateURL + 'AJ_getUserThumbFrameworks',
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
							              <span class="glyphicon glyphicon-pencil pull-right thumb-add"></span> \
                                          <span class="glyphicon glyphicon-remove pull-right thumb-remove"></span> \
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
                    zeroRecords: "You have no registered contributions matching the search term. Please select a framework from the approved list and start editing."
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
            this.domCache.$searchFrameworksTable.addClass("table table-hover"); //add bootstrap class
            this.domCache.$searchFrameworksTable.css("width","100%");
        },

        bindEvents: function() {
            var that = this;

            this.domCache.$searchFrameworksTable.find('tbody').on('click', 'tr', function () {
                var data = that.frameworkTable.row( this ).data();
                if(typeof data !== 'undefined') {
                    main.loadFormData(data);
                }
            });

            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },

        reloadTable: function() {
            this.frameworkTable.ajax.reload();
        }

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
                        main.banUser(user);
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
                var data = that.contributionTable.row( this ).data();
                if(typeof data !== 'undefined') {
                    main.updateAdminHeader(data);
                    main.loadReviewFormData(data);
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
        initVariables: function() {
            this.addState = 1;
            this.filterTerms = [];
            this.comparedItems = [];
            this.domCache = {};
            this.validForms = [0,0,0,0,1]; // last form can't be invalid
            this.formSubmitData = [];
            this.editFrameworkRef = 0;
            this.editFrameworkName = "";
            this.editFrameworkId = 0;
            this.reviewFrameworkId = 0;
            this.reviewFrameworkRef = 0;
            this.reviewModifiedBy = 0;
            this.alertFunction = 0; // specifies the function of the alert modal
        },

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
        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();

            DataTable.init($('#searchFrameworksTable')); // init before cache to ensure that markup is generated
            DataTableUser.init($('#searchUserFrameworksTable'));
            DataTableContributions.init($("#userContributionTable"));

            this.initTooltip();
        },
        // Mandatory Javascript init of bootstrap tooltip component
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },

        initUserTables: function() {
            DataTableActiveUser.init($('#activeUsersTable'));
            DataTableBlockedUser.init($('#blockedUsersTable'));
        },

        initContributionTable: function() {
            DataTablePendingContributions.init($('#contributionTable'));
        },

        bindEvents: function() {
            var that = this;
            
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
                        break;
                    case "add":
                        that.showForm();
                        that.showAddForm();
                        break;
                    default:
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

            // Admin approve header buttons
            $('#cancelReview').on('click', function() {
                that.showContributionManagement(CONST.contributionInitialState, false);
            });
            $('#approveEntry').on('click', function() {
                that.submitReview(CONST.approve);
            });
            $('#declineEntry').on('click', function() {
                that.submitReview(CONST.decline);
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
                    this.submitData();
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

        figureOutNavBtn: function() {
            if(this.addState > 1) {
                this.domCache.$goBackAddBtn.show();
            } else {
                this.domCache.$goBackAddBtn.hide();
            }
            if(this.addState >= CONST.formSteps) {
                this.domCache.$goNextAddBtn.text('Submit');
                if(this.validForms.indexOf(0) !== -1) this.domCache.$goNextAddBtn.prop('disabled', true);
                else this.domCache.$goNextAddBtn.prop('disabled', false);
            } else {
                this.domCache.$goNextAddBtn.prop('disabled', false);
                this.domCache.$goNextAddBtn.text('Next \u00bb');
            } 
        },

        cacheSubmitData: function(form) {
            var data = $(form).serializeArray();
            var name = $(form).prop('id');
            this.formSubmitData[name] = data;
        },

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

        errorCallback: function(jqXHR, status, errorThrown) {
            console.log("Something went wrong with request");
        },

        succesCallback: function(response, status, jqXHR) {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode !== CONST.successCode) {
                    main.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
                main.showModal("Succesfully submitted contribution", CONST.alertServerSuccess);
                main.resetEditInterface();
            } else {
                main.showModal("Server nor responding", CONST.alertServerUnresponsive);
            }
        },

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

        updateFrom: function(frameworkData) {
            var $el = null;
            for (var key in frameworkData) {
                if (frameworkData.hasOwnProperty(key)) {
                    $el = $(":input[name='" + key + "']");
                    if($el.length !== 0) {
                        if($el.is(':radio')) {
                            $($el).filter(":radio[value='" + frameworkData[key] + "']").prop('checked', true);
                        } else if($el.is(':checkbox')) {
                            $el.prop('checked', false);
                            $($el).filter(":checkbox[value='" + frameworkData[key] + "']").prop('checked', true);
                        } else {
                            // text field
                            $($el).val(frameworkData[key]);
                        }
                    }
                }
            }
            // Store the reference for resubmitting framework data
            this.editFrameworkRef = frameworkData.reference;
        },

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
                            $($el).filter(":checkbox[value='" + frameworkData[key] + "']").prop('checked', true);
                            if(frameworkData[key] !== frameworkDataAdj[key]) {
                                $($el).next('label').addClass('highlight-diff');
                            } else {
                                $($el).next('label').removeClass('highlight-diff');
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
                    }
                }
            }
        },

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

        loadUserContributions: function(user) {
            this.domCache.$contributionHeading.text(user.email);
            DataTableContributions.reloadTable(user.email);
        },

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

        submitReview: function(action) {
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

        showAddForm: function() {
            $('#addNewRad').prop('checked', true);
            $('#addNewRad').parent().addClass('active');
            $('#editExistingRad').parent().removeClass('active');
    
            this.domCache.$frameworkTableWrapper.hide();
            this.domCache.$editHeaderWrapper.hide();
            this.resetForm();
            this.domCache.$frameworkFormWrapper.show();
        },

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

        triggerSubmitStep: function(step) {
            //trigger submit
            $('.container-step' + step).trigger('submit', [step]);
        },

        updateAdminHeader: function(data) {
            this.domCache.$adminReviewTitle.text('Reviewing ' + data.framework + ' by ' + data.contributor);
        },

        hideCurrentForm: function() {
            //hide current container
            $('.container-step' + this.addState).hide();
            //remove validation classes current
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
        },

        hideCurrentRoForm: function() {
            //hide current container
            $('.container-ro-step' + this.addState).hide();
        },

        hideAllForms: function() {
            this.domCache.$formSteps.hide();
        },

        hideAllRoForms: function() {
            this.domCache.$formStepsRo.hide();
        },

        showNextForm: function() {
            $('.container-step' + this.addState).show();
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
            $('.progress-step' + this.addState).addClass('active-step');
            
            // Figure-out previous/next button visibility
            this.figureOutNavBtn();
        },

        showNextRoForm: function() {
            $('.container-ro-step' + this.addState).show();
        },

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

        hideApproveForm: function() {
            $('#formCurrent').removeClass('col-xs-8');
            $('#formRefered').hide();
            this.domCache.$adminEditHeader.hide();
        },

        hideContributionManagement: function() {
            this.hideApproveForm();
            this.domCache.$contributionManagement.hide();
        },

        showFrameworkForm: function() {
            $('#editHeaderWrapper').hide();
            $('#frameworkTableWrapper').hide();
            $('#formWrapper').show();
            $('#formCurrent').show();
        },

        showForm: function() {
            this.domCache.$userManagement.hide();
            this.hideContributionManagement();
            this.domCache.$frameworkForm.show();
        },

        showUserManagement: function() {
            this.domCache.$userManagement.show();
            this.domCache.$frameworkForm.hide();
            this.hideContributionManagement();
        },

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
        },

        hideModal: function() {
            this.domCache.$alertModal.modal('hide');
        },

        resetEditInterface: function() {
            this.resetForm();
            this.showEditForm(CONST.editFormInitialState);
            DataTableUser.reloadTable(); //update user edits
            DataTable.reloadTable(); //update approved
        },

        resetForm: function() {
            var i = 0;
            for(i=0; i<this.domCache.$formSteps.length; i++) {
                this.domCache.$formSteps[i].reset();
            }
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
        }
    }

    var socialLogin = {
        initVariables: function() {
            this.domCache = {};
            this.auth2 = null;
        },

        cacheElements: function() {
            this.domCache.$socialSignOutBtn = $("#socialSignOut");
        },

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

        bindEvents: function() {
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
                success: socialLogin.serverSignoutCallback,
                error: socialLogin.serverSignoutErrorCallback
            });
        },
        //NOTE that sign out will not work if you are running from localhost.
        signOutGoogle: function() {
            if (this.auth2.isSignedIn.get()) {
                this.auth2.signOut();
            }
        },

        serverSignoutErrorCallback: function() {
            console.log("log out on server failed, server onreachable");
        },

        serverSignoutCallback: function(response) {
            if(response.srvResponseCode === CONST.successCode) {
                // Log out with google
                socialLogin.signOutGoogle();
                // redirect to public part
                window.location = response.srvMessage;
            }
        }
    }

    $( document ).ready(function() {
        main.init();
        socialLogin.init();
    });

})(jQuery, window, document);