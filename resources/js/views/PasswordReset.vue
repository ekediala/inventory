<template>
    <div class="main container justify-content-center">
        <form @submit.prevent="reset()">
            <div class="form-group">
                <h3 class="text-info text-uppercase">Reset Password</h3>
            </div>
            <div v-if="message" class="form-group">
                <success-component :message="message" />
            </div>
            <div v-if="errors" class="form-group">
                <error-component :errors="errors" />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    required
                    id="email"
                    :disabled="sending"
                    v-model="email"
                    class="form-control form-control-lg"
                    type="email"
                />
            </div>
            <button :disabled="sending" class="btn btn-primary" type="submit">Reset Password</button>
        </form>
    </div>
</template>

<script>
import SuccessComponent from '../components/SuccessComponent.vue';
import ErrorComponent from '../components/ErrorComponent.vue';
export default {
    name: 'Reset',
    components: { SuccessComponent, ErrorComponent },
    data() {
        return {
            email: '',
            sending: false,
            errors: null,
            message: false,
        };
    },
    methods: {
        reset() {
            let email = this.email;
            this.sending = true;
            axios
                .post('/api/password/create', { email })
                .then(response => {
                    this.message = response.data.message;
                })
                .catch(error => {
                    if (error.response.status === 404) {
                        this.errors = {
                            message: "Sorry, we can't find a user with that email"
                        };

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
    form {
        max-width: 4rem;
    }
</style>
