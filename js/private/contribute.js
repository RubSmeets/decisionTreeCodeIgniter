/**
 * The contribute JS file is the main Javascript file for the NON-ADMIN community contribution page. It provides an interface for
 * a user that is logged-in to add new frameworks to the database or modify existing framework data. In addition, the user can manage 
 * his/her own contributions.
 * 
 * Available (exisiting) frameworks are displayed in jQuery datatables as well as the user contributions. Bootstrap form elements
 * in combination with the bootstrap validator plugin are used to construct a form containing the fields for adding/editing a new
 * cross platform development framework.
 * 
 * Resources:
 * - https://webdesign.tutsplus.com/tutorials/how-to-integrate-no-captcha-recaptcha-in-your-website--cms-23024 (add google captcha to form before submit)
 * - https://github.com/1000hz/bootstrap-validator
 * - https://datatables.net/
 * - https://www.formget.com/ajax-image-upload-php/ 
 */
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,             // Success-code returned from code igniter server
        formSteps: 5,               // Amount of form steps in the form
        buttonNavOption: 0,         // Navigate through form with navButton
        progressNavOption: 1,       // Navigate through form with bootstrap progressBar
        moveForward: 1,             // Move forward one step in form
        moveBack: -1,               // Move backwards one step in form
        alertServerSuccess: 0,      // Modal alert message: success
        alertServerFailed: 1,       // Modal alert message: failed
        alertServerUnresponsive: 2, // Modal alert message: server not responding
        alertDelete: 3,             // Modal alert message: deletion of data
        editFormInitialState: 0,    // Initial edit form state (displaying the datatables)
        editFormEditState: 1,       // Second edit form state (modify existing data in form)
        formatState: [              // User contribution states (used for displaying)
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
                    search: "<i class='glyphicon glyphicon-search edit-search-feedback'></i>",  // Change visuals of search input
                    searchPlaceholder: "Search by framework name...",                           
                    zeroRecords: "No Frameworks found. Please try another search term."         // Specify text to show when no results match search query
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
        /* Fucntion that reloads data from backend and redraws table after reload finished */
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
        /* Fucntion that reloads data from backend and redraws table after reload finished */
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
                    // First column containing framework Thumb image
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
        /* Fucntion that reloads data from backend and redraws table after reload finished */
        reloadTable: function() {
            this.processedContributionTable.ajax.reload(this.dataLoadComplete);
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
			this.uniqueName = "";           // Holds the result of remote framework name lookup
            this.alertFunction = 0;         // specifies the function of the alert modal
        },
        /* Cache the frequently used DOM elements */
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
			this.domCache.$logoInput = $('#logo');
        },
        /* Initialize the main contribution page functionality. Also call the init functions of all the datatables */
        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();
            this.initTooltip();
            // init before cache to ensure that markup is generated
            DataTable.init($('#searchFrameworksTable'));
            DataTableUser.init($('#searchUserFrameworksTable'));
            DataTableProcessedContributions.init($('#processedContributionTable'));
        },
        /* Mandatory Javascript init of bootstrap tooltip and popOver component */
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },
        initPopOver: function() {
            $('[data-toggle="popover"]').popover();
        },
        /* Bind events to the DOM elements */
        bindEvents: function() {
            var that = this;
            /* Handle switch between add new framework or edit existing framework */
            this.domCache.$contributeOptions.on('change', function() {
                // The form is always reset when navigation between add/edit is performed
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
			// Image preview event shows logo when file input changes
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
            // form field validation events
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
        /* Figure out the visibility of the navButtons */
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
                    main.updateFrom(response.srvMessage);       // Update the form with respone framework data
                    main.validateCompleteForm();                // Validate the complete form
                    main.showEditForm(CONST.editFormEditState); // Make the form visible to the user
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
        /* Perform a complete form validation (validate all the form steps at once) Validation is triggered by submission */
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
        /* Hide current form step (addState specifies the current step) */
        hideCurrentForm: function() {
            //hide current container
            $('.container-step' + this.addState).hide();
            //remove validation classes current
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
        },
        /* Hide all form steps */
        hideAllForms: function() {
            $('.container-step1').hide();
            $('.container-step2').hide();
            $('.container-step3').hide();
            $('.container-step4').hide();
            $('.container-step5').hide();
        },
        /* Show the next form step */
        showNextForm: function() {
            $('.container-step' + this.addState).show();
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
            $('.progress-step' + this.addState).addClass('active-step');
            
            // Figure-out previous/next button visibility
            this.figureOutNavBtn();
        },
        /* Show the feedback bootstrap modal that provides useful information of successful or failed actions of the user */
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
                success: socialLogin.serverSignoutCallback,
                error: socialLogin.serverSignoutErrorCallback
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
                socialLogin.signOutGoogle();
                // redirect to public part
                window.location = response.srvMessage;
            }
        }
    }
    /* Wait for document ready before Initializing main and logout functionality */
    $( document ).ready(function() {
        main.init();
        socialLogout.init();
    });

})(jQuery, window, document);