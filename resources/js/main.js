import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/App'
import BootstrapVue from 'bootstrap-vue'
import Breadcrumbs from './components/bread_crumbs'
import firebase from 'firebase/app';
import Vue2Filters from 'vue2-filters'
import VueSweetalert2 from 'vue-sweetalert2';
import VueFormWizard from 'vue-form-wizard';
import VueTour from 'vue-tour'
import Notifications from 'vue-notification'
import { Vue2Dragula } from 'vue2-dragula'
import Toasted from 'vue-toasted';
import SmartTable from 'vuejs-smart-table'
import * as VueGoogleMaps from 'vue2-google-maps'
import { VueMasonryPlugin } from 'vue-masonry'
import VueFeather from 'vue-feather';
import toastr from "./mixins/toastr";

import { Icon } from "leaflet";
delete Icon.Default.prototype._getIconUrl;

// Import Theme scss
// import './assets/scss/app.scss'

// config for firebase
import config from './config.json'

Icon.Default.mergeOptions({
  iconRetinaUrl: require("./assets/images/vue2leaflet/marker-icon.png"),
  iconUrl: require("./assets/images/vue2leaflet/marker-icon.png")
});

Vue.use(VueFeather);

Vue.use(Toasted,{
  iconPack: 'fontawesome'
});
Vue.use(Vue2Dragula);

Vue.use(VueGoogleMaps, {
  load: {
    key: 'API_KEY',
    libraries: 'places',
  },
})

Vue.use(Notifications)
Vue.use(Vue2Filters)
Vue.use(VueSweetalert2);
Vue.use(VueFormWizard)
Vue.use(VueTour)
Vue.use(BootstrapVue)
Vue.use(SmartTable)
Vue.use(require('vue-chartist'))
Vue.use(require('vue-moment'));
Vue.component('Breadcrumbs', Breadcrumbs)
Vue.use(VueMasonryPlugin);
Vue.mixin(toastr);

import '~/plugins'
import '~/components'

Vue.config.productionTip = false
// firebase.initializeApp(config.firebase);
//
// export const db = firebase.firestore();

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App
})
