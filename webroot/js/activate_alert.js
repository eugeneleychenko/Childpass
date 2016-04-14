$(function() {
    if (typeof openAlertDialog !== 'undefined' && openAlertDialog) {
        showActiveAlertDialog();
    }

   $('.activateAlert').click(function(event) {
       event.preventDefault();
       showActiveAlertDialog();
   });

    $('#activateAlertDialog').on('change', '.child_checkbox', function() {
        var checkbox = $(this);
        var rowChildDescription = checkbox.closest('.row').next();

        if (checkbox.is(':checked')) {
            sessionStorage.setItem($(this).attr('name'), true);
            rowChildDescription.show();
        } else {
            sessionStorage.setItem($(this).attr('name'), false);
            rowChildDescription.hide();
        }
    });

    $('#activateAlertDialog').on('change', '#description', function() {
        sessionStorage.setItem('description', $(this).val())
    });

    $('#activateAlertDialog').on('change', '#date', function() {
        sessionStorage.setItem('date', $(this).val())
    });


    $('#activateAlertDialog').on('click', '#cancelAlert', function() {
        $('#activateAlertDialog').dialog("close");
    });

   function  showActiveAlertDialog() {
       setUrlWithDialog();
       $.get(Yii.app.createUrl('child/activate-alert'),
             {
                 'time' : new Date().getTime()
             }).done(function (data) {
               var dialog = $('#activateAlertDialog');
               dialog.html(data);
               dialog.dialog({
                   width: '700px',
                   modal: true,
                   resizable: false,
                   open: function() {
                       restoreValues();

                       $('#activateAlertDialog #date').datetimepicker({
                           dateFormat: 'yy-mm-dd',
                           timeFormat: 'HH:mm:ss'
                        });
                   },
                   close : function() {
                       resetUrl();
                       sessionStorage.clear();
                       window.location = Yii.app.createUrl('child/list');
                   }
               });
               //$(".ui-dialog-titlebar").hide();
           })
       }
});

function restoreValues() {
    $('#activateAlertDialog').find('.child_checkbox').each( function (index, element) {
        var $element = $(element);
        if (sessionStorage.getItem($element.attr('name'))) {
            $element.prop('checked', true);
            $element.change();
        }
    });

    if (sessionStorage.getItem("description")) {
        $('#activateAlertDialog').find('#description').val(sessionStorage.getItem("description"));
    }
    if (sessionStorage.getItem("date")) {
        $('#activateAlertDialog').find('#date').val(sessionStorage.getItem("date"));
    }
}

function setUrlWithDialog() {
    var url = window.location.href;
    if (!url.match(/.*openAlertDialog$/)) {
        var sep = '';
        if (window.location.search === '') {
            sep = '?';
        } else {
            sep = '&';
        }
        url += sep + 'openAlertDialog';
    }
    window.history.replaceState(null, null, url);
}

function resetUrl() {
    var url = window.location.href;
    if (url.match(/.*openAlertDialog$/)) {
        url = url.replace(/.openAlertDialog/, '');
    }
    window.history.replaceState(null, null, url);
}

function submitForm(form, data, hasError) {
    if (!$('.child_checkbox:checked').length) {
        alert('Select at least one child!');
        return;
    }

    if (!$('#activateAlertDialog').find('#description').val() || !$('#activateAlertDialog').find('#date').val()) {
        alert('Please specify incident description and date.');
        return;
    }

    $('#activateAlertDialog').find('#alert_submit').val('Uploading...');

    resetUrl();
    sessionStorage.clear();

    $.post(
            form.attr('action'),
            form.serialize()
        ).done(function(data) {
            form.parent().parent().html(data);
            $('#activateAlertDialog #date').datetimepicker({
                dateFormat: 'yy-mm-dd',
                timeFormat: 'HH:mm:ss'
            });
        }).fail(function () {
        });
}

