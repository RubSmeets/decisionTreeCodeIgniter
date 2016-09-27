
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,
        formSteps: 5,
        buttonNavOption: 0,
        progressNavOption: 1,
        moveForward: 1,
        moveBack: -1,
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
							              <span class="thumb-status ' + row.status.toLowerCase() + '">' + row.status + '</span> \
                                          <span class="thumb-approved">' + CONST.formatState[row.internalState] + '</span>';
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
                main.loadFormData(data);
            });

            this.domCache.$filterField.on('focus', function() {
                $(this).select();
            });
        },

    }

    /* Main contribute functionality */
    var main = {
        initVariables: function() {
            this.addState = 1;
            this.filterTerms = [];
            this.comparedItems = [];
            this.domCache = {};
            this.validForms = [0,0,0,0,1]; //
            this.formSubmitData = [];
        },

        cacheElements: function() {
            this.domCache.$contributeOptions = $('[name="options"]');     
            this.domCache.$frameworkTableWrapper = $('#frameworkTableWrapper');
            this.domCache.$frameworkFormWrapper = $('#formWrapper');
            this.domCache.$editHeaderWrapper = $('#editHeaderWrapper');
            this.domCache.$cancelEdit = $('#cancelEdit');
            this.domCache.$goNextAddBtn = $('#goNextStepAdd');
            this.domCache.$goBackAddBtn = $('#goBackStepAdd').hide();
            this.domCache.$formSteps = $('form');
            this.domCache.$progressSegments = $('.progress-bar');    
        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();

            DataTable.init($('#searchFrameworksTable')); // init before cache to ensure that markup is generated
            this.initTooltip();
        },
        // Mandatory Javascript init of bootstrap tooltip component
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },

        bindEvents: function() {
            var that = this;
            
            this.domCache.$contributeOptions.on('change', function() {
                if($(this).val() === "add") {
                    that.showAddForm();
                } else {
                    that.showEditForm();
                }
            });

            // Form management events
            this.domCache.$cancelEdit.on('click', function() {
                that.showEditForm();
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

            $.ajax({
                method: "POST",
                url: CONST.backEndPrivateURL + "AJ_addFramework",
                dataType: "json",
                data: {framework: data},

                error: this.errorCallback,
                success: this.succesCallback
            });
        },

        errorCallback: function(jqXHR, status, errorThrown) {
            console.log("Something went wrong with request");
        },

        succesCallback: function(data, status, jqXHR) {
            main.resetForm();
            console.log(data);
            console.log("Successful request");
        },

        loadFormData: function(data) {
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
                }
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
            this.showEditForm();
        },

        showAddForm: function() {
            if(this.domCache.$frameworkTableWrapper.is(":visible") || this.domCache.$editHeaderWrapper.is(":visible")) {
                this.domCache.$frameworkTableWrapper.hide();
                this.domCache.$editHeaderWrapper.hide();
                this.resetForm();
                this.domCache.$frameworkFormWrapper.show();
            }
        },

        showEditForm: function() {
            if(!this.domCache.$frameworkTableWrapper.is(":visible")) {
                this.domCache.$frameworkTableWrapper.show();
                this.domCache.$editHeaderWrapper.hide();
                this.domCache.$frameworkFormWrapper.hide();
            } else {
                this.domCache.$frameworkTableWrapper.hide();
                this.domCache.$editHeaderWrapper.show();
                this.domCache.$frameworkFormWrapper.show();
            }
        },

        hideCurrentForm: function() {
            //hide current container
            $('.container-step' + this.addState).hide();
            //remove validation classes current
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
            //trigger submit
            $('.container-step' + this.addState).trigger('submit', [this.addState]);
        },

        showNextForm: function() {
            $('.container-step' + this.addState).show();
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
            $('.progress-step' + this.addState).addClass('active-step');
            
            // Figure-out previous/next button visibility
            this.figureOutNavBtn();
        },

        resetForm: function() {
            var i = 0;
            for(i=0; i<this.domCache.$formSteps.length; i++) {
                this.domCache.$formSteps[i].reset();
            }
            // show first form
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