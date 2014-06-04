$(function() {
   $('.activateAlert').click(function(event) {
       event.preventDefault();
       showActiveAlertDialog();
   });

   function  showActiveAlertDialog() {
       $.get('/child/activateAlert/',
             {
                 'time' : new Date().getTime()
             }).done(function (data) {
               var dialog = $('#activateAlertDialog');
               dialog.html(data);
               dialog.dialog({
                   modal: true,
                   resizable: false,
                   buttons : {
                       'Save'   : function () {
                       }
                   },

                   open: function() {
                   },
                   close : function() {
                   }
               });

           })
       }
});
