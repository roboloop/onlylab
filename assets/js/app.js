// Style
import '../css/app.scss';

// Scripts
import '@fortawesome/fontawesome-free/js/all';
import copy from 'copy-to-clipboard';
import $ from 'jquery';
import 'popper.js';
import 'bootstrap';
import VueRouter from 'vue-router'
import routes from './routes';

// Logic
let router = new VueRouter({
    mode: 'history',
    routes,
});

// let routeTo = router.getMatchedComponents(window.location.pathname);
let routeTo = router.match(window.location.pathname);

// console.log(routeTo);
switch(routeTo.name) {
    case 'genres':
        // const genreTemplate = require('../templates/genres.hbs');

        $.ajax({
            url: '/api/genres',
            method: 'GET',
            dataType: 'JSON',
        }).then((data, textStatus, jqXHR) => {
            const genreTemplate = require('../templates/genres.hbs');
            $('#App').append(genreTemplate({genres: data}));
        });
        break;
    case 'studios':
        $.ajax({
            url: '/api/studios',
            method: 'GET',
            dataType: 'JSON',
        }).then((data, textStatus, jqXHR) => {
            const studioTemplate = require('../templates/studios.hbs');
            $('#App').append(studioTemplate({studios: data}));
        });

        break;
    case 'topics':
        $.ajax({
            url: '/api/topics' + window.location.search,
            method: 'GET',
            dataType: 'JSON',
        }).then((data, textStatus, jqXHR) => {
            const topicsTemplate = require('../templates/topics.hbs');
            $('#App').append(topicsTemplate(data));
            $('.topic-row').hover((e) => {
                $(e.currentTarget).find('.dropdown-toggle').dropdown('show');
            }, (e) => {
                $(e.currentTarget).find('.dropdown-toggle').dropdown('hide');
            });
        });
        break;
    case 'topic':
        $.ajax({
            url: '/api/topics/' + routeTo.params.id,
            method: 'GET',
            dataType: 'JSON',
        }).then((data, textStatus, jqXHR) => {
            const topicTemplate = require('../templates/topic.hbs');
            $('#App').append(topicTemplate(data));

            document.title = data.parsedTitle.title ? data.parsedTitle.title : data.parsedTitle.rawTitle;
            $('.carousel-item').on('click', (e) => {
                $('.carousel').carousel('next');
            });

            $(document).keydown(function(e) {
                switch(e.which) {
                    case 37: // left
                        $('.carousel').carousel('prev');
                        return false;
                    case 39: // right
                        $('.carousel').carousel('next');
                        return false;
                }
            });

            $('.copy-btn').on('click', () => {
                let link = $('#link').attr('href');
                copy(link);
            });
        });
        break;
    default:
        const defaultTemplate = require('../templates/base.hbs');
        $('#App').append(defaultTemplate);
}
