import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import VueFlashMessage from 'vue-flash-message';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import pagination from 'laravel-vue-pagination';
import GlobalFunctions from './plugins/globalFunctions';
import { AclInstaller, AclCreate } from 'vue-acl';
Vue.use(AclInstaller)

Vue.component('laravue-pagination', pagination);

Vue.use(VueSweetalert2);
Vue.use(VueFlashMessage);
Vue.use(GlobalFunctions);

Vue.config.productionTip = false

/* Instanciando o vue acl (Permissões) */
var acl = new AclCreate({
  initial: 'public',
  notfound: '/',
  router,
  acceptLocalRules: false,
})

new Vue({
  router,
  store,
  acl,
  render: h => h(App)
}).$mount('#app')
