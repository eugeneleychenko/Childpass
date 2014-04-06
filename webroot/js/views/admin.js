var sortOrder = '';

$(document).ready(function () {
    $('.approveAction').click(function () {
        var itemId = $(this).closest('.intern').attr('data-itemId');
        var button = $(this);

        $.ajax({
            url: Yii.app.createUrl('admin/approveIntern'),
            data: {'itemId': itemId},
            type: 'PUT',
            success: function (data) {
                button.closest('.intern').addClass('approved').removeClass('deleted');
                button.replaceWith("<span>Done</span>");
            },
            error: function (data) {
                alert('Ooops! Error occured: ' + data.responseText);
                button.removeAttr('disabled');
            }
        });

        $(this).attr('disabled', 'disabled');


        return false;
    });


    $('.deleteAction').click(function () {
        var itemId = $(this).closest('.intern').attr('data-itemId');
        var button = $(this);

        $.ajax({
            url: Yii.app.createUrl('admin/deleteIntern'),
            data: {'itemId': itemId},
            type: 'DELETE',
            success: function (data) {
                button.closest('.intern').addClass('deleted').removeClass('approved');
                button.replaceWith("<span>Done</span>");
            },
            error: function (data) {
                alert('Ooops! Error occured: ' + data.responseText);
                button.removeAttr('disabled');
            }
        });

        $(this).attr('disabled', 'disabled');

        return false;
    });

    $(".basic").jRating({
        step: true,
        length: 5, // nb of stars
        bigStarsPath: Yii.app.baseUrl + '/libs/jRating/jquery/icons/stars.png',
        smallStarsPath: Yii.app.baseUrl + '/images/star-small.png',
        sendRequest: false,
        showRateInfo: false,
        type: 'small',
        rateMax: 5,
        onClick: function (element, rate) {
            var itemId = $(element).closest('.intern').attr('data-itemId');

            $.ajax({
                url: Yii.app.createUrl('admin/rateIntern'),
                data: { 'itemId': itemId, 'rate': rate },
                type: 'PUT',
                error: function (data) {
                    alert('Ooops! Error occured: ' + data.responseText);
                }
            });
        }
    });

    $('.internsList').on('click', '.acceptAction',function () {
        var itemId = $(this).closest('.intern').attr('data-itemId');
        var button = $(this);

        $.ajax({
            url: Yii.app.createUrl('admin/acceptIntern'),
            data: {'internId': itemId},
            type: 'PUT',
            success: function (data) {
                button.closest('.intern').addClass('accepted');
                button.removeClass('acceptAction').addClass('unacceptAction');
            },
            error: function (data) {
                alert('Ooops! Error occured: ' + data.responseText);
            },
            complete: function () {
                button.removeAttr('disabled');
            }
        });

        $(this).attr('disabled', 'disabled');

        return false;
    }).on('click', '.unacceptAction', function () {
        var itemId = $(this).closest('.intern').attr('data-itemId');
        var button = $(this);

        $.ajax({
            url: Yii.app.createUrl('admin/unAcceptIntern'),
            data: {'internId': itemId},
            type: 'PUT',
            success: function (data) {
                button.closest('.intern').removeClass('accepted');
                button.removeClass('unacceptAction').addClass('acceptAction');
            },
            error: function (data) {
                alert('Ooops! Error occured: ' + data.responseText);
            },
            complete: function () {
                button.removeAttr('disabled');
            }
        });

        $(this).attr('disabled', 'disabled');

        return false;
    });

    $('.internsList .headerRow .checkmarkColumn input').click(function () {
        var value = this.checked;
        $('.internsList .intern .mainData .checkmarkColumn input').attr('checked', value == true);
    });

    $('.internsList .headerRow .columns[data-fieldName]').click(function () {
        var fieldName = $(this).attr('data-fieldName');
        var sortOrder = $('input[name=sort_order]', '#admin-dashboard-filter-form');
        if (sortOrder.val() === fieldName + ' ASC') {
            sortOrder.val(fieldName + ' DESC');
        } else {
            sortOrder.val(fieldName + ' ASC');
        }

        $('#admin-dashboard-filter-form').find('input[type=submit]').click();
    });


    function showSortMark() {
        var sortOrder = $('input[name=sort_order]', '#admin-dashboard-filter-form').val();
        if (!sortOrder) {
            return;
        }

        sortOrder = sortOrder.split(' ');
        $('.internsList .headerRow .columns[data-fieldName=' + sortOrder[0] + ']').addClass('sorted-' + sortOrder[1]);
    }

    showSortMark();

    $('form.bulkActions').submit(function (event) {
        var selectedItems = [];
        var button = $(this).find('input[type=submit]');

        $('.internsList .intern .checkmarkColumn [type=checkbox]:checked').each(function() {
            selectedItems.push($(this).closest('.intern').attr('data-itemId'));
        });

        if (selectedItems.length == 0) {
            event.preventDefault();
            return;
        }

        var action = $(this).find('[name=action]').val();

        if (action == 'approve') {
            event.preventDefault();
            bulkApproveInterns(selectedItems, button);
        } else if (action == 'export')  {
            exportInternsToCSV(selectedItems, this);
        } else {
            event.preventDefault();
        }
    });

    function bulkApproveInterns(items, control) {
        $.ajax({
            url: Yii.app.createUrl('admin/bulk-approve-interns'),
            data: {itemsIds : items},
            type: 'PUT',
            success: function (data) {
                window.location.reload();
            },
            error: function (data) {
                alert('Ooops! Error occured: ' + data.responseText);
            },
            complete: function () {
                control.removeAttr('disabled');
            }
        });

        $(control).attr('disabled', 'disabled');
    }

    function exportInternsToCSV(items, form) {
        form.action = Yii.app.createUrl('admin/export-interns-to-csv');

        $(form).find('[name=internsIds]').val(items.join(','));
    }

});