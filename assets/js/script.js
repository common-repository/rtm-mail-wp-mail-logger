// Load Flatpickr libraries
const flatpickr = require("flatpickr").default;
import "flatpickr/dist/flatpickr.min.css";

// Load CodeMirror libraries
import CodeMirror from "codemirror";
import 'codemirror/lib/codemirror.css';
import 'codemirror/theme/monokai.css';
import 'codemirror/mode/xml/xml.js';

jQuery(document).ready(function ($) {
    flatpickr("#rtm-date-range", {
        mode: "range",
        maxDate: "today",
        dateFormat: "d-m-Y",
        onChange: function (selectedDates, dateStr, instance) {
            let dates = instance.element.value.split('to');
            // If only one date is available
            if (!dates[1]) {
                let currentDate = new Date().toLocaleDateString('nl-NL', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                });
                // Set current date
                dateStr = dates[0] + ' to ' + currentDate;
            }
            instance.element.value = dateStr.replace('to', '-');
        },
    });


    if (document.getElementById('edit_body') != undefined) {
        var editor = CodeMirror.fromTextArea(document.getElementById('edit_body'), {
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            theme: 'monokai',
            mode: 'xml',
            htmlMode: true,
            autoCloseTags: true,
        });
    }

    $('#rtm-mail').click(function () {
        $('#rtm-mail .dropdown-menu').css('display', 'none');
    });

    // Add field of outgoing email setting
    $('body').on('click', '#rtm-mail #add_outgoing_field', function () {
        // random number for semi "unique" box ids
        let box_id = Math.floor(Math.random() * 999);
        var html = `
        <tr class="outgoing-mail-row" data-boxid="${box_id}">
            <td class="outgoing-mail-cell email-cell">
                <input type="text" name="option_email[]" class="option-email"
                       placeholder="${rtm_mail_translation.type_address}" />
            </td>
            <td class="outgoing-mail-cell type-cell">
                <select class="outgoing_type" name="outgoing_type[]">
                    <option value="cc">CC</option>
                    <option value="bcc">BCC</option>
                    <option value="recipient">Recipient</option>
                </select>
            </td>
            <td class="outgoing-mail-cell button-cell">
                 <button type="button" name="remove_outgoing"
                        class="button button-invert-remove remove_outgoing_field"
                        data-boxid="${box_id}"
                        style="padding: 0 12px">-
                </button>
            </td>
        </tr>
        `;
        $("#outgoing__list").append(html);
    });

    // Remove field of outgoing email setting
    $('body').on('click', '#rtm-mail .remove_outgoing_field', function () {
        let box_id = $(this).attr('data-boxid');
        $("[data-boxid='" + box_id + "'").remove();
        $(this).remove();
    });

    // Toggle search filters
    $('body').on('click', '#rtm-mail .filter-box .filter-label-box .filter-label', function () {
        $('#rtm-mail .filter-box .filter-input').slideToggle('fast', function () {
            if ($(this).is(':visible')) {
                $(this).css('display', 'flex');
            }
        });
        $(this).find('.filter__icon').toggleClass('fa-caret-down').toggleClass('fa-caret-up');
    });

    // Close modal
    $('body').on('click', '#rtm-mail .modal-close', function () {
        let log_id = $(this).attr('data-id');
        $('#modal_' + log_id).fadeOut(250);
    });

    $('body').on('click', '#rtm-mail #delete_log', function () {
        $('.modal-container').css('height', 'auto');
        $('#modal_delete_log').fadeIn(250);
    });

    $('body').on('click', '#rtm-mail #send_mail', function () {
        $('.modal-container').css({
            width: 'fit-content',
            height: 'auto',
        });
        $('#modal_send_log').fadeIn(250);
    });

    $('body').on('click', '#rtm-mail #delete-submit', function () {
        $('.modal-container').css({
            width: 'fit-content',
            height: 'auto',
        });
        $('#modal_delete_log_rows').fadeIn(250);
    });

    $('body').on('click', '#rtm-mail #action-send', function () {
        $('.modal-container').css({height: 'auto', width: '17vw'});
        $('#send_log_id').val($(this).attr('data-id'));
        $('#modal_log_action_send').fadeIn(250);
    });

    $('body').on('click', '#rtm-mail #action-delete', function () {
        $('.modal-container').css({height: 'auto', width: '17vw'});
        $('#delete_log_id').val($(this).attr('data-id'));
        $('#modal_log_action_delete').fadeIn(250);
    });

    $('body').on('click', '#rtm-mail #send-submit', function () {
        $('.modal-container').css('height', 'auto');
        $('#modal_send_log_rows').fadeIn(250);
    });

    $('body').on('change', '#rtm-mail .log-select, #rtm-mail #cb-select-all-1, #rtm-mail #cb-select-all-2', function () {
        $("#send-submit").prop("disabled", true);
        $("#delete-submit").prop("disabled", true);
        $(".log-select").each(function () {
            if ($(this).is(':checked')) {
                $("#send-submit").prop("disabled", false);
                $("#delete-submit").prop("disabled", false);
            }
        });
    });

    $('body').on('click', '#rtm-mail .event-detail-block', function () {
        let target = $(this).parent().find('.event-details-expand');
        $(target).slideToggle();
        $(this).find('.caret__icon').toggleClass('fa-caret-down').toggleClass('fa-caret-left');
    });

    // info dashboard icon
    $('body').on('click', '#rtm-mail .icon__info', function () {
        $('.modal-container').css('height', 'auto');
        $('#' + $(this).attr('data-target')).fadeIn(250);
    });

    $('body').on('click', '#rtm-mail .toggle__dropdown', function () {
        $('.' + $(this).attr('data-target')).css('display', 'block');
    });

    $('body').on('click', '#rtm-mail #clear-events', function () {
        $('.modal-container').css({height: 'auto', width: '17vw'});
        $('#modal_clear_events').fadeIn(250);
    });

    $('body').on('click', '#rtm-mail .mobile-link-toggle', function () {
        $(this).find('.fas').toggleClass('fa-bars').toggleClass('fa-times');
        $('.navbar-mobile-container').fadeToggle("fast", function () {
            $('.navbar-mobile').animate({
                width: "toggle"
            });
        });
    });

    $('body').on('click', '#rtm-mail .feature-image-preview', function () {
        $('#image-'+$(this).attr('data-target')).fadeIn(250);
    });

    $('body').on('click', '#rtm-mail .preview__close', function () {
        $('#image-'+$(this).attr('data-close')).fadeOut(250);
    });

    $('body').on('click', '#rtm-mail .image-preview-modal', function () {
        $(this).fadeOut(250);
    });

    $('body').on('change', '#rtm-mail #enable__smtp', function() {
       if ($(this).is(':checked')) {
           $('.smtp__option').css({
               opacity: '1.0',
               pointerEvents: 'auto',
           });
       }  else {
           $('.smtp__option').css({
               opacity: '0.4',
               pointerEvents: 'none',
           });
       }
    });

    $('body').on('change', '#rtm-mail #enable__authentication', function() {
        if ($(this).is(':checked')) {
            $('.smtp__credentials').css('display', 'flex');
        }  else {
            $('.smtp__credentials').css('display', 'none');
        }
    });

    $('#rtm-mail .bulk__action').change(function() {
        if ($(this).val()) {
            $('#apply__bulk__action').prop('disabled', false)
        } else {
            $('#apply__bulk__action').prop('disabled', true)
        }
    });

    $('#apply__bulk__action').click((e) => {
        let action = $('.bulk__action').val();
        if (action === 'send') {
            $('.modal-container').css('height', 'auto');
            $('#modal_send_log_rows').fadeIn(250);
        } else if (action === 'delete') {
            $('.modal-container').css({
                width: 'fit-content',
                height: 'auto',
            });
            $('#modal_delete_log_rows').fadeIn(250);
        }
    });

})
