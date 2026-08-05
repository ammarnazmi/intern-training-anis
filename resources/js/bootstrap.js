// Global constants
Object.defineProperties(window, {
    IS_BOOTSTRAP4: {
        value: IS_BOOTSTRAP4,
    },
    IS_BOOTSTRAP5: {
        value: IS_BOOTSTRAP5,
    },
    PageData: {
        value: {},
    },
});

// Bootstrap
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// axios
import axios from 'axios';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios = axios;

// Language Translator
import Lang from 'vendor/apih/laravel-jslang/resources/js/lang';
window.Lang = Lang;
window.__ = Lang.get;
window.trans = Lang.get;
window.trans_choice = Lang.choice;

// Validator
import Validator from 'vendor/onpay/laravel-core/resources/js/validator';
window.Validator = Validator;

// Helper functions
import Helper from 'vendor/onpay/laravel-core/resources/js/helper';
window.Helper = Helper;
window.eh = Helper.escapeHtml;

// abmodal
import abmodal from 'vendor/onpay/laravel-core/resources/js/abmodal';
window.abmodal = abmodal;

// zcache
import zcache from 'vendor/onpay/laravel-core/resources/js/zcache';
window.zcache = zcache;
zcache.runGarbageCollection();

// zroute
import zroute from 'vendor/onpay/laravel-core/resources/js/zroute';
window.zroute = zroute;

// useEventStream
import useEventStream from 'vendor/onpay/laravel-core/resources/js/use-event-stream';
window.useEventStream = useEventStream;

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Extend Alpine
import extendAlpine from 'vendor/onpay/laravel-core/resources/js/alpine-extend';
extendAlpine(Alpine);

// Alpine plugins
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';
import persist from '@alpinejs/persist';
import alpinePlugins from 'vendor/onpay/laravel-core/resources/js/alpine-plugins';

Alpine.plugin(collapse);
Alpine.plugin(intersect);
Alpine.plugin(persist);
Alpine.plugin(alpinePlugins);

// Alpine components
import AlpineComponents from './alpine-components';
window.AlpineComponents = AlpineComponents;

// Bolt
import Bolt from 'vendor/onpay/laravel-core/resources/js/bolt';
window.Bolt = Bolt;
