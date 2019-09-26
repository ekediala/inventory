<template>
    <div class="main">
        <div v-if="!loaded">
            <p>
                Please wait a moment...
            </p>
        </div>
        <div v-else>
            <form @submit.prevent="reset()">
                <div class="form-group">
                    <h3 class="text-uppercase text-info">Reset Password</h3>
                </div>

                <div v-if="errors" class="form-group">
                    <error-component :errors="errors"/>
                </div>
                <div v-if="message" class="form-group">
                    <success-component :message="message"/>
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input
                        required
                        minlength="8"
                        v-model="payload.password"
                        type="password"
                        id="password"
                        :disabled="sending"
                        class="form-control form-control-lg"
                    />
                </div>
                <button :disabled="sending" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
</template>

<script>
import ErrorComponent from '../components/ErrorComponent.vue';
import SuccessComponent from '../components/SuccessComponent.vue';
export default {
    name: 'NewPassword',
    components: { ErrorComponent, SuccessComponent },
    data() {
        return {
            sending: false,
            message: null,
            errors: null,
            loaded: false,
            payload: {
                email: '',
                token: '',
                password: '',
            },
        };
    },

    methods: {
        reset() {
            const payload = this.payload;
            this.sending = true;
            axios
                .post('/api/password/reset', payload)
                .then(response => {
                    this.message = response.data.status;
                })
                .catch(error => {
                    if (error.response.status === 404) {
                        this.errors = {
                            message: `Sorry, seems token is invalid or has expired.
                            Please request another from the login page`,
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

    created() {
        const token = this.$route.params.token;
        axios
            .get('/api/password/find/' + token)
            .then(result => {
                let response = result.data;
                this.payload.email = response.email;
                this.payload.token = response.token;
            })
            .catch(error => {
                if (error.response.status === 404) {
                    this.errors = {
                        message: `Sorry, seems token is invalid or has expired.
                            Please request another from the login page`,
                    };
                    return;
                }
            }).finally(() => {
                this.loaded = true;
            });
    },
};
</script>
