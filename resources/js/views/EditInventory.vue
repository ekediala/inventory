<template>
    <div class="parent">
        <form @submit.prevent="create">
            <div v-if="!loaded">
                One moment...
            </div>
            <div v-else class="form-group">
                <h3 class="text-info text-uppercase">New Inventory</h3>
            </div>

            <div v-if="errors" class="form-group mid">
                <error-component :errors="errors" />
            </div>
            <div v-if="message" class="form-group mid">
                <success-component :message="message" />
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input
                    type="text"
                    required
                    v-model="inventory.title"
                    class="form-control form-control-lg"
                    placeholder="Enter inventory title"
                    :disabled="sending"
                />
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input
                    type="text"
                    required
                    v-model="inventory.category"
                    class="form-control form-control-lg"
                    placeholder="Enter inventory category"
                    :disabled="sending"
                />
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input
                    type="number"
                    required
                    v-model="inventory.price"
                    class="form-control form-control-lg"
                    placeholder="Enter inventory price"
                    :disabled="sending"
                />
            </div>
            <div class="form-group">
                <label for="units">Units</label>
                <input
                    type="number"
                    required
                    v-model="inventory.units"
                    class="form-control form-control-lg"
                    placeholder="Enter inventory title"
                    :disabled="sending"
                />
            </div>
            <div class="form-group">
                <div>
                    Status:
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input
                            type="radio"
                            class="form-check-input"
                            v-model="inventory.status"
                            value="available"
                            checked
                        />
                        Available
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input
                            type="radio"
                            v-model="inventory.status"
                            class="form-check-input"
                            value="unavailable"
                        />
                        Unavailable
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="title">Description</label>
                <textarea
                    type="text"
                    required
                    v-model="inventory.description"
                    class="form-control form-control-lg"
                    placeholder="Describe inventory"
                    :disabled="sending"
                    cols="8"
                ></textarea>
            </div>
            <button :disabled="sending" type="submit" class="btn btn-primary">
                Update Inventory
            </button>
        </form>
    </div>
</template>

<script>
import ErrorComponent from '../components/ErrorComponent.vue';
import SuccessComponent from '../components/SuccessComponent.vue';
export default {
    name: 'CreateInventory',
    components: {
        ErrorComponent,
        SuccessComponent,
    },
    data() {
        return {
            message: null,
            errors: null,
            sending: false,
            loaded: false,
            inventory: {
                title: '',
                category: '',
                description: '',
                price: '',
                units: '',
                status: 'checked',
            },
        };
    },

    created() {
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.headers.common['Authorization'] =
            'Bearer ' + localStorage.getItem('token');
        axios
            .get('/api/inventory/' + this.$route.params.id)
            .then(result => {
                this.inventory = result.data;
            })
            .catch(error => {
                if (error.response.status === 404) {
                    this.errors = {
                        message: `Sorry, seeems that inventory is invalid or has been deleted.
                            Please try another.`,
                    };
                    return;
                }

                this.errors = {
                    message: `Something went wrong on our end please check your internet
                            connection. If problem persists contact our admin. Thanks.`,
                };
            })
            .finally(() => {
                this.loaded = true;
            });
    },

    methods: {
        create() {
            this.sending = true;
            const inventory = this.inventory;
            axios
                .post('/api/inventory', inventory)
                .then(result => {
                    this.message = 'Updated successfully';
                    for (const key in this.inventory) {
                        this.inventory[key] = '';
                    }
                })
                .catch(error => {
                    if (error.response.status === 401) {
                        alert('Session expired. Please log in again');
                        localStorage.removeItem('token');
                        localStorage.removeItem('is_admin');
                        this.$router.replace('/login');
                        return;
                    }

                    if (error.response.status === 422) {
                        let errors = Object.values(error.response.data.errors);
                        errors = errors.flat();
                        this.errors = errors;
                        return;
                    }

                    this.errors = {
                        message: `Something went wrong on our end please check your internet
                            connection. If problem persists contact our admin. Thanks.`,
                    };
                })
                .finally(() => {
                    this.sending = false;
                });
        },
    },
};
</script>

<style scoped>
.parent {
    display: flex;
    margin: 3rem auto;
    justify-content: center;
}

.mid {
    max-width: 20rem;
}
</style>
