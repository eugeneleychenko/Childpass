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

    function deleteRelative(button) {
        if (!confirm('Do you want to remove this relative?')) {
            return false;
        }

        var button = $(this);
        var row = button.closest('.tr');

        var childRelativeIdElement = row.find('.child_relation_id');
        if (!childRelativeIdElement.length || !childRelativeIdElement.value) {
            row.remove();
            return false;
        }

        var relativeIdElement = row.find('relative_id');
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

