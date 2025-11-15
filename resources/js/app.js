/**
 * We'll load Axios and the Bootstrap plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

import EmailBuilder from '@usewaypoint/email-builder';



//import grapesjs from 'grapesjs';
// import gjsBlocksBasic from 'grapesjs-blocks-basic';
// import gjsBlocksFlexbox from 'grapesjs-blocks-flexbox';
// import forms from 'grapesjs-plugin-forms';
// import navbar from 'grapesjs-navbar';
// import gjsPresetWebpage from 'grapesjs-preset-webpage';
// import rtgrape from 'grapesjs-rte-extensions';
try {
    // document.addEventListener('DOMContentLoaded', () => {
    //     const container = document.getElementById('my-builder');

    //     if (container) {
    //         const builder = new EmailBuilder({
    //             container, // ini kuncinya: masukkan ke div buatan sendiri
    //             autosave: false,
    //             showToolbar: true,
    //             blocks: {
    //                 // Optional, kamu bisa kostumisasi blok-bloknya
    //             },
    //         });

    //         // Kalau kamu ingin akses instance-nya global:
    //         window.emailBuilder = builder;
    //     }
    // });
//     document.addEventListener('DOMContentLoaded', function () {
//         const swv = 'sw-visibility';
//         const expt = 'export-template';
//         const osm = 'open-sm';
//         const otm = 'open-tm';
//         const ola = 'open-layers';
//         const obl = 'open-blocks';
//         const ful = 'fullscreen';
//         const prv = 'preview';

//         if (document.querySelector('#gjs')) {
//             // Pastikan ada elemen target
//             const editor = grapesjs.init({
//                 container: '#gjs',
//                 layerManager: {
//                     appendTo: '.layers-container',
//                 },
//                 fromElement: true,
//                 storageManager: false,
//                 plugins: [
//                     gjsBlocksBasic,
//                     gjsBlocksFlexbox,
//                     gjsPresetWebpage,
//                     forms,
//                     navbar,
//                     rtgrape,
//                 ],
//                 panels: { defaults: [] },
//             });

//         }
//     });


    // Axios modules
    window.axios = require('axios');
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // Popper.js that required by bootstrap popovers and tooltips
    require('@popperjs/core');

    // Bootstrap v5 Vanilla.js
    window.bootstrap = require('bootstrap');

    // UI block
    window.UI = require('./ui-block');

    [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function (el) {
        return new bootstrap.Popover(el, {
            el: 'focus',
            html: true,
        });
    });

    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Tom seelct
    require('tom-select');

    // Custom
    require('./d-bootstrap/sidebar');
    require('./d-bootstrap/daterangepicker');
    require('./custom');
} catch (e) {}
