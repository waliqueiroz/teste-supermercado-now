import Vue from 'vue'
import VueRouter from 'vue-router'
import { AclRule } from "vue-acl";
import store from '../store'
import Login from '../views/login/Index.vue'
import Template from '../components/Template.vue'
import HomeIndex from '../views/home/Index.vue'
import ProductsIndex from '../views/products/Index.vue'
import ProductsForm from '../views/products/Form.vue'
import UsersIndex from '../views/users/Index.vue'
import UsersForm from '../views/users/Form.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      rule: new AclRule("public").generate()
    }
  },
  {
    path: '/',
    component: Template,
    meta: {
      requiresAuth: true,
      rule: new AclRule("public").generate()
    },
    children: [
      /**Rotas produtos */
      {
        path: '',
        alias: 'home',
        name: 'Home 0',
        meta: {
          description: "Fluxo de aprovação",
          rule: new AclRule("product.index").generate()
        },
        component: HomeIndex
      },
      {
        path: 'products',
        name: 'Products 0',
        meta: {
          description: "Produtos",
          rule: new AclRule("product.index").generate()
        },
        component: ProductsIndex
      },
      {
        path: 'products/create',
        name: 'Products 1',
        meta: {
          description: "Produtos",
          rule: new AclRule("product.store").generate()
        },
        component: ProductsForm
      },
      {
        path: 'products/:id/edit',
        name: 'Products 2',
        meta: {
          description: "Produtos",
          rule: new AclRule("product.update").generate()
        },
        component: ProductsForm
      },

      /**Rotas usuários */
      {
        path: 'users',
        name: 'Users 0',
        meta: {
          description: "Usuários",
          rule: new AclRule("user.index").generate()
        },
        component: UsersIndex
      },
      {
        path: 'users/create',
        name: 'Users 1',
        meta: {
          description: "Usuários",
          rule: new AclRule("user.store").generate()
        },
        component: UsersForm
      },
      {
        path: 'users/:id/edit',
        name: 'Users 2',
        meta: {
          description: "Usuários",
          rule: new AclRule("user.update").generate()
        },
        component: UsersForm
      },
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  linkExactActiveClass: 'active',
  routes
})

/* Middleaware executado antes de prosseguir pra rota solicitada */
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) { // Se o parametro meta.requiresAuth da rota solicitada for true
    if (localStorage.getItem(store.state.TOKEN_KEY)) { // Se já existir token de autenticação no storage
      next() // Prossiga pra rota solicitada
    } else {
      console.log('Não autenticado!')
      next({ path: '/login' }) // Retorna pro componente login
    }
  } else { // Se a rota não estiver protegida (requiresAuth)
    next() // Prossiga pra rota solicitada
  }
});

export default router
