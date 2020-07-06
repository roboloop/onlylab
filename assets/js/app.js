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

// Custom scripts
import Search from './search';

// Logic
let router = new VueRouter({
    mode: 'history',
    routes,
});

// let routeTo = router.getMatchedComponents(window.location.pathname);
let routeTo = router.match(window.location.pathname);

// console.log(routeTo);
switch(routeTo.name) {
    case 'forums':
        $.ajax({
            url: '/api/forums',
            method: 'GET',
            dataType: 'JSON',
        }).then((data, textStatus, jqXHR) => {
            const forumTemplate = require('../templates/forums.hbs');
            $('#App').append(forumTemplate({forums: data}));
        });
        break;
    case 'genres':
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
            $('#App').append(studioTemplate({groups: data}));
            $('.js-studio-list').on('click', '.js-change-studio-status', e => {
                const $li = $(e.currentTarget).closest('li');
                const id = $li.attr('data-id');
                const type = $li.attr('data-type');
                switch (type) {
                    case 'typical':
                        $.ajax({
                            url: '/api/studios/' + id + '/prefer',
                            method: 'POST',
                            dataType: 'JSON',
                            context: $li,
                        }).then((data, textStatus, jqXHR) => {
                            const studioItem = require('../templates/partials/_studio_item.hbs');
                            $li.replaceWith(studioItem(data));

                        });
                        break;
                    case 'preferable':
                        $.ajax({
                            url: '/api/studios/' + id + '/ban',
                            method: 'POST',
                            dataType: 'JSON',
                        }).then((data, textStatus, jqXHR) => {
                            const studioItem = require('../templates/partials/_studio_item.hbs');
                            $li.replaceWith(studioItem(data));

                        });
                        break;
                    default:
                        $.ajax({
                            url: '/api/studios/' + id + '/typical',
                            method: 'POST',
                            dataType: 'JSON',
                        }).then((data, textStatus, jqXHR) => {
                            const studioItem = require('../templates/partials/_studio_item.hbs');
                            $li.replaceWith(studioItem(data));
                        });
                        break;
                }
            });
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

    case 'search':
        $.ajax({
            url: '/api/search-data',
            method: 'GET',
            dataType: 'JSON',
        }).then((data, textStatus, jqXHR) => {
            const searchTemplate = require('../templates/search.hbs');
            let $app = $('#App').append(searchTemplate(data));
            $app.find('#studio-status-typical').prop('checked', true);
            $app.find('#studio-status-preferable').prop('checked', true);

            let $searchModule = new Search($app.find('#search-block'));
        });
        break;
    default:
        const defaultTemplate = require('../templates/base.hbs');
        $('#App').append(defaultTemplate);
}
