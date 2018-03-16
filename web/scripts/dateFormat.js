$(function () {
    var options = {
        minDate: new Date(),
        todayButton: new Date(),
        autoHide: true,
        language: "ua-UA"
    };

    $("input[name=date]").datepicker(options).on('pick.datepicker', function (e) {
        e.preventDefault();
        $(this).val(e.date.getDate() + ' ' + getMonthName(e.date.getMonth()) + ' ' + e.date.getFullYear());
    });

    $(".route-costs").datepicker(options).on('pick.datepicker', function (e) {
        var dateObj = e.date,
            formObj = $(this).find('form');

        e.preventDefault();

        formObj.find("input[name=date]").val(dateObj.getDate() + ' ' + getMonthName(dateObj.getMonth()) + ' ' + e.date.getFullYear());
        formObj.submit();
    });

    function getMonthName(month) {
        var months = ['Січня', 'Лютого', 'Березня', 'Квітня', 'Травня', 'Червня', 'Липня', 'Серпня', 'Вересня', 'Жовтня', 'Листопада', 'Грудня'];

        return months[month];
    }
});