'use strict';

$('.ui.rating').rating();

$('.ui.rating').click(function () {
    var film = $(this);
    var rating = film.rating('get rating');
    var filmId = film.attr('data-film-id');

    axios.post('/ratings', { rating: rating, filmId: filmId }).then(function (_ref) {
        // film.rating('set rating', data);

        var data = _ref.data;
    });
});