$(function() {

   $('.activateAlert').click(function(event) {
       event.preventDefault();
       showActiveAlertDialog();
   });

    $('#activateAlertDialog').on('change', '.child_checkbox', function() {
        var checkbox = $(this);
        var rowChildDescription = checkbox.closest('.row').next();

        if (checkbox.is(':checked')) {
            rowChildDescription.show();
        } else {
            rowChildDescription.hide();
        }
    });


    $('#activateAlertDialog').on('click', '#cancelAlert', function() {
        $('#activateAlertDialog').dialog("close");
    });


   function  showActiveAlertDialog() {
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
                       $('#activateAlertDialog #date').datetimepicker({
                           dateFormat: 'yy-mm-dd',
                           timeFormat: 'HH:mm:ss'
                        });
                   },
                   close : function() {
                       window.location = Yii.app.createUrl('child/list');
                   }
               });
               $(".ui-dialog-titlebar").hide();
           })
       }
});

function submitForm(form, data, hasError) {
    if (!$('.child_checkbox:checked').length) {
        alert('Select at least one child!');
        return;
    }

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

