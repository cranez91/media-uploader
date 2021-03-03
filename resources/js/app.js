require('./bootstrap');

window.Vue = require('vue');

//Import Vue Filter
require('./filter'); 

//Import progressbar
require('./progressbar'); 

//Setup custom events 
require('./customEvents'); 

//Import View Router
import VueRouter from 'vue-router'
Vue.use(VueRouter)

//Import Vuetify
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const Vuex = require('vuex');
import  createPersistedState  from  'vuex-persistedstate'

window.store = new Vuex.Store({
  state: {
    images: []
  },
  mutations: {
    setImages(state, value){
      state.images = value;
    },
    getImages(state, value){
      return state.images;
    }
  },
  plugins: [createPersistedState()]
});

//Import Sweetalert2
import Swal from 'sweetalert2'
window.Swal = Swal
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast

//Import v-from
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//Routes
import { routes } from './routes';

//Register Routes
const router = new VueRouter({
    routes, 
    mode: 'hash',

})

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    vuetify: new Vuetify(),
    el: '#app',
    router
});
