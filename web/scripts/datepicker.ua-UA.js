(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define('datepicker.ua', ['jquery'], factory);
    } else if (typeof exports === 'object') {
        factory(require('jquery'));
    } else {
        factory(jQuery);
    }
})(function ($) {

    'use strict';

    $.fn.datepicker.languages['ua-UA'] = {
        days: ['Неділя', 'Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П`ятниця', 'Субота'],
        daysShort: ['Нд', 'Пн', 'Вт', 'Сер', 'Чт', 'Пт', 'Сб'],
        daysMin: ['Нд', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        months: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'],
        monthsShort: ['Січ', 'Лют', 'Бер', 'Квіт', 'Трав', 'Черв', 'Лип', 'Серп', 'Вер', 'Жовт', 'Лист', 'Груд'],
        today: 'Сьогодні',
        clear: 'Очистити',
        format: 'yyyy-mm-dd',
        weekStart: 1
    };
});