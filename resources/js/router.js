import VueRouter from 'vue-router';
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin',
            name: 'Admin',
            component:'',
            meta: {
                auth: true
            }
        },
        {
            path: '/',
            name: 'Public',
            component: ''
        }
    ]
});
