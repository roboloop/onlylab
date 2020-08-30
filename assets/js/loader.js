'use strict';

import $ from 'jquery';

export default class Loader {
    constructor($wrapper) {
        this.$wrapper = $wrapper;

        this.$wrapper.submit(this.handleLoad.bind(this));
    }

    handleLoad() {
        let forums = [];

        this.$wrapper.find('#forum-group-2 option:selected').each((index, el) => {
            forums.push($(el).val());
        });

        if (forums.includes('-1')) {
            forums = null;
        }
        
        const start = this.$wrapper.find('#loader-start-page').val();
        const end = this.$wrapper.find('#loader-end-page').val();
        
        const values = {
            forums,
            start: start ? parseInt(start) : null,
            end: end ? parseInt(end) : null,
        };

        debugger;
        Object.keys(values).forEach((key) => (!values[key]) && delete values[key]);

        const query = $.param(values);
        window.open('/api/loader?' + query, '_blank');

        return false;
    }
}
