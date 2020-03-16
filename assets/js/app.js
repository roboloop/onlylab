// Style
import '../css/app.scss';

// Scripts
import '@fortawesome/fontawesome-free/js/all';
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
            url: '/api/topics',
            method: 'GET',
            dataType: 'JSON',
        }).then((data, textStatus, jqXHR) => {
            const topicsTemplate = require('../templates/topics.hbs');
            $('#App').append(topicsTemplate(data));

            // $('[data-toggle="tooltip"]').tooltip();
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
            $('.carousel-item').on('click', (e) => {
                $('.carousel').carousel('next');
            });
        });
        break;
    default:
        const defaultTemplate = require('../templates/base.hbs');
        $('#App').append(defaultTemplate);
}
