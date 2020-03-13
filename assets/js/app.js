/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');
require('bootstrap/dist/css/bootstrap.css');

const $ = require('jquery');
require('popper.js');
require('bootstrap');
import VueRouter from 'vue-router'
import routes from './routes';

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
            console.log(data);
            console.log(textStatus);
            console.log(jqXHR);
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
            console.log(data);
            console.log(textStatus);
            console.log(jqXHR);
            const studioTemplate = require('../templates/studios.hbs');
            $('#App').append(studioTemplate({studios: data}));
        });


        break;
    default:
        const defaultTemplate = require('../templates/base.hbs');
        $('#App').append(defaultTemplate);
}

// console.log(window.location);
//
// console.log(router.getMatchedComponents(window.location.pathname));
// console.log(router.match('/genres'));
// console.log(router.match('/studios'));
// console.log(router.match('/studios/qwe'));
// console.log(router.match('/studiosinvalud'));
