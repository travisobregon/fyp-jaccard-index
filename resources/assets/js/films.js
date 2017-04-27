$('.ui.rating').rating();

$('.ui.rating').click(function () {
    const film = $(this);
    const rating = film.rating('get rating');
    const filmId = film.attr('data-film-id');

    axios.post('/ratings', { rating, filmId })
        .then(function ({ data }) {
            // film.rating('set rating', data);
        });
});