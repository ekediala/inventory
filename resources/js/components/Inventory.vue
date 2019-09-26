<template>
    <div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ inventory.title }}</h5>

                <h6 v-if="admin" class="card-subtitle mb-2 text-muted">
                    By {{ inventory.user.name }}
                </h6>

                <div class="card-text">
                    <p>
                        <span class="text-primary text-monospace">{{
                            inventory.units
                        }}</span>
                        &nbsp;
                        <span class="text-small">
                            units left
                        </span>
                    </p>

                    <p>$ {{ inventory.price }}</p>

                    <p>{{ inventory.description }}</p>

                    <p>{{ inventory.status }}</p>
                </div>

                <a :href="url" class="card-link">Edit Inventory</a>
                <a href="#" @click.prevent="remove" class="card-link"
                    >Delete Inventory</a
                >

                <a href="#" @click.prevent="use" class="card-link"
                    >Use Inventory</a
                >
            </div>
        </div>
    </div>
</template>

<script>
import ErrorComponent from './ErrorComponent.vue';
import SuccessComponent from './SuccessComponent.vue';
export default {
    name: 'Inventory',
    components: {
        ErrorComponent,
        SuccessComponent,
    },
    props: {
        inventory: {
            required: true,
        },
    },
    data() {
        return {
            admin: false,
            url: `/edit-inventory/${inventory.id}`,
        };
    },

    created() {
        if (localStorage.getItem('is_admin') !== null) {
            this.admin = true;
        }
    },

    methods: {
        use() {
            this.inventory.units -= 1;
            const inventory = this.inventory;
            axios
                .patch('/api/inventory/' + this.inventory.id, {
                    inventory,
                })
                .then(result => {})
                .catch(err => {});
        },

        remove() {
            if (confirm('Are you sure you want to delete inventory?. This is irreversible.')){
                axios.delete('/api/inventory/' + this.inventory.id).then((result) => {

                }).catch((err) => {

                });
            }
        },
    },
};
</script>
