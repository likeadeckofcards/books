require('./bootstrap');

require('alpinejs');

import Vue from 'vue';
import route from 'ziggy-js';
import { Ziggy } from './ziggy';

let hostname = location.hostname;
if(location.port) {
    hostname += `:${location.port}`;
}
Ziggy.baseUrl = location.protocol + '//' + hostname;
Ziggy.url = location.protocol + '//' + hostname;
Ziggy.baseProtocol = location.protocol;
Ziggy.baseDomain = hostname;

Vue.mixin({
    methods: {
        route: (name, params, absolute, config = Ziggy) => route(name, params, absolute, config),
    },
});

Vue.component('book-list', require('@/components/BookList').default);

const app = new Vue({
    el: '#app',

});
