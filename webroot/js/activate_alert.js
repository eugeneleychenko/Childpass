$(function() {
   $('.activateAlert').click(function(event) {
       event.preventDefault();
       showActiveAlertDialog();
   });

    $('#activateAlertDialog').on('change', '.child_checkbox', function() {
        var checkbox = $(this);
        var rowChildDescription = checkbox.closest('.row').next()
        if (checkbox.is(':checked')) {
            rowChildDescription.show();
        } else {
            rowChildDescription.hide();
        }
    });


   function  showActiveAlertDialog() {
       $.get('/child/activateAlert/',
             {
                 'time' : new Date().getTime()
             }).done(function (data) {
               var dialog = $('#activateAlertDialog');
               dialog.html(data);
               dialog.dialog({
                   width: '20%',
                   modal: true,
                   resizable: false,
                   open: function() {
                   },
                   close : function() {
                   }
               });

           })
       }


});

function submitForm(form, data, hasError) {
    if (!$('.child_checkbox:checked').length) {
        alert('Select at least on child!');
        return;
    }



        $.post(
                form.attr('action'),
                form.serialize()
            ).done(function(data) {
                console.log(data);
                form.parent().parent().html(data);
            }).fail(function () {
            });
}

