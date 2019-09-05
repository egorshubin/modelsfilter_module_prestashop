$(document).ready(function() {
    if ($('#select-katalog').val() && $('#select-f-1').val() || $('#select-f-2').val() || $('#select-f-3').val() || $('#select-f-4').val()) {
        checkingIfFeaturesDbExist();
    }

    function checkingIfFeaturesDbExist() {
        $.ajax({
            type: 'POST',
            url: "/module/modelsfilter/ajax",
            cache: false,
            data: {
                'flag': '6'
            },
            success: function(msg) {
                if (msg == 0) {
                    $('.alarm-css').html('Вы должны обновить список свойств во вкладке "Обновить модели"!').show();
                }
                else {
                    $('.alarm-css').hide();
                }
            },
            fail: function(jqXHR, textStatus) {
                alert( "Запрос к базе данных Active Features не прошел");
            }
        });
    }


    $('.list-group-item-mf').click(function(){
        var id = $(this).attr('data-id');
        if (!$(this).hasClass('active')) {
            $('.pane-active').hide().removeClass('pane-active');
            $('#' + id).fadeIn().addClass('pane-active');
            $('.active').removeClass('active');
            $(this).addClass('active');
        }
    });
    var checkboxCategoriesList = [];
    var inactives = [];
    var newchecks = [];
    var updateMetas = [];

    $('.checkbox-category').each(function () {
        var val = $(this).val();
        if ($(this).prop("checked")) {
            checkboxCategoriesList.push(val);
            $(this).parent().addClass("category-bold");
        }
    });

    $('.checkbox-category').change(function(){
        var val = $(this).val();
        if($(this).prop("checked")) {
            checkboxCategoriesList.push(val);
            newchecks.push(val);
            var idx1 = $.inArray(val, inactives);
            if( idx1 > -1 ){
                inactives.splice(idx1, 1);
            }
            $(this).parent().addClass("category-bold");
            var opener = $(this).parent().next();
            if (opener.hasClass('icon-caret-down') && !($('#title-1-' + val).val())) {
                var id = '#' + $(opener).attr('data-id');
                $(id).slideDown();
            }
        }
        else {
            var idx = $.inArray(val, checkboxCategoriesList);
            if( idx > -1 ){
                checkboxCategoriesList.splice(idx, 1);
            }
            var idx2 = $.inArray(val, newchecks);
            if( idx2 > -1 ){
                newchecks.splice(idx2, 1);
            }
            inactives.push(val);
            $(this).parent().removeClass("category-bold");
        }

    });

    $('.ta-meta').blur(function(){
        var val = $(this).parent().parent().parent().parent().prev().find('.checkbox-category').val();
        var label = $(this).parent().parent().parent().parent().prev().find('label').css('color', '');
        var idx3 = $.inArray(val, updateMetas);
        if (idx3 == -1) {
            updateMetas.push(val);
        }
    });

    //metatags opening

    $('.icon-caret-down').on('click', function () {
        var id = '#' + $(this).attr('data-id');
        $(id).slideToggle();
    });

    $('#choose-categories').on('submit', function (e) {
        e.preventDefault();
        var checkTitle = 0;

        $.each(checkboxCategoriesList, function(key,value){
            if (!$('#title-1-' + value).val() || !$('#title-2-' + value).val() || !$('#desc-1-' + value).val() || !$('#desc-2-' + value).val()) {
                checkTitle = 1;
                $('#' + value).parent().css('color', 'red');
            }
        });
        if (checkTitle == 1) {
            alert('Заполните title и description у всех отмеченных категорий!');
            return false;
        }

        // console.log(checkboxCategoriesList);
        // console.log(newchecks);
        // console.log(inactives);
        // console.log(updateMetas);
        var memetas = [];
        $.each(updateMetas, function(key, value){
            var active = 0;
            var idx4 = $.inArray(value, checkboxCategoriesList);
            if (idx4 > -1) {
                active = 1;
            }
           var array = {
               'id': value,
               'title_1': $('#title-1-' + value).val(),
               'title_2': $('#title-2-' + value).val(),
               'desc_1': $('#desc-1-' + value).val(),
               'desc_2': $('#desc-2-' + value).val(),
               'active': active
           };
           memetas.push(array);
        });
        // console.log(memetas);

        // var checks = JSON.stringify(checkboxCategoriesList);
        var newchecks1 = JSON.stringify(newchecks);
        var inactives1 = JSON.stringify(inactives);
        // var updatemetas = JSON.stringify(updateMetas);
        var metas = JSON.stringify(memetas);

        // console.log(metas);

        $.ajax({
            type: 'POST',
            url: "/module/modelsfilter/ajax",
            cache: false,
            beforeSend: function(){
                $('#ajax_running').css('display', 'inline-block');
            },
            data: {
                'newchecks': newchecks1,
                'inactives': inactives1,
                'metas': metas,
                'flag': '2'
            },
            success: function(msg) {
                $('#ajax_running').css('display', 'none');
                alert(msg);
                window.location = window.location.href;
            },
            fail: function(jqXHR, textStatus) {
                $('#ajax_running').css('display', 'none');
                alert( "Request failed: " + textStatus );
            }
        });
    });

    // features addition

    if (!$('#select-f-1').val() || !$('#select-f-2').val() || !$('#select-f-3').val() || !$('#select-f-4').val()) {
        $('.alarm-css').html('Вы должны задать все свойства!').show();
    }

    $('#choose-features').on('submit', function (e) {
        e.preventDefault();

        if (!$('#select-f-1').val() || !$('#select-f-2').val() || !$('#select-f-3').val() || !$('#select-f-4').val()) {
            alert('Вы должны задать все свойства!');
            return false;
        }

        var mfArray = {
            '1': $('#select-f-1').val(),
            '2': $('#select-f-2').val(),
            '3': $('#select-f-3').val(),
            '4': $('#select-f-4').val(),
        };

        mfArray = JSON.stringify(mfArray);

        $.ajax({
            type: 'POST',
            url: "/module/modelsfilter/ajax",
            cache: false,
            beforeSend: function(){
                $('#ajax_running').css('display', 'inline-block');
            },
            data: {
                'mfs': mfArray,
                'flag': '3'
            },
            success: function(msg) {
                $('#ajax_running').css('display', 'none');
                alert(msg);
                $('.alarm-css').hide();
                window.location = window.location.href;
            },
            fail: function(jqXHR, textStatus) {
                $('#ajax_running').css('display', 'none');
                alert( "Request failed: " + textStatus );
            }
        });


    });

    //adding, updating features

    $('#generate-update').click(function () {
        $.ajax({
            type: 'POST',
            url: "/module/modelsfilter/ajax",
            cache: false,
            beforeSend: function(){
                $('#ajax_running').css('display', 'inline-block');
            },
            data: {
                'flag': '4'
            },
            success: function(msg) {
                $('#ajax_running').css('display', 'none');
                alert(msg);
                checkingIfFeaturesDbExist();
                window.location = window.location.href;
            },
            fail: function(jqXHR, textStatus) {
                $('#ajax_running').css('display', 'none');
                alert( "Request failed: " + textStatus );
            }
        });
    });

    //main category addition

    if (!$('#select-katalog').val()) {
        var text = 'Вы должны задать главную категорию, в которой будут отображаться все товары! (на момент создания модуля и если ничего не изменилось, это Каталог)';
        if ($('#select-katalog').val() && $('#select-f-1').val() || $('#select-f-2').val() || $('#select-f-3').val() || $('#select-f-4').val()) {
            $('.alarm-css').html(text).show();
        }
        else {
            var html = $('.alarm-css').html() + '<br>' + text;
            $('.alarm-css').html(html).show();
        }

    }

    $('#choose-main-category').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: "/module/modelsfilter/ajax",
            cache: false,
            beforeSend: function(){
                $('#ajax_running').css('display', 'inline-block');
            },
            data: {
                'cat': $('#select-katalog').val(),
                'flag': '5'
            },
            success: function(msg) {
                $('#ajax_running').css('display', 'none');
                alert(msg);
                $('.alarm-css').hide();
                window.location = window.location.href;
            },
            fail: function(jqXHR, textStatus) {
                $('#ajax_running').css('display', 'none');
                alert( "Request failed: " + textStatus );
            }
        });
    });

});