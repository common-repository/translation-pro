(function ($) {
    'use strict';

    $(document).on('change', '.cl_price_update', function () {
        cl_update_price();
    });


    //Event onchange
    $(document).on('tinymce-editor-setup', function (event, editor) {

        editor.on('change', function (e) {
            cl_update_price();
        });

    });

    $(document).on('click', '.cl_remove_lng', function (e) {
        var _sel = jQuery('#target').val();
        var _iso_for_remove = $(this).attr('data-iso');

        _sel = _sel.filter(function (value, index, arr) {
            if (_iso_for_remove != value)
                return value;
        });

        if (_sel.length == 0) {
            $('#cl_lng_breakdown_wrapper_result').empty();
            $('#cl_total').empty().text(0);
        }

        jQuery('#target').val(_sel).trigger('change');

        return false;
    });


    $(document).ready(function () {
        //Settings
        $('#back_to_login').click(function () {
            $('#registration_area').fadeOut('slow', function () {
                $('#login_area').fadeIn();
            });
        });

        $("#create_account").click(function () {
            $('#login_area').fadeOut('slow', function () {
                $('#registration_area').fadeIn();
            });
        });

        //Translation
        $('.js-example-basic-single').select2({
            placeholder: 'Choose language'
        });

        $('.cl_source_lng').change(function () {
            $.ajax({
                url: "admin-post.php",
                method: "POST",
                dataType: 'json',
                data: {
                    'action': 'cltp_target_language',
                    'source': $(this).val()
                },
                beforeSend: function (xhr) {
                    $('.cl_loading').show();
                },
            }).done(function (data) {
                $("#target option").remove();

                data.forEach(function (ele) {
                    $("#target").append('<option value="' + ele.iso_code + '">' + ele.name + '</option>');
                });

                $('.cl_loading').hide();
            });
        });

        $('#cltp_pages_list').change(function () {
            $.ajax({
                url: "admin-post.php",
                method: "POST",
                dataType: 'json',
                data: {
                    'action': 'cltp_select_page_source',
                    'id': $(this).val(),
                    'type': 1
                },
                beforeSend: function (xhr) {
                    $('.cl_loading').show();
                },
            }).done(function (data) {
                var instance = window.parent.tinyMCE;
                instance.activeEditor.execCommand('mceSetContent', false, data);
                $('.cl_loading').hide();

                cl_update_price();
            });
        });

        $('#cltp_posts_list').change(function () {
            $.ajax({
                url: "admin-post.php",
                method: "POST",
                dataType: 'json',
                data: {
                    'action': 'cltp_select_page_source',
                    'id': $(this).val(),
                    'type': 2
                },
                beforeSend: function (xhr) {
                    $('.cl_loading').show();
                },
            }).done(function (data) {
                var instance = window.parent.tinyMCE;
                instance.activeEditor.execCommand('mceSetContent', false, data);
                $('.cl_loading').hide();

                cl_update_price();
            });
        });


    });

    function cl_update_price() {
        var target = Array();

        //Validation
        if (!cl_translate_validation())
            return;

        $('#target').select2('data').forEach(function (e) {
            target.push(e.id);
        });

        $.ajax({
            url: "admin-post.php",
            method: "POST",
            dataType: 'json',
            data: {
                'action': 'cltp_calculate_project',
                'from_id': $('#source').val(),
                'to_id': target,
                'content': tinymce.editors['content'].getContent(),
                'delivery': $('#delivery').val(),
                'product': 1,
            },
            beforeSend: function (xhr) {
                $('.cl_loading').show();
            },
        }).done(function (data) {
            if (data.status == false) {
                alert(data.msg);
                $('.cl_loading').hide();
                return;
            }

            cl_update_order_overview(data);
            $('.cl_loading').hide();
        });

    }

    function cl_update_order_overview(data) {
        $('#cl_lng_breakdown_wrapper_result').empty();
        $('#cl_source_name').empty().text($('#source option[selected]').text());
        var no_word = 0;
        var total = 0;

        data.calculation.forEach(function (e) {
            var html = '<div class="cl_lng_breakdown_wrapper">';
            html += '   <img class="cl_lng_icon" src="' + plugin_dir_url + 'img/flags_iso/16/' + e.to.iso_code + '.png" />';
            html += '   ' + e.to.name;
            html += '   <a href="" class="cl_remove_lng" data-iso="' + e.to.iso_code + '"><span class="dashicons dashicons-trash"></span></a>';
            html += '   <span style="float: right; margin-right: 5px">$' + e.price + '</span>';
            html += '</div>';

            $('#cl_lng_breakdown_wrapper_result').append(html);
            no_word = e.twords;
            total += e.price;
        });

        $('#cl_words').empty().text(no_word);
        $('#cl_total').empty().text(total.toPrecision(2));
    }

    function cl_translate_validation() {
        var pass = true;
        $('#cl_place_order').prop("disabled", true);
        if ($("#title").val().length < 4) {
            $("#title").addClass('cl_error');
            pass = false;
        } else {
            $("#title").removeClass('cl_error')
        }

        if (!$("#source").val()) {
            $('#source').next().find('.select2-selection').addClass('cl_error');
            pass = false;
        } else {
            $('#source').next().find('.select2-selection').removeClass('cl_error');
        }

        if (!$("#target").val()) {
            $('#target').next().find('.select2-selection').addClass('cl_error');
            pass = false;
        } else {
            $('#target').next().find('.select2-selection').removeClass('cl_error');
        }

        if ($(tinymce.editors['content'].getContent()).text().length < 3) {
            $('#mceu_7').addClass('cl_error');
            pass = false;
        } else {
            $('#mceu_7').removeClass('cl_error');
        }

        if (pass)
            $('#cl_place_order').prop("disabled", false);

        return pass;
    }


})
(jQuery);
