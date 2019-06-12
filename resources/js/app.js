import Vue from 'vue'
import VueRouter from 'vue-router'
import BootstrapVue from 'bootstrap-vue'
import VueCookie from 'vue-cookie'
import Axios from 'axios'
import CxltToastr from 'cxlt-vue2-toastr'
import EventBus from './eventbus'

import App from './components/pages/App'

import TaskList from './components/TaskList'
import Login from './components/auth/Login'
import Register from './components/auth/Register'

import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'

import '../css/style.css'

Vue.use(VueCookie)
Vue.use(VueRouter)
Vue.use(BootstrapVue)

Vue.use(CxltToastr, {
    position: 'bottom right',
    showDuration: 2000,
    timeOut: 5000,
    progressBar: true
})

/* Router (move this to a separate file?) */
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'Overview',
            component: TaskList,
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            props: true,
            meta: {
              requiresGuest: true,
            }
        },
        {
            path: '/register',
            name: 'Register',
            component: Register,
            meta: {
              requiresGuest: true,
            }
        },
    ],
});

router.beforeEach((to, from, next) => {
   var token = Vue.cookie.get('todo_token'); //Get the token
   
   // If logged in, set the authorization header for future api requests 
    if (token != null){
      Axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    }
    
    //If the route is for logged in users only
    if (to.matched.some(record => record.meta.requiresAuth)) {
      if (token == null) { //Redirect to login route if not logged in
        next({
          name: 'Login',
          query: { redirect: to.fullPath }
        })
      } else { //Allow the access to the route
        next()
      }
    } 
    //If the route is for guests (not logged in)
    else if (to.matched.some(record => record.meta.requiresGuest)){
      if (token != null) { //Redirect to the overview route if logged in
        next({
          name: 'Overview',
          query: { redirect: to.fullPath }
        })
      } else {
        next() //Allow the access to the route
      }
    } else {
      next() //If any other route, access it
    }
  })

Vue.prototype.$http = Axios; //set global axios var
Vue.prototype.$bus = EventBus //set global eventbus var

new Vue({
    el: '#app',
    components: { App },
    router,
});