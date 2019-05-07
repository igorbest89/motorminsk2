
$('select.data-select_id').on('change', function (e) {

    $('.ajax_loader').remove();
    let updateSelector = this.id;
    let updateValue = this.value;

    if(updateSelector !== 'generation'){
            $('.custom_p').after('<div class="ajax_loader" ><img src="/image/catalog/172.gif" /></div>');
            $.post('index.php?route=extension/module/filtersearch/getSelectFilters', {
                filterKeyword: this.value,
                filterType: this.name,
                filterMethod: this.id,
                filterId: $(this).children(':selected').attr('id')
            }, function (data) {
                $('.ajax_loader').remove();
                if (updateSelector === 'marks') {
                    data = '<option value="">Модель</option>' + data;
                    $('select#models').html(data).trigger('refresh');
                    let clearData = '<option value="">Поколение</option>';
                    $('select#generation').html(clearData).trigger('refresh');
                    $('#extended-div').css('cursor', 'not-allowed');
                    $('#filter-search_extended').css('pointer-events', 'none');
                    $('.filters.filters-main').slideUp(500);
                    $('.filters.filters-main, .fastSearch__more').removeClass('open');
                } else if (updateSelector === 'models') {

                    if (data === '') {
                        data = '<option value="" id="0">Без модификации</option>';
                        $('select#generation').html(data).trigger('refresh');
                        $('#filter-search_extended').css('pointer-events', 'all');
                        $('#extended-div').css('cursor', 'not-allowed');
                    } else {
                        data = '<option value="">Поколение</option>' + data;
                        $('select#generation').html(data).trigger('refresh');
                        $('#extended-div').css('cursor', 'not-allowed');
                        $('#filter-search_extended').css('pointer-events', 'none');
                        $('.filters.filters-main').slideUp(500);
                        $('.filters.filters-main, .fastSearch__more').removeClass('open');
                    }
                } else if (updateSelector === 'generation' && updateValue !== '') {
                    $('#ev').css('cursor', 'pointer');
                    $('#extended-div').removeAttr('style');
                    $('#filter-search_extended').css('pointer-events', 'all');
                    $('#extended_filters-ajax').html(data);
                    $('select').trigger('refresh');
                    $('.sidebar-scroll').perfectScrollbar({
                        minScrollbarLength: 50,
                        maxScrollbarLength: 50,
                    });
                    let massSumm = $('input#hidden-massSum').val();
                    $('#massSum').text(massSumm);
                    rangeSlider();
                } else {
                    $('#extended-div').css('cursor', 'not-allowed');
                    $('#filter-search_extended').css('pointer-events', 'none');
                    $('.filters.filters-main').slideUp(500);
                    $('.filters.filters-main, .fastSearch__more').removeClass('open');
                }
            });
        }
});

$('#extended_filters-ajax').delegate('input[name^=\'filter-search\']', 'change', function () {
    if ($(this).is(':checked')) {
        $('#filter_status').append('<a href="javascript:(0)" id="delete_status_' + $(this).attr('data-filter_id') + '"><span>' + $(this).attr('data-filter_name') + '</span></a>');
    } else {
        $('#delete_status_' + $(this).attr('data-filter_id')).remove();
    }
});

$('button#extended_filters__resetButton').on('click', function () {
    $('select.data-select_id').val('').trigger('refresh');
    $('input:checkbox').removeAttr('checked');
    $('label.checkbox__label').removeClass('active');
    $('#extended-div').css('cursor', 'not-allowed');
    $('#filter-search_extended').css('pointer-events', 'none');
    $('.filters.filters-main').slideUp(500);
    $('.filters.filters-main, .fastSearch__more').removeClass('open');
    $('#filter_status').empty();
});

function searchingExtendedFilters() {
    let input, hideElements, checkboxList, filter, list, elements, text, i;
    input = document.getElementById('searchingInput');
    filter = input.value.toUpperCase();
    list = document.getElementById("searchingList");
    elements = list.querySelectorAll('span.fillengine__model');
    hideElements = list.querySelectorAll('.fillengine__type');
    checkboxList = list.querySelectorAll('label');

    for (i = 0; i < elements.length; i++) {
        text = elements[i];
        if (text.innerHTML.toUpperCase().indexOf(filter) > -1) {
            elements[i].style.display = '';
            checkboxList[i].style.display = '';
            hideElements[i].style.display = '';
        } else {
            hideElements[i].style.display = 'none';
            elements[i].style.display = 'none';
            checkboxList[i].style.display = 'none';
        }
    }
}


$('#search_detal').keyup(function (event) {
    if ($('#search_detal').val().length > 2) {
        $.ajax({
            url: $('base').attr('href') + 'index.php?route=extension/module/filtersearch/autocomplete&filter_name=' + $('#search_detal').val(),
            dataType: 'json',
            beforeSend: function (xhr) {
            },
            success: function (result) {
                var html = "";
                for (var item in result) {
                    html += "<option name='filter[]' id='search-detail__selected' class='search-detail' data-keyword='" + result[item].keyword + "' data-idcategory='" + result[item].category_id
                        + "'>" + result[item].name + "</option>";
                }
                $('#category_search_result').html(html);
                if ($('#category_search_result option').length > 1) {
                    $('#search_detal').parent().addClass('open');
                }
            }
        });
    }


    if ($('#search_detal').val().length == 0 || $('#search_detal').val().length == undefined) {
        $('#category_search_result .search-detail').remove();
        $(this).parent().removeClass('open');
    }

});

$('#category_search_result').delegate('option.search-detail', 'click', function () {
    $(this).attr('selected', 'selected');
    var optionVal = $(this).val();
    $('#search_detal').val(optionVal);

    $('#category_search_result .search-detail').not($(this)).remove();
    $(this).parent().parent().removeClass('open');

});

// close dropdown by click on body
$(document).click(function (e) {

    var div = $("#category_search_result, #search_detal");

    if (!div.is(e.target) && div.has(e.target).length === 0) {
        div.parent().removeClass('open');
    }

});

// open dropdown by click on input
$('#search_detal').on('focus', function () {

    if ($(this).val().length > 1) {
        $(this).parent().addClass('open');
    }

});

