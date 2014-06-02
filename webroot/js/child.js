var relationOptions;
function afterSelect(e, v, m) {
    $('.MultiFile-remove').text('');
    var fileSize = e.files[0].size;
    if(fileSize > 1024*1024*10) {
        alert("Exceeds file upload limit 10MB");
        $(".MultiFile-remove:last").click();
    }
    return true;
}

function deleteChildPhoto(button) {
    button = $(button);
    var photo_item = button.closest('.child-photo');
    if (confirm("You really want to delete this photo?")) {
        $.ajax({
            url: Yii.app.createUrl('child-photo/delete'),
            data: {'photo_id': photo_item.attr('data-photo_id')},
            type: 'POST',
            success: function (response) {
                if(response.success == true) {
                    photo_item.remove();
                } else {
                    alert('Error occurred. Please try again.');
                }
            },
            error: function (data) {
                alert('Error occurred. Please try again.');
            }
        });
    }
}

$(function() {
    relationOptions = $('select.relation_id').first().find('option');

    $('#relatives_table tbody').on('click', '.delete_relative', function() {
        deleteRelative($(this));
        return false;
    });

    $('#add_relative').on('click', function() {
        addNewRelative();
    });

    function addNewRelative() {
        var namePrefix = 'Relative[' + relativesNumber  + ']';
        var  select = $('#relatives_table tbody').find('tr').first().find('select').clone();
        select.removeAttr('id');
        select.attr('name', namePrefix + '[relation_id]');
        select.val('');
        var row =  '<tr><td><input name="' + namePrefix + '[first_name]" required maxlength="100"></td><td><input name="' + namePrefix + '[last_name]" required maxlength="100"> </td><td>' + select.text() + '</td><td><button type="type" class="button small delete_relative">-</button></td></tr>';
        $('#relatives_table').append(row);
        $('#relatives_table tbody').find('tr').last().find('td').eq(2).html(select);
        relativesNumber++;
    }

    $('#add_saved_relatives').on('click', function() {
        prefillWithSavedRelatives();
    });

    function prefillWithSavedRelatives() {
        $.ajax({
            url: '/child/GetSavedRelatives',
            type: 'GET',
            success: function(data) {
               var rows = prefilledRelativeRows(data);
               if (rows) {
                   $('#relatives_table').append(rows);
               }
            },
            complete: function() {
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        });
    }

    function prefilledRelativeRows(relativesInfo) {
       var length = relativesInfo.length;
       var rows = '';
       for (var i = 0; i < length; i++) {
           if (relativeExistsOnTheForm(relativesInfo['relative_id'])) {
               console.log('already exists');
               continue;
           }

           rows += prefilledRelativeRow(relativesInfo[i]);
           relativesNumber++;
       }
       return rows;
    }

    function relativeExistsOnTheForm(relativeId) {
        return ($('.relation_id[value="' + relativeId + '"]').length > 0);
        //return $('Relative_' + relativeId + '_relative_id').length > 0;
    }

    function prefilledRelativeRow(relativeInfo) {
        var options = '';
        relationOptions.each(function() {
           var element = $(this);
           options +=  '<option value="' + element.val() + '">' + element.text() + '</option>';
        });
        var namePrefix = getNamePrefix();//'Relative[' + relativesNumber  + ']';
        var select = '<select required="required" class="relation_id" name="' + namePrefix + '[relation_id]">' + options + '</select>';

        return  '<tr><td>' + firstNameElement(namePrefix, relativeInfo['first_name']) + '</td><td>' + lastNameElement(namePrefix, relativeInfo['last_name']) + '</td><td>' + select + '</td><td>' + deleteRelativeButton() + relativeIdElement(namePrefix, relativeInfo['relative_id']) + '</td></tr>';
    }

    function firstNameElement(namePrefix, value) {
        var value = typeof value !== 'undefined' ? 'value="' + value + '"' : '';
        return '<input name="' + namePrefix + '[first_name]" required maxlength="100" ' +  value  +  '">';
    }

    function lastNameElement(namePrefix, value) {
        var value = typeof value !== 'undefined' ? 'value="' + value + '"' : '';
        return '<input name="' + namePrefix + '[last_name]" required maxlength="100" ' +  value  +  '">';
    }

    function deleteRelativeButton() {
        return '<button type="type" class="button small delete_relative">-</button>';
    }

    function relativeIdElement(namePrefix, relativeId) {
        return  '<input class="relative_id" type="hidden" value="'  + relativeId  + '" name="' + namePrefix + '[relative_id]">';
    }

    function getNamePrefix() {
        return 'Relative[' + relativesNumber  + ']';
    }


    function deleteRelative(button) {
        if (!confirm('Do you want to remove this relative?')) {
            return false;
        }

        var row = button.closest('tr');

        var childRelativeIdElement = row.find('.child_relative_id');


        if (!childRelativeIdElement.length || !childRelativeIdElement.val()) {
            row.remove();
            relativesNumber--;
            return false;
        }

        var relativeIdElement = row.find('.relative_id');
        var relativeId = relativeIdElement.val();
        if (!relativeId) {
            return false;
        }

        $.ajax({
            url: '/child/deleteRelativeMapping',
            data: {
                'childId' : childId,
                'relativeId' : relativeId
            },
            type: 'DELETE',
            success: function(data) {
                if (data) {
                    row.remove();
                    relativesNumber--;
                } else {
                  alert('Failed to delete! Make sure child has at least one relative!');
                }
            },
            complete: function() {
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        });
}

});

