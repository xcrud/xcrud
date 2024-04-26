/** f0ska xCRUD v.1.6.26; 03/2015 */
$(document).on('click', '#select_all', function() {
    $(".emp_checkbox").prop("checked", this.checked);
    $("#select_count").html($("input.emp_checkbox:checked").length + " Selezionati");
});
$(document).on('click', '.emp_checkbox', function() {
    if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
        $('#select_all').prop('checked', true);
    } else {
        $('#select_all').prop('checked', false);
    }
    $("#select_count").html($("input.emp_checkbox:checked").length + " Selezionati");
});
$('#delete_records').on('click', function(e) {
	var table = $(this).attr("data_table");
    var action = $(this).attr("data_action");
    var employee = [];
    $(".emp_checkbox:checked").each(function() {
        employee.push($(this).data('emp-id'));
    });
    if (employee.length <= 0) {
        alert("Please select records.");
    } else {
        WRN_PROFILE_DELETE = "Sei sicuro di voler cancellare " + (employee.length > 1 ? "questi" : "questo") + " record ?";
        var checked = confirm(WRN_PROFILE_DELETE);
        if (checked == true) {
            var selected_values = employee.join(",");
            $.ajax({
                type: "POST",
                url: "controller.php?table="+table,
                cache: false,
                data: 'action='+ action + '&emp_id=' + selected_values,
                dataType:"json",
                success: function(response) {
                    alert("Both Fields are Required");
                    // remove deleted employee rows
                    var emp_ids = response.split(",");
                    for (var i = 0; i < emp_ids.length; i++) {
                        $("#" + emp_ids[i]).remove();
                    }

                }
            });
        }
    }
});
/** object */
var Xcrud = {
    config: function(key) {
        if (xcrud_config[key] !== undefined) {
            return xcrud_config[key];
        } else {
            return key;
        }
    },
    lang: function(key) {
        if (xcrud_config['lang'][key] !== undefined) {
            return xcrud_config['lang'][key];
        } else {
            return key;
        }
    },
    current_task: null,
    request: function(container, data, success_callback) {
        //jQuery(container).trigger("xcrudbeforerequest");
        jQuery(document).trigger("xcrudbeforerequest", [container, data]);
        jQuery.ajax({
            type: "post",
            url: Xcrud.config('url'),
            beforeSend: function() {
                Xcrud.current_task = data.task;
                Xcrud.show_progress(container);
            },
            data: {
                "xcrud": data
            },
            success: function(response) {
                jQuery(container).html(response);
                //jQuery(container).trigger("xcrudafterrequest");
                var status = Xcrud.check_message(container);
                jQuery(document).trigger("xcrudafterrequest", [container, data, status]);
                if (success_callback) {
                    success_callback(container);
                }
            },
            complete: function() {
                Xcrud.hide_progress(container);
            },
            dataType: "html",
            cache: false
        });
    },
    new_window_request: function(container, data) {
        var html = Xcrud.data2form(data);
        var w = window.open("", "Xcrud_request", "scrollbars,resizable,height=400,width=600");
        w.document.open();
        w.document.write(html);
        w.document.close();
        jQuery(w.document.body).find('form').submit();
    },
    data2form: function(data) {
        var html = '<!DOCTYPE HTML><html><head><meta http-equiv="content-type" content="text/html;charset=UTF-8" /></head><body>';
        html += '<form method="post" action="' + Xcrud.config('url') + '">';
        jQuery.map(data, function(value, key) {
            if (!jQuery.isPlainObject(value)) {
                html += '<input type="hidden" name="xcrud[' + key + ']" value="' + value + '" />';
            }
        });
        html += '</form></body></html>';
        return html;
    },
    unique_check: function(container, data, success_callback) {
        data.unique = {};
        data.task = "unique";
        if (jQuery(container).find('.xcrud-input[data-unique]').size()) {
            jQuery(container).find('.xcrud-input[data-unique]').each(function(index, element) {
                data.unique[jQuery(element).attr('name')] = jQuery(element).val();
            });
            jQuery.ajax({
                type: "post",
                url: Xcrud.config('url'),
                beforeSend: function() {
                    Xcrud.show_progress(container);
                },
                data: {
                    "xcrud": data
                },
                dataType: "json",
                success: function(response) {
                    //jQuery(container).find(".xcrud-data[name=key]:first").val(response.key);
                    if (response.error) {
                        jQuery(container).find(response.error.selector).addClass('validation-error');
                        //alert(Xcrud.lang('unique_error'));
                        Xcrud.show_message(container, Xcrud.lang('unique_error'), 'error');
                        return false;
                    }
                    if (success_callback) {
                        success_callback(container);
                    }
                },
                complete: function() {
                    Xcrud.hide_progress(container);
                },
                cache: false
            });
        } else {
            if (success_callback) {
                success_callback(container);
            }
        }
    },
    show_progress: function(container) {
        jQuery(container).closest(".xcrud").find(".xcrud-overlay").width(jQuery(container).closest(".xcrud-container").width()).stop(true, true).fadeTo(300, 0.6);
    },
    hide_progress: function(container) {
        jQuery(container).closest(".xcrud").find(".xcrud-overlay").stop(true, true).css("display", "none");
    },
    get_container: function(element) {
        return jQuery(element).closest(".xcrud-ajax");
    },
    list_data: function(container, element) {
        var data = {};
        Xcrud.validation_error = 0;
        Xcrud.save_editor_content(container);
        jQuery(container).find(".xcrud-data").each(function() {
            if (Xcrud.check_container(this, container)) {
                data[jQuery(this).attr("name")] = Xcrud.prepare_val(this);
            }
        });
        if (element && jQuery.isPlainObject(element)) {
            jQuery.extend(data, element);
        } else if (element) {
            jQuery.extend(data, jQuery(element).data());
        }
        data.postdata = {};
        var validation = data.task == 'save' ? true : false;
        if(validation){
            jQuery(document).trigger("xcrudbeforevalidate",[container]);
        }
        jQuery(container).find('.xcrud-input:not([type="checkbox"],[type="radio"],[disabled])').each(function() {
            if (Xcrud.check_container(this, container)) {
                var val = Xcrud.prepare_val(this);
                data.postdata[jQuery(this).attr("name")] = val;
                var required = jQuery(this).data('required');
                var pattern = jQuery(this).data('pattern');
                if (validation && required && !Xcrud.validation_required(val, required)) {
                    Xcrud.validation_error = 1;
                    jQuery(this).addClass('validation-error');
                } else if (validation && pattern && !Xcrud.validation_pattern(val, pattern)) {
                    Xcrud.validation_error = 1;
                    jQuery(this).addClass('validation-error');
                } else {
                    jQuery(this).removeClass('validation-error');
                }
            }
        });
        jQuery(container).find('.xcrud-input[data-type="checkboxes"]:not([disabled])').each(function() {
            if (data.postdata[jQuery(this).attr("name")] === undefined) {
                data.postdata[jQuery(this).attr("name")] = '';
            }
            if (Xcrud.check_container(this, container) && jQuery(this).prop('checked')) {
                if (!data.postdata[jQuery(this).attr("name")]) {
                    data.postdata[jQuery(this).attr("name")] = Xcrud.prepare_val(this);
                } else {
                    data.postdata[jQuery(this).attr("name")] += "," + Xcrud.prepare_val(this);
                }
            }
        });
        jQuery(container).find('.xcrud-input[type="radio"]:not([disabled])').each(function() {
            if (Xcrud.check_container(this, container) && jQuery(this).prop('checked')) {
                data.postdata[jQuery(this).attr("name")] = Xcrud.prepare_val(this);
            }
        });
        jQuery(container).find('.xcrud-input[data-type="bool"]:not([disabled])').each(function() {
            if (Xcrud.check_container(this, container)) {
                data.postdata[jQuery(this).attr("name")] = jQuery(this).prop('checked') ? 1 : 0;
            }
        });
        jQuery(container).find(".xcrud-searchdata.xcrud-search-active").each(function() {
            if (Xcrud.check_container(this, container)) {
                data[jQuery(this).attr("name")] = Xcrud.prepare_val(this);
            }
        });

        if(validation){
            jQuery(document).trigger("xcrudaftervalidate",[container,data]);
        }
        return data;
    },
    list_controls_data: function(container, element) {
        var data = {};
        jQuery(container).find(".xcrud-data").each(function() {
            if (Xcrud.check_container(this, container)) {
                data[jQuery(this).attr("name")] = Xcrud.prepare_val(this);
            }
        });
        return data;
    },
    check_container: function(element, container) {
        return jQuery(element).closest(".xcrud-ajax").attr('id') == jQuery(container).attr('id') ? true : false;
    },
    save_editor_content: function(container) {
        if (jQuery(container).find('.xcrud-texteditor').size()) {
            if (typeof(tinyMCE) != 'undefined') {
                tinyMCE.triggerSave();
/*for (instance in tinyMCE.editors) {
                    if (tinyMCE.editors[instance] && isNaN(instance * 1)) {
                        if (jQuery('#' + instance).size()) {
                            tinyMCE.editors[instance].save();
                        } else {
                            //tinyMCE.editors[instance].destroy();
                            //tinyMCE.editors[instance] = null;
                        }
                    }
                }*/
            }
            if (typeof(CKEDITOR) != 'undefined') {
                for (instance in CKEDITOR.instances) {
                    if (jQuery('#' + instance).size()) {
                        CKEDITOR.instances[instance].updateElement();
                    }
/*else {
                        CKEDITOR.instances[instance].destroy();
                    }*/
                }
            }
        }
    },
    prepare_val: function(element) {
        switch (jQuery(element).data("type")) {
        case 'datetime':
        case 'timestamp':
        case 'date':
        case 'time':
            if (jQuery(element).val()) {
                var d = jQuery(element).datepicker("getDate");
                return d ? Math.round(d.getTime() / 1000) - d.getTimezoneOffset() * 60 : '';
            } else
            return '';
            break;
        default:
            return jQuery.trim(jQuery(element).val());
            break;
        }
    },
    change_filter: function(type, container, fieldname) {
        jQuery(container).find(".xcrud-searchdata").hide().removeClass("xcrud-search-active");
        var name_selector = '';
        switch (type) {
        case 'datetime':
        case 'timestamp':
        case 'date':
        case 'time':
            var fieldtype = 'date';
            break;
        case 'bool':
            var fieldtype = 'bool';
            break;
        case 'select':
        case 'multiselect':
        case 'radio':
        case 'checkboxes':
            var fieldtype = 'dropdown';
            name_selector = '[data-fieldname="' + fieldname + '"]';
            break;
        default:
            var fieldtype = 'default';
            break;
        }
        jQuery(container).find('.xcrud-searchdata[data-fieldtype="' + fieldtype + '"]' + name_selector).show().addClass("xcrud-search-active");
        if (fieldtype == 'date') {
            Xcrud.init_datepicker_range(type, container);
        }
    },
    init_datepicker_range: function(type, container) {
        jQuery(container).find('.xcrud-datepicker-from.hasDatepicker,.xcrud-datepicker-to.hasDatepicker').datepicker("destroy");
        var datepicker_config = {
            changeMonth: true,
            changeYear: true,
            showSecond: true,
            dateFormat: Xcrud.config('date_format'),
            timeFormat: Xcrud.config('time_format')
        };
        switch (type) {
        case 'datetime':
        case 'timestamp':
            // to
            datepicker_config.onClose = function(selectedDate) {
                jQuery(container).find('.xcrud-datepicker-from').datetimepicker("option", "maxDate", selectedDate);
            }
            datepicker_config.onSelect = datepicker_config.onClose;
            jQuery(container).find('.xcrud-datepicker-to').datetimepicker(datepicker_config);
            // from
            datepicker_config.maxDate = jQuery(container).find('.xcrud-datepicker-to').val();
            datepicker_config.onClose = function(selectedDate) {
                jQuery(container).find('.xcrud-datepicker-to').datetimepicker("option", "minDate", selectedDate);
            }
            datepicker_config.onSelect = datepicker_config.onClose;
            jQuery(container).find('.xcrud-datepicker-from').datetimepicker(datepicker_config);
            break;
        case 'date':
            // to
            datepicker_config.onClose = function(selectedDate) {
                jQuery(container).find('.xcrud-datepicker-from').datepicker("option", "maxDate", selectedDate);
            }
            datepicker_config.onSelect = datepicker_config.onClose;
            jQuery(container).find('.xcrud-datepicker-to').datepicker(datepicker_config);
            // from
            datepicker_config.maxDate = jQuery(container).find('.xcrud-datepicker-to').val();
            datepicker_config.onClose = function(selectedDate) {
                jQuery(container).find('.xcrud-datepicker-to').datepicker("option", "minDate", selectedDate);
            }
            datepicker_config.onSelect = datepicker_config.onClose;
            jQuery(container).find('.xcrud-datepicker-from').datepicker(datepicker_config);
            break;
        case 'time':
            jQuery(container).find('.xcrud-datepicker-from,.xcrud-datepicker-to').timepicker(datepicker_config);
            break;
        }
        jQuery(".ui-datepicker").css("font-size", "0.9em"); // reset ui size
    },
    init_datepicker: function(container) {
        if (jQuery(container).find(".xcrud-datepicker").size()) {
            jQuery(container).find(".xcrud-datepicker").each(function() {
                var element = jQuery(this);
                var format_id = jQuery(this).data("type");
                switch (format_id) {
                case 'datetime':
                case 'timestamp':
                    element.datetimepicker({
                        showSecond: true,
                        timeFormat: Xcrud.config('time_format'),
                        dateFormat: Xcrud.config('date_format'),
                        firstDay: Xcrud.config('date_first_day'),
                        changeMonth: true,
                        changeYear: true
                    });
                    break;
                case 'time':
                    element.timepicker({
                        showSecond: true,
                        dateFormat: Xcrud.config('date_format'),
                        timeFormat: Xcrud.config('time_format')
                    });
                    break;
                case 'date':
                default:
                    element.datepicker({
                        dateFormat: Xcrud.config('date_format'),
                        firstDay: Xcrud.config('date_first_day'),
                        changeMonth: true,
                        changeYear: true,
                        onClose: function(selectedDate) {
                            var range_start = element.data("rangestart");
                            var range_end = element.data("rangeend");
                            if (range_start) {
                                var target = element.closest(".xcrud-ajax").find('input[name="' + range_start + '"]');
                                jQuery(target).datepicker("option", "maxDate", selectedDate);
                            }
                            if (range_end) {
                                var target = element.closest(".xcrud-ajax").find('input[name="' + range_end + '"]');
                                jQuery(target).datepicker("option", "minDate", selectedDate);
                            }
                        }
                    });
                    var range_start = element.data("rangestart");
                    var range_end = element.data("rangeend");
                    if (range_start && element.val()) {
                        var target = element.closest(".xcrud-ajax").find('input[name="' + range_start + '"]');
                        jQuery(target).datepicker("option", "maxDate", element.val());
                    }
                    if (range_end && element.val()) {
                        var target = element.closest(".xcrud-ajax").find('input[name="' + range_end + '"]');
                        jQuery(target).datepicker("option", "minDate", element.val());
                    }
                }
            });
        }
    },
    init_texteditor: function(container) {
        var elements = jQuery(container).find(".xcrud-texteditor:not(.editor-loaded)");
        if (jQuery(elements).size()) {
            if (Xcrud.config('editor_url') || Xcrud.config('force_editor')) {
                jQuery(elements).addClass("editor-loaded").addClass("editor-instance");
                if (Xcrud.config('editor_init_url')) {
                    window.setTimeout(function() {
                        jQuery.ajax({
                            url: Xcrud.config('editor_init_url'),
                            type: "get",
                            dataType: "script",
                            success: function(js) {
                                jQuery(".xcrud-overlay").stop(true, true).css("display", "none");
                                jQuery(elements).removeClass("editor-instance");
                            },
                            cache: true
                        });
                    }, 300);
                } else {
                    if (typeof(tinyMCE) != 'undefined') {
                        tinyMCE.init({
                            mode: "textareas",
                            editor_selector: "editor-instance",
                            height: "250"
                        });
                    } else if (typeof(CKEDITOR) != 'undefined') {
                        CKEDITOR.replaceAll('editor-instance');
                    }
                    jQuery(elements).removeClass("editor-instance");
                }
            }
        }
    },
    upload_file: function(element, data, container) {
        var upl_container = jQuery(element).closest('.xcrud-upload-container');
        data.field = jQuery(element).data("field");
        data.oldfile = jQuery(upl_container).find('.xcrud-input').val();
        data.task = "upload";
        data.type = jQuery(element).data("type");
        var ext = Xcrud.get_extension(jQuery(element).val());
        if (data.type == 'image') {
            switch (ext.toLowerCase()) {
            case 'jpg':
            case 'jpeg':
            case 'gif':
            case 'png':
                break;
            default:
                Xcrud.show_message(container, Xcrud.lang('image_type_error'), 'error');
                jQuery(element).val('');
                return false;
                break;
            }
        }
        jQuery(document).trigger("xcrudbeforeupload", [container, data]);
        Xcrud.show_progress(container);
        jQuery.ajaxFileUpload({
            secureuri: false,
            fileElementId: jQuery(element).attr('id'),
            data: {
                "xcrud": data
            },
            url: Xcrud.config('url'),
            success: function(out) {
                Xcrud.hide_progress(container);
                jQuery(upl_container).replaceWith(out);
                var status = Xcrud.check_message(container);
                jQuery(document).trigger("xcrudafterupload", [container, data, status]);
                var crop_img = jQuery(out).find("img.xcrud-crop");
                if (jQuery(crop_img).size()) {
                    Xcrud.show_crop_window(crop_img, container);
                }
            },
            error: function() {
                Xcrud.hide_progress(container);
                Xcrud.show_message(container, Xcrud.lang('undefined_error'), 'error');
            }
        });
    },
    show_crop_window: function(crop_img, container) {
        var upl_container = jQuery(container).find('img.xcrud-crop').closest('.xcrud-upload-container');
        jQuery(crop_img).dialog({
            resizable: false,
            height: 'auto',
            width: 'auto',
            modal: true,
            closeOnEscape: false,
            buttons: {
                "OK": function() {
                    var data = Xcrud.list_data(container,{"task":"crop_image"});
                    jQuery(upl_container).find('.xrud-crop-data').each(function() {
                        data[jQuery(this).attr('name')] = jQuery(this).val();
                    });
                    //data.task = "crop_image";
                    jQuery(document).trigger("xcrudbeforeecrop", [container, data]);
                    Xcrud.show_progress(container);
                    jQuery.ajax({
                        data: {
                            "xcrud": data
                        },
                        success: function(out) {
                            Xcrud.hide_progress(container);
                            jQuery(upl_container).replaceWith(out);
                            jQuery(document).trigger("xcrudaftercrop", [container, data]);
                        },
                        error: function() {
                            Xcrud.hide_progress(container);
                            Xcrud.show_message(container, Xcrud.lang('undefined_error'), 'error');
                        },
                        type: "post",
                        url: Xcrud.config('url'),
                        dataType: "html",
                        cache: false,
                    });
                    jQuery(this).dialog("destroy");
                    jQuery(".xcrud-crop").remove();
                }
            },
            close: function(event, ui) {
                var data = Xcrud.list_data(container,{"task":"crop_image"});
                jQuery(upl_container).find('.xrud-crop-data').each(function() {
                    data[jQuery(this).attr('name')] = jQuery(this).val();
                });
                //data.task = "crop_image";
                data.w = 0;
                data.h = 0;
                Xcrud.show_progress(container);
                jQuery.ajax({
                    data: {
                        "xcrud": data
                    },
                    success: function(out) {
                        Xcrud.hide_progress(container);
                        jQuery(upl_container).replaceWith(out);
                    },
                    error: function() {
                        Xcrud.hide_progress(container);
                        Xcrud.show_message(container, Xcrud.lang('undefined_error'), 'error');
                    },
                    type: "post",
                    url: Xcrud.config('url'),
                    dataType: "html",
                    cache: false,
                });
                jQuery(this).dialog("destroy");
                jQuery(".xcrud-crop").remove();
            },
            open: function(event, ui) {
                Xcrud.load_image(crop_img.attr('src'), function(imageObject) {
                    var t_w = parseInt(jQuery(crop_img).data('width'));
                    var t_h = parseInt(jQuery(crop_img).data('height'));
                    var ratio = parseFloat(jQuery(crop_img).data('ratio'));
                    var cropset = {};
                    cropset.boxWidth = t_w;
                    cropset.boxHeight = t_h;
                    if (t_h > 500) {
                        cropset.boxHeight = 500;
                        cropset.boxWidth = Math.round(t_w * 500 / t_h)
                    }
                    if (cropset.boxWidth > 550) {
                        cropset.boxWidth = 550;
                        cropset.boxHeight = Math.round(t_h * 550 / t_w);
                    }
                    var left = Math.round((jQuery(window).width() - cropset.boxWidth - 10) / 2);
                    var top = Math.round((jQuery(window).height() - cropset.boxHeight - 75) / 2);
                    jQuery(".ui-dialog.ui-widget").css({
                        "position": "fixed",
                        "left": left + "px",
                        "top": top + "px"
                    });
                    cropset.minSize = [50, 50];
                    if (ratio) {
                        cropset.aspectRatio = ratio;
                    }
                    cropset.onChange = Xcrud.get_coordinates;
                    cropset.keySupport = false;
                    cropset.trueSize = [t_w, t_h];
                    var w1 = t_w / 4;
                    var h1 = t_h / 4;
                    var w2 = w1 * 3;
                    var h2 = h1 * 3;
                    cropset.setSelect = [w1, h1, w2, h2];
                    cropset.allowSelect = false;
                    jQuery(".ui-dialog img.xcrud-crop").Jcrop(cropset);
                });
            }
        });
    },
    load_image: function(url, callback) {
        var imageObject = new Image();
        imageObject.src = url;
        if (imageObject.complete) {
            if (callback) {
                callback(imageObject);
            }
        } else {
            jQuery(document).trigger("startload");
            imageObject.onload = function() {
                jQuery(document).trigger("stopload");
                if (callback) {
                    callback(imageObject);
                }
            }
            imageObject.onerror = function() {
                jQuery(document).trigger("stopload");
                if (callback) {
                    callback(false);
                }
            }
        }
    },
    remove_file: function(element, data, container) {
        var upl_container = jQuery(element).closest('.xcrud-upload-container');
        data.field = jQuery(element).data("field");
        data.file = jQuery(upl_container).find('.xcrud-input').val();
        data.task = "remove_upload";
        Xcrud.show_progress(container);
        jQuery.ajax({
            data: {
                "xcrud": data
            },
            success: function(data) {
                Xcrud.hide_progress(container);
                jQuery(upl_container).replaceWith(data);
            },
            type: "post",
            url: Xcrud.config('url'),
            dataType: "html",
            cache: false,
            error: function() {
                Xcrud.hide_progress(container);
                Xcrud.show_message(container, Xcrud.lang('undefined_error'), 'error');
            }
        });
    },
    get_coordinates: function(c) {
        jQuery(".xcrud").find("input.xrud-crop-data[name=x]").val(Math.round(c.x));
        jQuery(".xcrud").find("input.xrud-crop-data[name=y]").val(Math.round(c.y));
        jQuery(".xcrud").find("input.xrud-crop-data[name=x2]").val(Math.round(c.x2));
        jQuery(".xcrud").find("input.xrud-crop-data[name=y2]").val(Math.round(c.y2));
        jQuery(".xcrud").find("input.xrud-crop-data[name=w]").val(Math.round(c.w));
        jQuery(".xcrud").find("input.xrud-crop-data[name=h]").val(Math.round(c.h));
    },
    validation_required: function(val, length) {
        return jQuery.trim(val).length >= length;
    },
    validation_pattern: function(val, pattern) {
        if (val === '') {
            return true;
        }
        switch (pattern) {
        case 'email':
            reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            return reg.test(jQuery.trim(val));
            break;
        case 'alpha':
            reg = /^([a-z])+$/i;
            return reg.test(jQuery.trim(val));
            break;
        case 'alpha_numeric':
            reg = /^([a-z0-9])+$/i;
            return reg.test(jQuery.trim(val));
            break;
        case 'alpha_dash':
            reg = /^([-a-z0-9_-])+$/i;
            return reg.test(jQuery.trim(val));
            break;
        case 'numeric':
            reg = /^[\-+]?[0-9]*(\.|\,)?[0-9]+$/;
            return reg.test(jQuery.trim(val));
            break;
        case 'integer':
            reg = /^[\-+]?[0-9]+$/;
            return reg.test(jQuery.trim(val));
            break;
        case 'decimal':
            reg = /^[\-+]?[0-9]+(\.|\,)[0-9]+$/;
            return reg.test(jQuery.trim(val));
            break;
        case 'point':
            reg = /^[\-+]?[0-9]+\.{0,1}[0-9]*\,[\-+]?[0-9]+\.{0,1}[0-9]*$/;
            return reg.test(jQuery.trim(val));
            break;
        case 'natural':
            reg = /^[0-9]+$/;
            return reg.test(jQuery.trim(val));
            break;
        default:
            reg = new RegExp(pattern);
            return reg.test(jQuery.trim(val));
            break;
        }
        return true;
    },
    pattern_callback: function(e, element) {
        var pattern = jQuery(element).data('pattern');
        if (pattern) {
            var code = e.which;
            if (code < 32 || e.ctrlKey || e.altKey) return true;
            var val = String.fromCharCode(code);
            switch (pattern) {
            case 'alpha':
                reg = /^([a-z])+$/i;
                return reg.test(val);
                break;
            case 'alpha_numeric':
                reg = /^([a-z0-9])+$/i;
                return reg.test(val);
                break;
            case 'alpha_dash':
                reg = /^([-a-z0-9_-])+$/i;
                return reg.test(val);
                break;
            case 'numeric':
            case 'integer':
            case 'decimal':
            case 'point':
                reg = /^[0-9\.\,\-+]+$/;
                return reg.test(val);
                break;
            case 'natural':
                reg = /^[0-9]+$/;
                return reg.test(val);
                break;
            }
        }
        return true;
    },
    validation_error: false,
    get_extension: function(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    },
    check_fixed_buttons: function() {
        jQuery(".xcrud").each(function() {
            if (jQuery(this).find(".xcrud-list:first").width() > jQuery(this).find(".xcrud-list-container:first").width()) {
                var w = jQuery(this).find(".xcrud-actions:not(.xcrud-fix):first").width();
                jQuery(this).find(".xcrud-actions:not(.xcrud-fix):first").css({
                    "width": w,
                    "min-width": w
                });
                jQuery(this).find(".xcrud-list:first .xcrud-actions.xcrud-fix:not(.xcrud-actions-fixed)").addClass("xcrud-actions-fixed");
            } else
            jQuery(this).find(".xcrud-list:first .xcrud-actions").removeClass("xcrud-actions-fixed");
        });
    },
    block_query: {},
    depend_init: function(container) {
        jQuery(container).off('change.depend');
        var dependencies = {};
        jQuery(container).find('.xcrud-input[data-depend]').each(function() {
            var container = Xcrud.get_container(this);
            var data = Xcrud.list_controls_data(container, this);
            var depend_on = jQuery(this).data("depend");
            data.task = "depend";
            data.name = jQuery(this).attr('name');
            data.value = jQuery(this).val();
            jQuery(container).on('change.depend', '.xcrud-input[name="' + depend_on + '"]', function() {
                if (Xcrud.check_container(this, container)) {
                    data.dependval = jQuery(this).val();
                    Xcrud.depend_query(data, depend_on, container);
                }
            });
            if (depend_on) dependencies[depend_on] = depend_on;
        });
        jQuery.map(dependencies, function(val, key) {
            window.setTimeout(function() {
                jQuery(container).find('.xcrud-input[name="' + val + '"]:not([data-depend])').trigger('change.depend');
            }, 100);
        });
    },
    depend_query: function(data, depend_on, container) {
        if (Xcrud.block_query[data.name + depend_on]) {
            return;
        }
        Xcrud.block_query[data.name + depend_on] = 1;
        jQuery(document).trigger("xcrudbeforedepend", [container, data]);
        jQuery.ajax({
            data: {
                "xcrud": data
            },
            type: 'post',
            url: Xcrud.config('url'),
            success: function(input) {
                jQuery(container).find('.xcrud-input[name="' + data.name + '"]').replaceWith(input);
                window.setTimeout(function() {
                    jQuery(document).trigger("xcrudafterdepend", [container, data]);
                    jQuery(container).find('.xcrud-input[name="' + data.name + '"]').trigger('change.depend');
                    Xcrud.block_query[data.name + depend_on] = 0;
                }, 50);
            },
            cache: false
        });
    },
    parse_latlng: function(string) {
        var coords = string.split(',');
        if (coords.length != 2) {
            return null;
        }
        var LatLng = new google.maps.LatLng(parseFloat(coords[0]), parseFloat(coords[1]));
        return LatLng;
    },
    create_map: function(selector, center, zoom, type) {
        var params = {
            zoom: zoom,
            center: center,
            mapTypeId: google.maps.MapTypeId[type]
        }
        var map = new google.maps.Map(jQuery(selector)[0], params);
        return map;
    },
    place_marker: function(map, point, draggable, infowindow, point_field) {
        var marker = new google.maps.Marker({
            position: point,
            map: map,
            animation: google.maps.Animation.DROP,
            draggable: (draggable ? true : false)
        });
        if (infowindow) {
            google.maps.event.addListener(marker, 'click', function() {
                var currentmarker = this;
                var infoWindow = new google.maps.InfoWindow({
                    maxWidth: 320
                });
                infoWindow.setContent('<p class="xcrud-infowinow">' + infowindow + '</p>');
                infoWindow.open(map, currentmarker);
            });
        }
        if (draggable && jQuery(point_field).size()) {
            google.maps.event.addListener(marker, 'dragend', function() {
                jQuery(point_field).val(this.getPosition().lat() + ',' + this.getPosition().lng());
            });
            google.maps.event.addListener(map, 'click', function(event) {
                //console.log(oMap);
                marker.setPosition(event.latLng);
                jQuery(point_field).val(marker.getPosition().lat() + ',' + marker.getPosition().lng());
            });
        }
        return marker;
    },
    move_marker: function(map, marker, point, dragable, infowindow) {
        if (marker) {
            marker.setPosition(point);
        } else {
            this.place_marker(map, point, dragable, infowindow)
        }
        map.setCenter(point);
        return marker;
    },
    find_point: function(address, callback) {
        return this.geocode({
            address: address
        }, callback);
    },
    find_address: function(point, callback) {
        return this.geocode({
            latLng: point
        }, callback);
    },
    geocode: function(geocoderRequest, callback, callback_single) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode(geocoderRequest, function(results, status) {
            //console.log(results);
            var output = {};
            if (status == google.maps.GeocoderStatus.OK) {
                for (var i = 0; i < results.length; i++) {
                    if (results[i].formatted_address) {
                        //console.log(results[i]);
                        output[i] = {};
                        output[i].lat = results[i].geometry.location.lat();
                        output[i].lng = results[i].geometry.location.lng();
                        output[i].address = results[i].formatted_address;
                        if (callback_single) {
                            return callback_single(output[i]);
                        }
                    }
                }
                if (callback) {
                    callback(output);
                }
            }
        });
    },
    map_instances: [],
    marker_instances: [],
    map_init: function(container) {
        Xcrud.map_instances = [];
        jQuery(container).find('.xcrud-map').each(function() {
            var cont = this;
            var point_field = jQuery(cont).parent().children('.xcrud-input');
            var search_field = jQuery(cont).parent().children('.xcrud-map-search');
            var point = Xcrud.parse_latlng(jQuery(point_field).val());
            var map = Xcrud.create_map(cont, point, jQuery(cont).data('zoom'), 'ROADMAP');
            var marker = Xcrud.place_marker(map, point, jQuery(cont).data('draggable'), jQuery(cont).data('text'), point_field);
            jQuery(point_field).on("keyup", function() {
                var point = Xcrud.parse_latlng(jQuery(point_field).val());
                Xcrud.move_marker(map, marker, point, jQuery(cont).data('draggable'), jQuery(cont).data('text'));
                return false;
            });
            if (jQuery(search_field).size()) {
                jQuery(search_field).on("keyup", function() {
                    var value = jQuery.trim(jQuery(search_field).val());
                    if (value) {
                        Xcrud.find_point(value, function(results) {
                            Xcrud.map_dropdown(search_field, results, map, marker, point_field, cont);
                        });
                    }
                    return false;
                });
            }
            Xcrud.map_instances.push(map);
            Xcrud.marker_instances.push(marker);
        });
    },
    map_dropdown: function(element, results, map, marker, point_field, cont) {
        var m_left = jQuery(element).outerWidth();
        var m_top = jQuery(element).outerHeight();
        var pos = jQuery(element).offset();
        jQuery(element).prev(".xcrud-map-dropdown").remove();
        if (results) {
            var list = '<ul class="xcrud-map-dropdown">';
            jQuery.map(results, function(value) {
                list += '<li data-val="' + value.lat + ',' + value.lng + '">' + value.address + '</li>';
            });
            list += '</ul>';
            jQuery(element).before(list);
            jQuery(element).prev(".xcrud-map-dropdown").offset(pos).css({
                "marginTop": m_top + "px",
                "minWidth": m_left + "px"
            }).children('li').on("click", function() {
                var point = Xcrud.parse_latlng(jQuery(this).data("val"));
                jQuery(element).val(jQuery(this).text());
                marker = Xcrud.move_marker(map, marker, point, jQuery(cont).data('draggable'), jQuery(cont).data('text'));
                jQuery(point_field).val(marker.getPosition().lat() + ',' + marker.getPosition().lng());
                jQuery(this).parent('ul').remove();
                return false;
            });
        }
    },
    map_resize_all: function() {
        if (jQuery(".xcrud-map").size() && Xcrud.map_instances.length) {
            for (i = 0; i < Xcrud.map_instances.length; i++) {
                var map = Xcrud.map_instances[i];
                var marker = Xcrud.marker_instances[i];
                google.maps.event.trigger(map, 'resize');
                map.setZoom(map.getZoom());
                map.setCenter(marker.position)
            }
        }
    },
    reload: function(selector_or_object) {
        if (!selector_or_object) {
            selector_or_object = 'body';
        }
        jQuery(selector_or_object).find(".xcrud-ajax").each(function() {
            Xcrud.request(this, Xcrud.list_data(this));
        });
    },
    bootstrap_modal: function(header, content) {
        jQuery("#xcrud-modal-window").remove();
        jQuery("body").append('<div id="xcrud-modal-window" class="modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>');
        jQuery("#xcrud-modal-window .modal-content").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">' + header + '</h4></div>');
        jQuery("#xcrud-modal-window .modal-content").append('<div class="modal-body">' + content + '</div>');
        jQuery("#xcrud-modal-window").modal();
        jQuery('#xcrud-modal-window [data-dismiss="modal"]').on("click",function(){
            jQuery("#xcrud-modal-window").modal('hide');
            if(jQuery(".simplemodal-close").size()){ // joomla trick
                jQuery(".simplemodal-close").trigger("click");
                jQuery("#xcrud-modal-window").remove();
            }
            return false;
        });
        jQuery('#xcrud-modal-window').on('hidden.bs.modal hidden', function() {
            jQuery("#xcrud-modal-window").remove();
        });
    },
    ui_modal: function(header, content) {
        jQuery("#xcrud-modal-window").remove();
        jQuery("body").append('<div id="xcrud-modal-window">' + content + '</div>');
        jQuery("#xcrud-modal-window").dialog({
            resizable: false,
            height: 'auto',
            width: 'auto',
            modal: true,
            closeOnEscape: true,
            close: function(event, ui) {
                jQuery("#xcrud-modal-window").remove();
            },
            title: header
        });
    },
    modal: function(header, content) {
        content = '<span>' + content + '</span>';
        if (typeof(jQuery.fn.modal) != 'undefined') {
            if(jQuery(content).first().prop("tagName") == 'IMG'){
                Xcrud.load_image(jQuery(content).first().attr('src'),function(imgObj){
                    Xcrud.bootstrap_modal(header, content);
                })
            }else{
                Xcrud.bootstrap_modal(header, content);
            }
        } else {
            if(jQuery(content).first().prop("tagName") == 'IMG'){
                Xcrud.load_image(jQuery(content).first().attr('src'),function(imgObj){
                    Xcrud.ui_modal(header, content);
                })
            }else{
                Xcrud.ui_modal(header, content);
            }
        }
    },
    init_tabs: function(container) {
        if (jQuery(container).find('.xcrud-tabs').size()) {
            if (typeof(jQuery.fn.tab) != 'undefined') {
                jQuery(container).find('.xcrud-tabs > ul:first > li > a').on("click", function() {
                    jQuery(this).tab('show');
                    return false;
                });
                jQuery('.xcrud .nav-tabs a').on('shown.bs.tab', function(e) {
                    Xcrud.map_resize_all();
                });
            } else {
                jQuery(container).find('.xcrud-tabs').tabs({
                    activate: function(event, ui) {
                        Xcrud.map_resize_all();
                    }
                });
            }
        }
    },
    init_tooltips: function(container) {
        if (jQuery(container).find('.xcrud-tooltip').size()) {
            jQuery(container).find('.xcrud-tooltip').tooltip();
        }
    },
    show_message: function(container, text, classname, delay) {
        if (container && text) {
            if (!classname) classname = 'info';
            if (!delay) delay = 7;
            var cont = jQuery(container).closest(".xcrud-container");
            jQuery(cont).children('.xcrud-message').stop(true, true).remove();
            jQuery(cont).append('<div class="xcrud-message ' + (classname ? classname : '') + '">' + text + '</div>');
            jQuery(cont).children('.xcrud-message').on("click", function() {
                jQuery(this).stop(true).slideUp(200, function() {
                    jQuery(this).remove();
                });
            }).slideDown().delay(delay * 1000).slideUp(200, function() {
                jQuery(this).remove();
            });
        }
    },
    check_message: function(container) {
        var status = 'success';
        var elements = jQuery(container).find(".xcrud-callback-message");
        if (jQuery(elements).size()) {
            elements.each(function() {
                var element = $(this);
                if (Xcrud.check_container(element, container)) {
                    Xcrud.show_message(container, element.val(), element.attr("name"));
                    if(element.attr("name") != 'success'){
                        var status = element.attr("name");
                    }
                    element.remove();
                }
            });
        }
        return status;
    }
}; /** events */
jQuery(document).on("ready xcrudreinit", function() {
    var $ = jQuery;
    if ($(".xcrud").size()) {
        $(".xcrud").on("change", ".xcrud-actionlist", function() {
            var container = Xcrud.get_container(this);
            var data = Xcrud.list_data(container);
            Xcrud.request(container, data);
        });
        $(".xcrud").on("change", ".xcrud-daterange", function() {
            var container = Xcrud.get_container(this);
            if ($(this).val()) {
                $(container).find(".xcrud-datepicker-from").datepicker("setDate", new Date(($(this).find('option:selected').data('from')  + new Date().getTimezoneOffset() * 60) * 1000));
                $(container).find(".xcrud-datepicker-to").datepicker("setDate", new Date(($(this).find('option:selected').data('to') + new Date().getTimezoneOffset() * 60) * 1000));
            } else {
                $(container).find(".xcrud-datepicker-from,.xcrud-datepicker-to").val('');
            }
        });
        $(".xcrud").on("change", ".xcrud-columns-select", function() {
            var container = Xcrud.get_container(this);
            var type = $(this).children("option:selected").data('type');
            var fieldname = $(this).children("option:selected").val();
            Xcrud.change_filter(type, container, fieldname);
        });
        $(".xcrud").on("click", ".xcrud-action", function() {
            var confirm_text = $(this).data('confirm');
            if (confirm_text && !window.confirm(confirm_text)) {
                return;
            } else {
                var container = Xcrud.get_container(this);
                var data = Xcrud.list_data(container, this);
                if ($(this).hasClass('xcrud-in-new-window')) {
                    Xcrud.new_window_request(container, data);
                } else {
                    if (data.task == 'save') {
                        if (!Xcrud.validation_error) {
                            Xcrud.unique_check(container, data, function(container) {
                                data.task = 'save';
                                Xcrud.request(container, data);
                            });
                        } else {
                            Xcrud.show_message(container, Xcrud.lang('validation_error'), 'error');
                        }
                    } else {
                        Xcrud.request(container, data);
                    }
                }
            }
            return false;
        });
        $(".xcrud").on("click", ".xcrud-toggle-show", function() {
            var container = $(this).closest(".xcrud").find(".xcrud-container:first");
            var closed = $(this).hasClass("xcrud-toggle-down");
            if (closed) {
                $(container).stop(true, true).delay(100).slideDown(200, function() {
                    $(document).trigger("xcrudslidedown");
                    $(container).trigger("xcrudslidedown");
                });
                //$(this).removeClass("xcrud-toggle-down");
                //$(this).addClass("xcrud-toggle-up");
                $(this).closest(".xcrud").find(".xcrud-main-tab").slideUp(200);
            } else {
                $(container).stop(true, true).slideUp(200, function() {
                    $(document).trigger("xcrudslideup");
                    $(container).trigger("xcrudslideup");
                });
                //$(this).removeClass("xcrud-toggle-up");
                //$(this).addClass("xcrud-toggle-down");
                $(this).closest(".xcrud").find(".xcrud-main-tab").delay(100).slideDown(200);
            }
            return false;
        });
        $(".xcrud").on("keypress", ".xcrud-input", function(e) {
            return Xcrud.pattern_callback(e, this);
        });
        $(".xcrud").on("click", ".xcrud-search-toggle", function() {
            $(this).hide(200);
            $(this).closest(".xcrud-ajax").find(".xcrud-search").show(200);
            return false;
        });
        $(".xcrud").on("keydown", ".xcrud-searchdata", function(e) {
            if (e.which == 13) {
                var container = Xcrud.get_container(this);
                var data = Xcrud.list_data(container);
                data.search = 1;
                Xcrud.request(container, data);
                return false;
            }
        });
        $(".xcrud").on("change", ".xcrud-upload", function() {
            var container = Xcrud.get_container(this);
            var data = Xcrud.list_data(container);
            Xcrud.upload_file(this, data, container);
            return false;
        });
        $(".xcrud").on("click", ".xcrud-remove-file", function() {
            var container = Xcrud.get_container(this);
            var data = Xcrud.list_data(container);
            Xcrud.remove_file(this, data, container);
            return false;
        });
        $(".xcrud").on("click", ".xcrud_modal", function() {
            var content = $(this).data("content");
            var header = $(this).data("header");
            Xcrud.modal(header, content);
            return false;
        });
        $(".xcrud-ajax").each(function() {
            Xcrud.init_datepicker(this);
            Xcrud.init_datepicker_range($(this).find('.xcrud-columns-select option:selected').data('type'), this);
            Xcrud.depend_init(this);
            Xcrud.map_init(this);
            Xcrud.check_fixed_buttons();
            Xcrud.init_tooltips(this);
            Xcrud.init_tabs(this);
            Xcrud.check_message(this);
            Xcrud.hide_progress(this);
        });
    }
});
jQuery(window).on("resize load xcrudslidetoggle", function() {
    Xcrud.check_fixed_buttons();
});
jQuery(window).on("load", function() {
    jQuery(".xcrud-ajax").each(function() {
        Xcrud.init_texteditor(this);
    });
});
jQuery(document).on("xcrudbeforerequest", function(event, container) {});
jQuery(document).on("xcrudafterrequest", function(event, container) {
    Xcrud.init_datepicker(container);
    Xcrud.init_texteditor(container);
    Xcrud.init_datepicker_range(jQuery(container).find('.xcrud-columns-select option:selected').data('type'), container);
    Xcrud.depend_init(container);
    Xcrud.map_init(container);
    Xcrud.check_fixed_buttons();
    Xcrud.init_tooltips(container);
    Xcrud.init_tabs(container);
});

//
/** print */
jQuery.extend({
    print_window: function(print_win, xcrud) {
        var data = {};
        jQuery(xcrud).find(".xcrud-data").each(function() {
            data[jQuery(this).attr("name")] = jQuery(this).val();
        });
        data.task = 'print';
        jQuery.ajax({
            data: data,
            success: function(out) {
                print_win.document.open();
                print_win.document.write(out);
                print_win.document.close();
                jQuery(xcrud).find(".xcrud-data[name=key]:first").val(jQuery(print_win.document).find(".xcrud-data[name=key]:first").val());
                var ua = navigator.userAgent.toLowerCase();
                if ((ua.indexOf("opera") != -1)) { // opera fix
                    jQuery(print_win).load(function() {
                        print_win.print();
                    });
                } else {
                    jQuery(print_win).ready(function() {
                        print_win.print();
                    });
                }
            }
        });
    }
});
//
/** upload */
jQuery.extend({
    createUploadIframe: function(id, uri) {
        var frameId = 'jUploadFrame' + id;
        var iframeHtml = '<iframe id="' + frameId + '" name="' + frameId + '" style="position:absolute; top:-9999px; left:-9999px"';
        if (window.ActiveXObject) {
            if (typeof uri == 'boolean') {
                iframeHtml += ' src="' + 'javascript:false' + '"';
            } else if (typeof uri == 'string') {
                iframeHtml += ' src="' + uri + '"';
            }
        }
        iframeHtml += ' />';
        jQuery(iframeHtml).appendTo(document.body);
        return jQuery('#' + frameId).get(0);
    },
    createUploadForm: function(id, fileElementId, data) {
        var formId = 'jUploadForm' + id;
        var fileId = 'jUploadFile' + id;
        var form = jQuery('<form  action="" method="POST" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>');
        if (data) {
            for (var i in data.xcrud) {
                if (data.xcrud[i] == 'postdata') {
/*for (var j in data.xcrud.postdata) {
                         jQuery('<input type="hidden" name="xcrud[postdata][' + j + ']" value="' + data.xcrud.postdata[j] + '" />').appendTo(form);
                     }*/
                } else
                jQuery('<input type="hidden" name="xcrud[' + i + ']" value="' + data.xcrud[i] + '" />').appendTo(form);
            }
        }
        var oldElement = jQuery('#' + fileElementId);
        var newElement = jQuery(oldElement).clone();
        jQuery(oldElement).attr('id', fileId);
        jQuery(oldElement).before(newElement);
        jQuery(oldElement).appendTo(form);
        jQuery(form).css('position', 'absolute');
        jQuery(form).css('top', '-1200px');
        jQuery(form).css('left', '-1200px');
        jQuery(form).appendTo('body');
        return form;
    },
    ajaxFileUpload: function(s) {
        s = jQuery.extend({}, jQuery.ajaxSettings, s);
        var id = new Date().getTime();
        var form = jQuery.createUploadForm(id, s.fileElementId, (typeof(s.data) == 'undefined' ? false : s.data));
        var io = jQuery.createUploadIframe(id, s.secureuri);
        var frameId = 'jUploadFrame' + id;
        var formId = 'jUploadForm' + id;
        if (s.global && !jQuery.active++) {
            jQuery.event.trigger("ajaxStart");
        }
        var requestDone = false;
        var xml = {};
        if (s.global) jQuery.event.trigger("ajaxSend", [xml, s]);
        var uploadCallback = function(isTimeout) {
            var io = document.getElementById(frameId);
            try {
                if (io.contentWindow) {
                    xml.responseText = io.contentWindow.document.body ? io.contentWindow.document.body.innerHTML : null;
                    xml.responseXML = io.contentWindow.document.XMLDocument ? io.contentWindow.document.XMLDocument : io.contentWindow.document;
                } else if (io.contentDocument) {
                    xml.responseText = io.contentDocument.document.body ? io.contentDocument.document.body.innerHTML : null;
                    xml.responseXML = io.contentDocument.document.XMLDocument ? io.contentDocument.document.XMLDocument : io.contentDocument.document;
                }
            } catch (e) {}
            if (xml || isTimeout == "timeout") {
                requestDone = true;
                var status;
                try {
                    status = isTimeout != "timeout" ? "success" : "error";
                    if (status != "error") {
                        var data = jQuery.uploadHttpData(xml, s.dataType);
                        if (s.success) s.success(data, status);
                        if (s.global) jQuery.event.trigger("ajaxSuccess", [xml, s]);
                    } else {}
                } catch (e) {
                    status = "error";
                }
                if (s.global) jQuery.event.trigger("ajaxComplete", [xml, s]);
                if (s.global && !--jQuery.active) jQuery.event.trigger("ajaxStop");
                if (s.complete) s.complete(xml, status);
                jQuery(io).unbind();
                setTimeout(function() {
                    try {
                        jQuery(io).remove();
                        jQuery(form).remove();
                    } catch (e) {}
                }, 100);
                xml = null
            }
        };
        if (s.timeout > 0) {
            setTimeout(function() {
                if (!requestDone) uploadCallback("timeout");
            }, s.timeout);
        }
        try {
            var form = jQuery('#' + formId);
            jQuery(form).attr('action', s.url);
            jQuery(form).attr('method', 'POST');
            jQuery(form).attr('target', frameId);
            if (form.encoding) {
                jQuery(form).attr('encoding', 'multipart/form-data');
            } else {
                jQuery(form).attr('enctype', 'multipart/form-data');
            }
            jQuery(form).submit();
        } catch (e) {}
        var ttt = 0;
        var ua = navigator.userAgent.toLowerCase();
        if ((ua.indexOf("opera") != -1)) { // opera fix
            jQuery('#' + frameId).load(function() {
                ttt++;
                if (ttt == 2) {
                    uploadCallback();
                }
            });
        } else {
            jQuery('#' + frameId).on("load", uploadCallback);
        }
        return {
            abort: function() {}
        };
    },
    uploadHttpData: function(r, type) {
        data = (type == "xml" && !type) ? r.responseXML : r.responseText;
        if (type == "script") jQuery.globalEval(data);
        if (type == "json") eval("data = " + data);
        return data;
    }
});