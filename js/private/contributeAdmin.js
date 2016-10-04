(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,
        buttonNavOption: 0,
        progressNavOption: 1,
        contributionInitialState: 0,
        contributionReviewState: 1,
        moveForward: 1,
        moveBack: -1,
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
                var data = that.contributionTable.row( this ).data();
                if(typeof data !== 'undefined') {
                    mainAdmin.updateAdminHeader(data);
                    mainAdmin.loadFormData(data);
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
            this.addState = 1;
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
            this.domCache.$refreshContributionTable = $('#refreshContributionTable');
            this.domCache.$adminEditHeader = $('#adminEditHeaderWrapper');
            this.domCache.$formStepsRo = $('form.refered-form');
            this.domCache.$progressSegments = $('.progress-bar'); 
            this.domCache.$adminReviewTitle = $('.adminEdit-header');
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
                    that.showContributionManagement(CONST.contributionInitialState, false);
                } else {
                    that.showForm();
                }
            });

            // Admin approve header buttons
            $('#cancelReview').on('click', function() {
                that.showContributionManagement(CONST.contributionInitialState, false);
            });

            // button form nav
            $('#goNextStepAdd').on('click', function() {
                that.goNavStep(CONST.moveForward, CONST.buttonNavOption);
            });
            $('#goBackStepAdd').on('click', function() {
                that.goNavStep(CONST.moveBack, CONST.buttonNavOption);
            });
            //progress bar nav
            this.domCache.$progressSegments.on('click', function() {
                var step = $(this).data('myValue');
                that.goNavStep(step, CONST.progressNavOption);
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

        initUserTables: function() {
            DataTableActiveUser.init($('#activeUsersTable'));
            DataTableBlockedUser.init($('#blockedUsersTable'));
        },

        initContributionTable: function() {
            DataTablePendingContributions.init($('#contributionTable'));
        },

        goNavStep: function(step, navOption) {

            if(navOption === CONST.progressNavOption && step === this.addState) return; //do nothing
            
            // hide all containers
            this.hideAllRoForms();
            // go to next
            if(navOption === CONST.buttonNavOption) {
                if(this.addState === CONST.formSteps && step > 0) {
                    return;
                } else {
                    this.addState += step;
                }
            } else {
                this.addState = step;
            }
            // Show next container
            this.showNextRoForm();
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

        loadFormData: function(data) {
            $.ajax({
                method: "GET",
                url: CONST.backEndPrivateURL + "AJ_getAdminFramework",
                dataType: "json",
                data: {framework_id: data.framework_id},

                error: this.errorCallback,
                success: this.loadFormDataCallback
            });
        },

        loadFormDataCallback: function(response) {
            if(response.hasOwnProperty("srvResponseCode")) {
                if(response.srvResponseCode === CONST.successCode) {
                   if(response.srvMessage.length === 1) {
                       // We have a new framework that needs to be reviewed
                       mainAdmin.updateFrom(response.srvMessage[0]);
                       mainAdmin.validateCompleteForm();
                       mainAdmin.showContributionManagement(CONST.contributionReviewState, false);
                    } else {
                       // We have an adjusted framework that needs to be reviewed and compared with original [0] == requested [1] == refered original
                       mainAdmin.updateFrom(response.srvMessage[0]);
                       mainAdmin.validateCompleteForm();
                       mainAdmin.updateReadOnlyForm(response.srvMessage[1], response.srvMessage[0]);
                       mainAdmin.showContributionManagement(CONST.contributionReviewState, true);
                   }
                }else {
                    mainAdmin.showModal(("Action not completed. server message: " + response.srvMessage), CONST.alertServerFailed);
                }
            } else {
                mainAdmin.showModal("Server not responding", CONST.alertServerUnresponsive);
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

        updateAdminHeader: function(data) {
            this.domCache.$adminReviewTitle.text('Reviewing ' + data.framework + ' by ' + data.contributor);
        },
        
        validateCompleteForm: function() {
            this.triggerSubmitStep(1);
            this.triggerSubmitStep(2);
            this.triggerSubmitStep(3);
            this.triggerSubmitStep(4);
            this.triggerSubmitStep(5);
            this.hideAllRoForms();
            this.resetProgressbar();
            this.showNextRoForm();    // set active the current form step
        },

        triggerSubmitStep: function(step) {
            //trigger submit
            $('.container-step' + step).trigger('submit', [step]);
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

        hideCurrentRoForm: function() {
            //hide current container
            $('.container-ro-step' + this.addState).hide();
        },

        hideAllRoForms: function() {
            $('.container-ro-step1').hide();
            $('.container-ro-step2').hide();
            $('.container-ro-step3').hide();
            $('.container-ro-step4').hide();
            $('.container-ro-step5').hide();
        },

        showNextRoForm: function() {
            $('.container-ro-step' + this.addState).show();
        },

        resetProgressbar: function() {
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
            $('.progress-step' + this.addState).addClass('active-step');
        },

        resetCurrentForm: function() {
            var i = 0;
            for(i=0; i<this.domCache.$formSteps.length; i++) {
                this.domCache.$formSteps[i].reset();
            }
        }

    }

    $( document ).ready(function() {
        mainAdmin.init();
    });

})(jQuery, window, document);