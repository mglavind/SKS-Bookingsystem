$(document).ready(function() {
    $(document).on('click', '#send_materialebestillings_mail', function() {
        copyStringToClipboard($(this).data('copy'));
        alert("Mailen er blevet kopieret, da den er for lang til at blive sat ind automatisk");
    });
    function copyStringToClipboard(str) {
        // Create new element
        var el = document.createElement('textarea');
        // Set value (string to be copied)
        el.value = str;
        // Set non-editable to avoid focus and move outside of view
        el.setAttribute('readonly', '');
        el.style = { position: 'absolute', left: '-9999px' };
        document.body.appendChild(el);
        // Select text inside element
        el.select();
        // Copy text to clipboard
        document.execCommand('copy');
        // Remove temporary element
        document.body.removeChild(el);
    }
    $('.modal').on('shown.bs.modal', function() {
        $(this)
            .find('.in-focus')
            .focus();
    });
    $(document).on('click', '.ret', function() {
        $('td.editor').toggleClass('show');
        $(this)
            .parent()
            .children()
            .toggle();
    });
    $(document).on('click', '.gem', function() {
        $('td.editor:not(show)').each(function() {
            $(this).html(
                $(this)
                    .siblings('td.editor.show')
                    .children()
                    .val(),
            );
        });
        //            $("#active_form").submit();
        $('td.editor').toggleClass('show');
        $(this)
            .parent()
            .children()
            .toggle();
    });
    $(document).on('click', '.tableRet', function() {
        var tr = $(this)
            .parent()
            .parent()
            .parent();
        tr.children()
            .children('span.vis')
            .removeClass('show');
        tr.children()
            .children('span.edit')
            .addClass('show');
        tr.find('input')
            .first()
            .focus();
    });
    $(document).on('click', '.tableGem', function() {
        $(this)
            .parent()
            .parent()
            .parent()
            .children()
            .children('span.vis')
            .addClass('show');
        $(this)
            .parent()
            .parent()
            .parent()
            .children()
            .children('span.edit')
            .removeClass('show');
        $(this)
            .parent()
            .parent()
            .parent()
            .children()
            .children('span.vis:not(.leave)')
            .each(function(index) {
                $(this).html(
                    $(this)
                        .siblings('span.edit')
                        .children()
                        .val(),
                );
            });
    });
    $(document).on('click', '.tableAnnuller', function() {
        $(this)
            .parent()
            .parent()
            .parent()
            .children()
            .children('span.vis')
            .addClass('show');
        $(this)
            .parent()
            .parent()
            .parent()
            .children()
            .children('span.edit')
            .removeClass('show');
    });
    /*$('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        zIndexOffset: '1031',
        weekStart: 1,
        language: 'da',
        autoclose: true
    });*/
    moment.locale('da', {
        week: { dow: 1 }, // Monday is the first day of the week
    });

    $('.datetimepicker').datetimepicker({
        stepping: 15,
        icons: {
            time: 'fa fa-clock-o',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-angle-double-left',
            next: 'fa fa-angle-double-right',
            today: 'fa fa-dot-circle-o',
            clear: 'fa fa-trash',
            close: 'fa fa-times',
        },
        format: 'YYYY-MM-DD HH:mm',
        sideBySide: true,
    });

    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD',
    });

    $('.datetimepicker[name="start"]').on('dp.change', function(e) {
        $('.datetimepicker[name="slut"][data-bestilling-id="' + $(this).data('bestilling-id') + '"]')
            .data('DateTimePicker')
            .minDate(e.date);
    });
    $('.datetimepicker[name="slut"]').on('dp.change', function(e) {
        $('.datetimepicker[name="start"][data-bestilling-id="' + $(this).data('bestilling-id') + '"]')
            .data('DateTimePicker')
            .maxDate(e.date);
    });
    // $('.datetimepicker[name="slut"]').on("dp.change", function (e) {
    //     $('.datetimepicker[name="start"]').each(function(index) {
    //         $(this).data("DateTimePicker").maxDate(e.date);
    //     });
    // });

    $('[data-toggle="select"]').select2({
        dropdownCssClass: 'show-select-search',
    });

    $('[data-toggle="switch"]').bootstrapSwitch();

    $('.alert')
        .delay(5000)
        .slideUp(200, function() {
            $(this).alert('close');
        });

    $('.btn_grp_booking > .btn').click(function() {
        $('#divCalendarWrapper').hide();
        $('#div_grp_booking').html($(this).text());
        $('#div_grp_booking').show();
        $('input#bookable_id').val($(this).data('bookable-id'));
        $('input#bookable_color').val($(this).data('bookable-color'));
        $('input#bookable_overlap').val($(this).data('bookable-overlap'));
        $.request('Bookables::onGetBookinger', {
            data: { bookable_id: $(this).data('bookable-id') },
            success: function(data) {
                console.log(data);
                data.forEach(function(item) {
                    if (item['editable'] == '0') {
                        delete item['editable'];
                    }
                });
                $('#divCalendarWrapper').show();
                $('#calendar').fullCalendar('render');
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('renderEvents', data);
            },
        });
    });

    $('#dataTable').DataTable();

    $('.export').on('click', function() {
        console.log('test');
        $('#' + $(this).data('table')).tableToCSV();
    });

    $('#calendar').fullCalendar({
        header: {
            left: '',
            center: '',
            right: '',
        },
        navLinks: true, // can click day/week names to navigate views
        defaultView: 'agendaWeek',
        selectable: true,
        allDaySlot: false,
        slotEventOverlap: false,
        eventOverlap: false,
        selectOverlap: function(event) {
            return event.rendering != 'background' && $('#bookable_overlap').val() == 1;
        },
        defaultDate: $('#calendar').data('default-date'),
        hiddenDays: $('#calendar').data('hidden-days'),
        firstDay: $('#calendar').data('first-day'),
        selectHelper: true,
        select: function(start, end) {
            var kommentar = prompt('Kommentar:');
            var eventData;
            if (true) {
                eventData = {
                    title: kommentar,
                    start: start,
                    end: end,
                    editable: true,
                    color: $('input#bookable_color').val(),
                };
                if (!$('#gruppe_id').val()) {
                    bootbox.alert('Du skal vælge en gruppe');
                } else {
                    $.request('Grupper::onAddBooking', {
                        data: {
                            start: start.format('YYYY-MM-DD HH:mm:ss'),
                            slut: end.format('YYYY-MM-DD HH:mm:ss'),
                            gruppe_id: $('#gruppe_id').val(),
                            bookable_id: $('#bookable_id').val(),
                            kommentar: kommentar,
                        },
                        success: function(data) {
                            // console.log(data);
                            eventData.id = data.booking_id;
                            // console.log(eventData);
                            $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                        },
                    });
                }
            }
            $('#calendar').fullCalendar('unselect');
        },
        eventDrop: function(event, delta, revertFunc) {
            // console.log(event);
            $.request('Grupper::onUpdateBooking', {
                data: {
                    booking_id: event.id,
                    start: event.start.format('YYYY-MM-DD HH:mm:ss'),
                    slut: event.end.format('YYYY-MM-DD HH:mm:ss'),
                },
                success: function(data) {
                    // console.log(data);
                },
            });
        },
        eventResize: function(event, delta, revertFunc) {
            // console.log(event);
            $.request('Grupper::onUpdateBooking', {
                data: {
                    booking_id: event.id,
                    start: event.start.format('YYYY-MM-DD HH:mm:ss'),
                    slut: event.end.format('YYYY-MM-DD HH:mm:ss'),
                },
                success: function(data) {
                    // console.log(data);
                },
            });
        },
        eventClick: function(calEvent, jsEvent, view) {
            console.log(calEvent);
            if (calEvent.editable == 1) {
                bootbox.dialog({
                    title: calEvent.start.format('DD/MM HH:mm') + " - " + calEvent.end.format('DD/MM HH:mm'),
                    message: "<p>" + calEvent.title + "</p>",
                    backdrop: true,
                    buttons: {
                        cancel: {
                            label: "Slet",
                            className: 'btn-danger',
                            callback: function(){
                                bootbox.confirm({
                                    title: 'Slet bookingen',
                                    message: 'Er du sikker på at du vil slette bookingen?',
                                    buttons: {
                                        confirm: {
                                            label: 'Ja',
                                            className: 'btn-success',
                                        },
                                        cancel: {
                                            label: 'Nej',
                                            className: 'btn-danger',
                                        },
                                    },
                                    callback: function(result) {
                                        if (result) {
                                            $.request('Grupper::onDeleteBooking', {
                                                data: { booking_id: calEvent.id },
                                                success: function(data) {
                                                    // console.log(data);
                                                    $('#calendar').fullCalendar('removeEvents', calEvent.id);
                                                },
                                            });
                                        }
                                    },
                                });
                            }
                        },
                        ok: {
                            label: "Luk",
                            className: 'btn-info',
                        }
                    }
                });
            } else {
                bootbox.dialog({
                    title: calEvent.start.format('DD/MM HH:mm') + " - " + calEvent.end.format('DD/MM HH:mm'),
                    message: "<p>" + calEvent.title + "</p>",
                    backdrop: true,
                    buttons: {
                        ok: {
                            label: "Luk",
                            className: 'btn-info',
                        }
                    }
                });
            }
        },
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: $('#calendar').data('events'),
        eventAfterRender: function(event, element, view) {
            // if($("input#bookable_overlap").val()==1 && event.rendering != 'background'){
            //     $(element).css('width','calc(100% - 10px)');
            // }
        },
    });

    /*var $input = $(".typeahead");
    $input.typeahead({
        source: JSON.parse($("#text_materialer").val()),
        autoSelect: true,
        displayText: function (item) {
            return item.navn + " - " + item.enhed;
        },
        highlighter: function (item) {
            var regex = new RegExp( '(' + this.query + ')', 'gi' );
            return item.replace( regex, "<u>$1</u>" );
        }
    });
    $input.change(function() {
        var current = $input.typeahead("getActive");
        if (current) {
            $("#materiale_id").val(current.id);


        }
    });
    $input.bind('typeahead:select', function(ev, suggestion) {
        console.log('Selection: ' + suggestion);
    });*/

    var options = {
        //url: "http://localhost/seniorkursusvork/october/plugins/vork/kurser/assets/js/countries.json",
        data: JSON.parse($('#text_materialer').val()),

        getValue: function(element) {
            return element.navn;
        },

        template: {
            type: "custom",
            method: function(value, item) {
                return value  + " - <small>" + item.total_antal + " " + item.enhed + "</small>";
            }
        },

        list: {
            sort: {
                enabled: true,
                method: function(a, b) {
                    var phrase = $('#materiale_navn').val();
                    return b['navn'].search(phrase) - a['navn'].search(phrase);
                }
            },
            match: {
                enabled: true,
            },
            maxNumberOfElements: 6,
            onChooseEvent: function() {
                var selectedItemValue = $('#materiale_navn').getSelectedItemData().id;

                $('#materiale_id').val(selectedItemValue);
            },
        },

        //theme: "round"
    };

    $('#materiale_navn').easyAutocomplete(options);
    $('div.easy-autocomplete').removeAttr('style');

    $(document).on('change', '.todo-checkbox', function() {
        data = { id: $(this).data('todo'), done: this.checked };
        console.log(data);
        $.request('Todos::onDoneTodo', {
            data: data,
            success: function(data) {
                location.reload();
            },
        });
    });
});
