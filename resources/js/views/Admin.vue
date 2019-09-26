<template>
    <div class="container justify-content-center p-3">
        <div v-if="errors">
            <error-component :errors="errors" />
        </div>
        <div v-else>
            <div v-if="inventories === null">
                <p>Please wait a moment while we fetch your data.</p>
            </div>
            <div v-if="inventories !== null && inventories.length < 1">
                <p>
                    Nothing here yet. Create new inventories or get your users
                    to create inventories.
                </p>
                <router-link to="/create">Create new inventory</router-link>
            </div>
            <div v-else>
                <inventory
                    v-for="(inventory, key) in inventories"
                    :key="key"
                    :inventory="inventory"
                />
            </div>
        </div>
    </div>
</template>

<script>
import Inventory from '../components/Inventory.vue';
import ErrorComponent from '../components/ErrorComponent.vue';
export default {
    name: 'Admin',
    components: {
        Inventory,
        ErrorComponent,
    },
    data() {
        return {
            inventories: null,
            errors: null,
        };
    },

    created() {
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.headers.common['Authorization'] =
            'Bearer ' + localStorage.getItem('token');
        axios
            .get('/api/admin/inventories')
            .then(response => {
                this.inventories = response.data.inventories.data;
            })
            .catch(error => {
                this.errors = {
                    message: `Something went wrong on our end please check your internet
                            connection. If problem persists contact our admin. Thanks.`,
                };
            });
    },

    beforeRouteEnter(to, from, next) {
        if (localStorage.getItem('is_admin') === null) {
            return next('/');
        }

        next();
    },
};
</script>
