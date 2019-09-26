<template>
    <section class="parent container justify-content-center">
        <div v-if="errors">
            <error-component :errors="errors" />
        </div>
        <div v-else>
            <div v-if="inventories === null">
                <p>Wait a moment while we fetch your inventories...</p>
            </div>
            <div v-if="inventories !== null && inventories.length < 1">
                <p>
                    Nothing here yet. Create new inventories or get your users
                    to create inventories.
                </p>
            </div>
            <div v-else>
                <div class="actions">
                    <router-link to="/create">Create new inventory</router-link>
                    <button @click="logout" class="btn btn-danger">
                        Log Out
                    </button>
                </div>
                <inventory
                    v-for="(inventory, key) in inventories"
                    :key="key"
                    :inventory="inventory"
                />
            </div>
        </div>
    </section>
</template>

<script>
import Inventory from '../components/Inventory.vue';
import ErrorComponent from '../components/ErrorComponent.vue';
export default {
    name: 'Dashboard',
    components: {
        ErrorComponent,
        Inventory,
    },
    data() {
        return {
            inventories: null,
            errors: null,
        };
    },

    methods: {
        logout() {
            if (confirm('You are logging out')) {
                axios
                    .post('/api/logout')
                    .then(() => {
                        localStorage.removeItem('token');
                        localStorage.removeItem('is_admin');
                        this.$router.replace('/login');
                    })
                    .catch(error => {
                        alert('Something went wrong. Please try again');
                    });
            }
        },
    },

    created() {
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.headers.common['Authorization'] =
            'Bearer ' + localStorage.getItem('token');
        axios
            .get('/api/inventory')
            .then(response => {
                this.inventories = response.data.data;
            })
            .catch(error => {
                if (error.response.status === 401) {
                    alert('Session expired. Please log in again');
                    localStorage.removeItem('token');
                        localStorage.removeItem('is_admin');
                        this.$router.replace('/login');
                    return;
                }

                this.errors = {
                    message: `Something went wrong on our end please check your internet
                            connection. If problem persists contact our admin. Thanks.`,
                };
            });
    },
};
</script>

<style scoped>
.parent {
    padding: 3rem;
}

.actions {
    margin: 2rem;
    padding: 1rem;
}
</style>
