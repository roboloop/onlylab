'use strict';

import $ from 'jquery';

export default class Search {
    constructor($wrapper) {
        this.$wrapper = $wrapper;

        this.$wrapper.submit(this.handleSearch.bind(this));
    }

    handleSearch() {
        let forums = [];

        this.$wrapper.find('#forum-group-1 option:selected').each((index, el) => {
            forums.push($(el).val());
        });

        if (forums.includes('-1')) {
            forums = null;
        }

        const genres = this.$wrapper.find('#search-genres').val();
        const studios = this.$wrapper.find('#search-studios').val();
        const title = this.$wrapper.find('#search-title').val();
        const rawTitle =  this.$wrapper.find('#search-raw-title').val();
        const year = this.$wrapper.find('#search-year').val();
        const quality = this.$wrapper.find('#search-quality').val();

        let studioStatuses = [];
        this.$wrapper.find('.js-studio-status-checkbox:checked').each((index, el) => {
            studioStatuses.push($(el).val());
        });
        if (!studioStatuses) {
            studioStatuses = null;
        }

        const values = {
            forums,
            genres,
            studios,
            title,
            rawTitle,
            year,
            quality,
            studioStatuses
        };

        Object.keys(values).forEach((key) => (!values[key]) && delete values[key]);

        const query = $.param(values);
        window.open('/topics?' + query, '_blank');

        return false;
    }
}
