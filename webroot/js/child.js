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
    $('#relatives_table tbody').on('click', '.delete_relative', function() {
        deleteRelative($(this));
        return false;
    });

    $('#add_relative').on('click', function() {
        addNewRelative();
    });

    function addNewRelative() {
//       $('#relatives_table').
//       var row = '<tr><td><input type="text" value="Bill" name="Relative[0][first_name]" id="Relative_0_first_name"></td><td><input type="text" value="Smith" name="Relative[0][last_name]" id="Relative_0_last_name"></td><td><select ="select="" relation"="" name="Relative[0][relation]" id="Relative_0_relation"> \
//            <option value="1" selected="selected">Father</option>\
//            <option value="2">Mother</option>\
//            <option value="3">Grandfather</option>\
//            <option value="4">GrandMother</option>\
//            <option value="5">Uncle</option>\
//            <option value="6">Aunt</option>\
//         </select></td><td><button type="button" class="button small delete_relative">-</button><input class="relative_id" type="hidden" value="0" name="Relative[0][relative_id]" id="Relative_0_relative_id"><input class="child_relative_id" type="hidden" value="0" name="Relative[0][childRelationId]" id="Relative_0_childRelationId"></td></tr>';
//
    }




    function deleteRelative(button) {
        if (!confirm('Do you want to remove this relative?')) {
            return false;
        }

        var row = button.closest('tr');


        var childRelativeIdElement = row.find('.child_relative_id');
        console.log(childRelativeIdElement);

        if (!childRelativeIdElement.length || !childRelativeIdElement.val()) {
            row.remove();
            return false;
        }

        var relativeIdElement = row.find('.relative_id');
        var relativeId = relativeIdElement.val();
        console.log(relativeId);
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
                row.remove();
            },
            complete: function() {
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        });




}

//    function deleteEmployee(id) {
//        if (!confirm('Do you want to remove this employee?')) {
//            return false;
//        }
//
//        $.ajax({
//            url: '/permission/delete-employee/',
//            data: {'id' : id},
//            type: 'DELETE',
//            success: function(data) {
//                $('#employees_grid').find('#employee_' + id).remove();
//            },
//            complete: function() {
//                deleteDetailsRow();
//            },
//            error: function(xhr, status, error) {
//                alert(xhr.responseText);
//            }
//        });
//    }


});

