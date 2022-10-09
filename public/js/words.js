function editData(wordId, word, definition, articleId) {
    $('#word').val(word);
    $('#definition').val(definition);
    $('#wordModal').modal('show');
    $('#addWordLabel').text('Edit your word');

    loadArticles('articles', articleId);

    $('#saveData').attr('onclick', 'updateData(' + wordId + ')');
}

function editArticle(articleId, title, link) {
    $('#article_title').val(title);
    $('#article_link').val(link);
    $('#articleModal').modal('show');
    $('#saveArticle').attr('onclick', 'updateArticle(' + articleId + ')');
}


function searchWord(event) {

    var wordArr = [];

    var typedString = $('#searchWord').val();
    if (typedString == '') {
        return false;
    }
    $.ajax({
        url: '/search-word/' + typedString,
        type: 'GET',
        async: false,
        dataType: 'json',
        error: function () {
            console.log("ajax error" + url);
        },
        success: function (response) {
            $.each(response, function (index, value) {
                wordArr.push(value.word + ' (' + value.definition + ')');
            });
        }
    });

    $("#searchWord").autocomplete({
        source: wordArr,
        select: function (event, ui) {
            var selectedArr = ui.item.value.split(' (');
            var selectedWord = selectedArr[0];
            loadWords(selectedWord);

        }
    });
}


function checkAll() {
    if ($('#checkAll:checked').length) {

        $('[id^="wordCheck_"]').each(function () {
            $('#' + this.id).prop('checked', true);
        });

        // $('#test').prop('checked', true);


    } else {
        $('[id^="wordCheck_"]').each(function () {
            $('#' + this.id).prop('checked', false);
        });
    }

}




$('.modalForm').on('keyup', function (e) {
    if (e.which == 13) {
        $('#saveData').click();
    } else {
        checkValidity();
    }
});

$('.articleModalForm').on('keyup', function (e) {
    if (e.which == 13) {
        $('#saveArticle').click();
    } else {
        checkArticleValidity();
    }
});

function deleteData(wordId) {
    $('#confirm').modal('show');
    $('#actionYes').attr('onclick', 'deleteWordConfirmed(' + wordId + ')');
}

function deleteArticle(articleId) {
    $('#confirm').modal('show');
    $('#actionYes').attr('onclick', 'deleteArticleConfirmed(' + articleId + ')');
}

function cancelAction() {
    $('#confirm').modal('hide');
    $('#actionYes').attr('onclick', '');
}

function deleteWordConfirmed(wordId) {

    $('#confirm').modal('hide');
    var requestFor = 'delete';

    var formData = new FormData;
    formData.append('wordId', wordId);
    formData.append('requestFor', requestFor);

    ajax('/update-word', 'POST', '', formData);

    $('#wordRow_' + wordId).fadeOut("slow");
}

function deleteArticleConfirmed(articleId) {

    $('#confirm').modal('hide');
    var requestFor = 'deleteArticle';

    var formData = new FormData;
    formData.append('articleId', articleId);
    formData.append('requestFor', requestFor);

    ajax('/update-article', 'POST', '', formData);

    $('#articleRow_' + articleId).fadeOut("slow");
}

function markNotLearned(wordId) {
    var requestFor = 'notLearned';

    var formData = new FormData;
    formData.append('wordId', wordId);
    $('#btnNotLearned_' + wordId).fadeOut("slow");

    // $('#wordRow_' + wordId).fadeOut("slow");

    ajax('/mark-not-learned', 'POST', '', formData);
}



function markLearned(wordId, noOfRead) {

    var requestFor = 'learned';
    var noOfRead = noOfRead + 1;

    var formData = new FormData;
    formData.append('wordId', wordId);
    formData.append('requestFor', requestFor);
    formData.append('noOfRead', noOfRead);

    $('#wordRow_' + wordId).fadeOut("slow");

    ajax('/update-word', 'POST', '', formData);


}

function checkValidity() {

    var flag = 1;

    if (!$('#word').val()) {
        $('#word').addClass('border-danger');
        flag = flag - 1;
    } else {
        $('#word').removeClass('border-danger');
    }

    if (!$('#definition').val()) {
        $('#definition').addClass('border-danger');
        flag = flag - 1;
    } else {
        $('#definition').removeClass('border-danger');
    }

    if (flag < 1) {
        return false;
    } else {
        return true;
    }

}

function checkArticleValidity() {

    var flag = 1;

    if (!$('#article_title').val()) {
        $('#article_title').addClass('border-danger');
        flag = flag - 1;
    } else {
        $('#article_title').removeClass('border-danger');
    }

    if (!$('#article_link').val()) {
        $('#article_link').addClass('border-danger');
        flag = flag - 1;
    } else {
        $('#article_link').removeClass('border-danger');
    }

    if (flag < 1) {
        return false;
    } else {
        return true;
    }

}



function viewAll() {

    $('[id^="wordCheck_"]').each(function () {
        var idToView = this.id.split('_');
        var idToView = idToView.pop();
        toggleDefinition(idToView + '_definition');
    });
}


function toggleDefinition(toShow) {
    $('#' + toShow).toggleClass('d-none');
}

function clearField(fieldId) {
    $('#' + fieldId).val('');
}

function addWord() {
    $('#wordModal').modal('show');
    $('#addWordLabel').text('Add word to your dictionary');
    $('#saveData').attr('onclick', 'saveData()');
    var wordInSearchBox = $('#searchWord').val();
    $('#word').val(wordInSearchBox);
    $('#definition').val('');
    loadArticles('articles');

}

function addArticle() {
    $('#articleModal').modal('show');
    $('#saveArticle').attr('onclick', 'saveArticle()');
    var articleSearch = $('#articleSearch').val();
    $('#article_title').val(articleSearch);
    $('#article_link').val('');

}

function updateArticle(articleId) {

    if (checkArticleValidity()) {


        var article_title = $('#article_title').val();
        var article_link = $('#article_link').val();

        $('#articleModal').modal('hide');

        var formData = new FormData;
        formData.append('article_title', article_title);
        formData.append('article_link', article_link);
        formData.append('articleId', articleId);

        setTimeout(() => {
            ajax('/update-article', 'POST', '', formData);
        }, 0);


        viewArticles();

        $('#article_title').val('');
        $('#article_link').val('');

    } else {
        console.log('Error updating data');
    }

}


function updateData(wordId) {

    if (checkValidity()) {


        $('#wordModal').modal('hide');
        $('#saveData').attr('onclick', 'saveData()');


        var word = $('#word').val();
        var definition = $('#definition').val();
        var articleId = $('#articles').val();

        if (articleId == 0) {
            articleId = 1;
        }

        var formData = new FormData;
        formData.append('wordId', wordId);
        formData.append('word', word);
        formData.append('definition', definition);
        formData.append('articleId', articleId);

        ajax('/update-word', 'POST', '', formData);
        loadWords();

        $('#word').val('');
        $('#definition').val('');

    } else {
        console.log('Error updating data');
    }

}

function hideModal(modalId) {
    $('#' + modalId).modal('toggle');
}

function saveData() {

    if (checkValidity()) {

        var word = $('#word').val();
        var definition = $('#definition').val();
        var articleId = $('#articles').val();

        $('#wordModal').modal('hide');

        var formData = new FormData;
        formData.append('word', word);
        formData.append('definition', definition);
        formData.append('articleId', articleId);

        // ajax('/save-word', 'POST', '', formData);

        // custom ajax starts

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (typeof formData === 'undefined') {
            formData = new FormData;
        }


        $.ajax({
            url: '/save-word',
            type: 'POST',
            async: false,
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            error: function () {
                $('#errorMessege').removeClass('d-none');
                loadWords();
            },
            success: function (response) {
                console.log(response);
                $('#successMessege').removeClass('d-none');

                setTimeout(() => {
                    $('#successMessege').fadeOut() ;
                }, 2000);

                loadWords();
                
            }
        });

        // custom ajax starts


        // loadWords();

        $('#word').val('');
        $('#definition').val('');

    } else {
        console.log('Error saving data');
    }
}

function saveArticle() {

    if (checkArticleValidity()) {

        var article_title = $('#article_title').val();
        var article_link = $('#article_link').val();

        $('#articleModal').modal('hide');

        var formData = new FormData;
        formData.append('article_title', article_title);
        formData.append('article_link', article_link);

        ajax('/save-article', 'POST', '', formData);
        loadArticles('wordsOnArticle');

        $('#article_title').val('');
        $('#article_link').val('');

    } else {
        console.log('Error saving data');
    }
}



function dateSearch() {
    var startDate = $('#startDate').val() == '' ? 0 : $('#startDate').val();
    var endDate = $('#endDate').val() == '' ? 0 : $('#endDate').val();

    var contentId = 'wordsTable';
    var skeletonId = 'skeleton';

    hideContentShowSkeletons(contentId, skeletonId);
    var functionsOnSuccess = [
        [showContentHideSkeletons, [contentId, skeletonId, 'response']],
    ];

    ajax('/date-search/' + startDate + '/' + endDate, 'GET', functionsOnSuccess);

}

function loadWordsOnArticle(articleNumber = $('#wordsOnArticle').val()) {

    if (articleNumber == 0) {
        loadWords();
        return false;
    }

    var contentId = 'wordsTable';
    var skeletonId = 'skeleton';

    hideContentShowSkeletons(contentId, skeletonId);
    var functionsOnSuccess = [
        [showContentHideSkeletons, [contentId, skeletonId, 'response']],
    ];

    ajax('/loadWordsOnArticle/' + articleNumber, 'GET', functionsOnSuccess);
}

function loadWordsOnRead() {
    var readNumber = $('#no_of_read').val();
    var contentId = 'wordsTable';
    var skeletonId = 'skeleton';

    hideContentShowSkeletons(contentId, skeletonId);
    var functionsOnSuccess = [
        [showContentHideSkeletons, [contentId, skeletonId, 'response']],
    ];

    ajax('/loadWordsOnRead/' + readNumber, 'GET', functionsOnSuccess);
}

function viewArticles() {
    var contentId = 'wordsTable';
    var skeletonId = 'skeleton1';

    hideContentShowSkeletons(contentId, skeletonId);
    var functionsOnSuccess = [
        [showContentHideSkeletons, [contentId, skeletonId, 'response']],
    ];

    setTimeout(() => {
        ajax('/view-articles', 'GET', functionsOnSuccess);
    }, 0);
}



function loadWords(wordToLoad) {

    if (wordToLoad === undefined) {
        wordToLoad = 0;
    }
    var contentId = 'wordsTable';
    var skeletonId = 'skeleton';

    hideContentShowSkeletons(contentId, skeletonId);
    var functionsOnSuccess = [
        [showContentHideSkeletons, [contentId, skeletonId, 'response']],
    ];

    setTimeout(() => {
        ajax('/loadWords/' + wordToLoad, 'GET', functionsOnSuccess);
    }, 0);
}

setTimeout(() => {
    loadWords();
}, 0);

loadNumbers();
setTimeout(() => {
    loadArticles('wordsOnArticle');
}, 0);



function loadArticles(contentId, articleId = 0) {
    var skeletonId = 'skeleton';

    hideContentShowSkeletons(contentId, skeletonId);
    var functionsOnSuccess = [
        [showContentHideSkeletons, [contentId, skeletonId, 'response']],
    ];

    ajax('/load-articles/' + articleId, 'GET', functionsOnSuccess);
}

function loadNumbers() {
    var contentId = 'no_of_read';
    var skeletonId = 'skeleton1';

    hideContentShowSkeletons(contentId, skeletonId);
    var functionsOnSuccess = [
        [showContentHideSkeletons, [contentId, skeletonId, 'response']],
    ];

    setTimeout(() => {
        ajax('/load-numbers', 'GET', functionsOnSuccess);
    }, 0);
}




function ajax(url, method, functionsOnSuccess, form) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if (typeof form === 'undefined') {
        form = new FormData;
    }


    $.ajax({
        url: url,
        type: method,
        async: false,
        data: form,
        processData: false,
        contentType: false,
        dataType: 'json',
        error: function () {
            console.log("ajax error" + url);
        },
        success: function (response) {

            for (var j = 0; j < functionsOnSuccess.length; j++) {
                for (var i = 0; i < functionsOnSuccess[j][1].length; i++) {
                    if (functionsOnSuccess[j][1][i] == "response") {

                        functionsOnSuccess[j][1][i] = response;
                    }
                }

                functionsOnSuccess[j][0].apply(this, functionsOnSuccess[j][1]);
            }
        }
    });
}

function showContentHideSkeletons(contentId, skeletonId, content) {

    if (typeof content === 'undefined') {
        content = 'default';
    }
    var skeleton = $('#' + skeletonId);
    var contentContainer = $('#' + contentId);

    skeleton.addClass('d-none');
    contentContainer.removeClass('d-none');
    if (content != 'default') {
        contentContainer.html(content);
    }

}

function hideContentShowSkeletons(contentId, skeletonId) {
    var skeleton = $('#' + skeletonId);
    var contentContainer = $('#' + contentId);
    skeleton.removeClass('d-none');
    contentContainer.addClass('d-none');
}

$(function () {
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy"
    });
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
