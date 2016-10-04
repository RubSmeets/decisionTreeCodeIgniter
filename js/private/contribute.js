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
            this.alertFunction = 0; // specifies the function of the alert modal
        },

        cacheElements: function() {
            this.domCache.$contributeOptions = $('#addNewRad,#editExistingRad');     
            this.domCache.$frameworkTableWrapper = $('#frameworkTableWrapper');
            this.domCache.$frameworkFormWrapper = $('#formWrapper');
            this.domCache.$editHeaderWrapper = $('#editHeaderWrapper');
            this.domCache.$updateEdit = $('#updateEdit');
            this.domCache.$cancelEdit = $('#cancelEdit');
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
        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();

            DataTable.init($('#searchFrameworksTable')); // init before cache to ensure that markup is generated
            DataTableUser.init($('#searchUserFrameworksTable'));
            this.initTooltip();
        },
        // Mandatory Javascript init of bootstrap tooltip component
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },

        bindEvents: function() {
            var that = this;
            
            this.domCache.$contributeOptions.on('change', function() {
                that.resetForm();
                if($(this).val() === "add") {
                    that.showAddForm();
                } else {
                    that.showEditForm(CONST.editFormInitialState);
                }
            });

            // Alert Modal events
            $('#modalYes').on('click', function() {
                if(that.alertFunction === CONST.alertDelete) {
                    that.removeFrameworkData();
                }
            });

            // Form management events
            this.domCache.$cancelEdit.on('click', function() {
                that.showEditForm(CONST.editFormInitialState);
            });
            this.domCache.$removeFramework.on('click', function() {
                var msg = "You are about to permanently delete one of your contribution. Do you wish to proceed?";
                that.showModal(msg, CONST.alertDelete);
            });
            this.domCache.$updateEdit.on('click', function() {
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
        },

        goNavStep: function(step, navOption) {

            if(navOption === CONST.progressNavOption && step === this.addState) return; //do nothing
            
            // hide current container
            this.hideCurrentForm();
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

        validateCompleteForm: function() {
            this.triggerSubmitStep(1);
            this.triggerSubmitStep(2);
            this.triggerSubmitStep(3);
            this.triggerSubmitStep(4);
            this.triggerSubmitStep(5);
            this.addState = 1;
            this.hideAllForms();
            this.showNextForm();    // set active the current form step
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

        hideCurrentForm: function() {
            //hide current container
            $('.container-step' + this.addState).hide();
            //remove validation classes current
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
        },

        hideAllForms: function() {
            $('.container-step1').hide();
            $('.container-step2').hide();
            $('.container-step3').hide();
            $('.container-step4').hide();
            $('.container-step5').hide();
        },

        showNextForm: function() {
            $('.container-step' + this.addState).show();
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
            $('.progress-step' + this.addState).addClass('active-step');
            
            // Figure-out previous/next button visibility
            this.figureOutNavBtn();
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
            this.showEditForm();
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