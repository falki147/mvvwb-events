require("daterangepicker");
var moment = require("moment");
require("moment-timezone");

/**
 * Initializes the datepicker for a text input field
 * @param {HTMLElement} element
 * @param {HTMLElement} dataElement the element where the initial data is taken from
 */
function initializeDatePicker(element, dataElement) {
    jQuery(element).daterangepicker({
        startDate: moment.tz(dataElement.value, dataElement.getAttribute("data-timezone")),
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
        autoApply: true,
        locale: {
            "format": "DD.MM.YYYY HH:mm",
            "applyLabel": "Übernehmen",
            "cancelLabel": "Abbrechen",
            "weekLabel": "W",
            "daysOfWeek": [
                "So",
                "Mo",
                "Di",
                "Mi",
                "Do",
                "Fr",
                "Sa"
            ],
            "monthNames": [
                "Januar",
                "Februar",
                "März",
                "April",
                "Mai",
                "Juni",
                "Juli",
                "August",
                "September",
                "Oktober",
                "November",
                "Dezember"
            ],
            "firstDay": 1
        }
    }, function (time) {
        dataElement.value = time.format();
    });
}

/**
 * Initializes all fields with datepickers which have the mvvwb-events-datepicker class set
 */
function initializeAllDatePickers() {
    document.addEventListener("DOMContentLoaded", function() {
        var datepicker = document.getElementsByClassName("mvvwb-events-datepicker");

        for (var i = 0; i < datepicker.length; ++i) {
            var name = datepicker[i].getAttribute("data-name");
            var elements = document.getElementsByName(name);

            if (elements.length === 0)
                continue;

            initializeDatePicker(datepicker[i], elements[0]);
        }
    });
}

/**
 * Initializes the event block in the editor
 * @param {*} blocks 
 * @param {*} element 
 * @param {*} components 
 */
function initializeBlock(blocks, element, components) {
    blocks.registerBlockType("mvvwb/events", {
        title: "Events",
        icon: "calendar",
        category: "widgets",
        edit: function(props) {
            return element.createElement(components.ServerSideRender, {
                block: "mvvwb/events",
                attributes: props.attributes
            });
        },
        save: function() { }
    });
}

if (window.wp.blocks && window.wp.element && window.wp.components)
    initializeBlock(window.wp.blocks, window.wp.element, window.wp.components);

initializeAllDatePickers();
