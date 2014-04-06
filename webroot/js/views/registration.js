$(document).ready(function () {
    $('input[type=file]').change(function() {
        var fileName = (typeof(this.files) != 'undefined') ? this.files[0].name : this.value;

        $(this).closest('.fileUploadWrapper').find('.fileName').text(fileName);
    });
});