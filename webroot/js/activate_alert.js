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
//        var container =  $('.dialogs');
        $.post(
                form.attr('action'),
                form.serialize()
            ).done(function(data) {
                console.log(data);
                form.parent().parent().html(data);
            }).fail(function () {
            });



//        var container =  $('.dialogs');
//        var submitted = container.data('wasSubmitted');
//        container.data('wasSubmitted', true);
//
//        $.post(
//                form.attr('action'),
//                form.serialize()
//            ).done(function(data) {
//                var list = container.data('targetList');
//
//                //if we don't get info about edited item
//                //we display form with error messages
//                if (!data.id) {
//                    form.parent().parent().html(data);
//                    return;
//                }
//
//                if (container.data('isNewItem')) {
//                    addItemToList(list, data);
//                } else {
//                    updateItemInList(list, data);
//                }
//                form.closest('.dialog').dialog("close");
//
//            }).fail(function () {
//                container.data('wasSubmitted', false);
//            });
}

