import VueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(VueRouter);

import Admin from './views/Admin';
import CreateInventory from './views/CreateInventory';
import Dashboard from './views/Dashboard';
import Login from './views/Login';
import Register from './views/Register';
import PasswordReset from './views/PasswordReset';
import NewPassword from './views/NewPassword.vue';
import EditInventory from './views/EditInventory.vue';


const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin',
            name: 'Admin',
            component: Admin,
            meta: {
                requiresAuth: true,
            },
        },

        {
            path: '/login',
            name: 'login',
            component: Login,
        },

        {
            path: '/',
            name: 'Dashboard',
            component: Dashboard,
            meta: {
                requiresAuth: true,
            },
        },

        {
            path: '/register',
            name: 'Register',
            component: Register,
        },

        {
            path: '/create',
            name: 'Create',
            component: CreateInventory,
            meta: {
                requiresAuth: true,
            },
        },

        {
            path: '/reset',
            name: 'Reset Password',
            component: PasswordReset
        },

        {
            path: '/password-reset/:token',
            name: 'Password Change',
            component: NewPassword
        },

        {
            path: '/edit-inventory/:id',
            name: 'Edit Inventory',
            component: EditInventory,
            meta: {
                requiresAuth: true
            }
        }
    ],
});

router.beforeEach((to, from, next) => {
  let currentUser = localStorage.getItem('token');
  let requiresAuth = to.matched.some(record => record.meta.requiresAuth);

  if (requiresAuth && !currentUser) next('login');
  else next();
});



export default router;
