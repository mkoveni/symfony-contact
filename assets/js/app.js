import Vue from 'vue'
import Contact from '../../templates/components/ContactForm'

import SweetAlert from'vue-sweetalert2';

import "@babel/polyfill"




Vue.component('app-contact', Contact)

Vue.use(SweetAlert)

require('../css/app.scss');

const $ = require('jquery');



new Vue({
    el: "#app"
})

