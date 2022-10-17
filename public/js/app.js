$(function() {
    $('main').on('click', '.btn-toggle', function() {
        $(this).children('.round').toggleClass('active');

        // Is not an image
        if (!$(this).children('.round').children('input').prop('checked')) {
            $(this).children('.round').children('input').prop('checked', true);
        } else {
            $(this).children('.round').children('input').prop('checked', false);
        }

        $(this).parent().parent().children('.textarea').toggleClass('is-active');
        $(this).parent().parent().children('.url').toggleClass('is-active');
    });

    $('.add-media').click(function(e) {
        e.preventDefault();
        const index = $('.card').length;
        $('button.add-media').before('' +
            '<div class="card">' +
            '<span class="card-title">Add media<i class="bi bi-x-circle remove-media"></i></span>' +
            '<div class="form-btn"><span>Url</span>' +
            '<div class="btn-toggle"><div class="round">' +
            '<input type="checkbox" name="trick_form[trickMedia]['+ index +'][isImage]" checked></div></div>' +
            '</div><div class="textarea is-active"><label for="embed">Video</label>' +
            '<textarea id="embed" name="trick_form[trickMedia]['+ index +'][embed]" placeholder=\'Ex: <iframe width="560" height="315" src="https://www.youtube.com/embed/monyw0mnLZg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>\'></textarea></div><div class="url"><label for="url">URL de l\'image</label><input type="url" id="url" class="white" name="trick_form[trickMedia]['+ index +'][url]" placeholder="Ex: https://img.redbull.com/images"></div></div>');
    });

    $('main').on('click', '.remove-media', function() {
        $(this).parent().parent().remove();
    });

    $('button.show-media').on('click', () => {
        $('.trick-media-list').toggleClass('is-active');

        if ($('.trick-media-list').hasClass('is-active')) {
            $('button.show-media span').text('Hide them');
        } else {
            $('button.show-media span').text('See them');
        }
    });
});
