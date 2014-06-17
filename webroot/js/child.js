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
    if (confirm("Do you really want to delete this photo?")) {
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

    if (typeof step !== 'undefined') {
        if (step == 'step1') {
            $('form').validate({
                ignore: '#Child_birthday',
                wrapper: 'div',
                errorLabelContainer: "#messageBox"
            });
        }
        $('form').submit(function( event ) {
            if (step == 'step1' && !$('#relatives_table tbody tr').length) {
                if (!$('#relatives_error').length) {
                    $('<div/>', {
                            'class': 'errorMessage',
                            'id': 'relatives_error'
                      }).text('Input at least one relative!').insertBefore($('#relatives_table'));
                }
                event.preventDefault();
            }
        });

    }

    relationOptions = $('select.relation_id').first().find('option');

    $('#relatives_table tbody').on('click', '.delete_relative', function() {
        deleteRelative($(this));
        return false;
    });

    $('#add_relative').on('click', function() {
        addNewRelative();
    });

    function addNewRelative() {
        var namePrefix = getNamePrefix();
        var selectRelationElement = createRelationsElement(namePrefix, '');
        var firstNameElement = $('<input/>', {
                name: namePrefix + '[first_name]',
                required: 'required',
                maxLength: '100'
        });
        var lastNameElement = $('<input/>', {
                name: namePrefix + '[last_name]',
                required: 'required',
                maxLength: '100'
        });

        var deleteRelativeButton = $('<button/>', {
                'type': 'type',
                'class': 'button small delete_relative'
        }).text('-');


        var row = $('<tr/>').append([
                $('<td/>').append(firstNameElement),
                $('<td/>').append(lastNameElement),
                $('<td/>').append(selectRelationElement),
                $('<td/>').append( createDeleteRelativeButton() )
                ]);

        $('#relatives_table').append(row);
        relativesNumber++;
    }

    $('#add_saved_relatives').on('click', function() {
        prefillWithSavedRelatives();
    });

    function prefillWithSavedRelatives() {
        $.ajax({
            url: Yii.app.createUrl('child/get-saved-relatives'),
            type: 'GET',
            success: function(data) {
               var rows = prefilledRelativeRows(data);

               if (rows) {
                   $('#relatives_table tbody').append(rows);
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
       var row;
       for (var i = 0; i < length; i++) {
           if (relativeExistsOnTheForm(relativesInfo[i]['relative_id'])) {
               continue;
           }

           row = prefilledRelativeRow(relativesInfo[i]);
           rows += row;
           relativesNumber++;
       }
       return rows;
    }

    function relativeExistsOnTheForm(relativeId) {
        return ($('.relative_id[value="' + relativeId + '"]').length > 0);
    }

    function prefilledRelativeRow(relativeInfo) {
        var namePrefix = getNamePrefix();

        var row = $('<tr/>');
        row.append([
                $('<td/>').append( createFirstNameElement(namePrefix, relativeInfo['first_name']) ),
                $('<td/>').append( createLastNameElement(namePrefix, relativeInfo['last_name']) ),
                $('<td/>').append( createRelationsElement(namePrefix, relativeInfo['relation_id']) ),
                $('<td/>').append([
                        createDeleteRelativeButton(),
                        createRelativeIdElement(namePrefix, relativeInfo['relative_id'])
                        ])
        ]);
        return row.clone().wrap('<div>').parent().html();
    }


    function createRelationsElement(namePrefix, relationId) {
        var options = [];
        var selected;
        relationOptions.each(function() {
            var element = $(this);

            if (element.val() ==  relationId) {
                selected = 'selected';
            } else {
                selected = false;
            }
            options.push($('<option/>', {
                    value: element.val(),
                    selected: selected
            }).text(element.text()));
        });

        return $('<select/>', {
                required: 'required',
                'class': 'relation_id',
                name: namePrefix + '[relation_id]'
            }).append(options);
    }


    function createFirstNameElement(namePrefix, value) {
        return $('<input/>', {
                name: namePrefix + '[first_name]',
                required: 'required',
                maxLength: '100',
                value: typeof value !== 'undefined' ? value : ''
        });
    }

    function createLastNameElement(namePrefix, value) {
        return $('<input/>', {
                name: namePrefix + '[last_name]',
                required: 'required',
                maxLength: '100',
                value: typeof value !== 'undefined' ? value : ''
        });
    }

    function createDeleteRelativeButton() {
        return $('<button/>', {
                type: 'type',
                'class': 'button small delete_relative'
        }).text('-');
    }

    function createRelativeIdElement(namePrefix, relativeId) {
        return $('<input/>', {
                type: 'hidden',
                value: relativeId,
                name: namePrefix + '[relative_id]'
        });
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
            url: Yii.app.createUrl('child/delete-relative-mapping'),
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

