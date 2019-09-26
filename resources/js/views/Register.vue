<template>
    <div>
        <div class="main">
            <form @submit.prevent="signup">
                <div class="form-group">
                    <h3 class="text-info text-uppercase">Register</h3>
                </div>
                <div v-if="errors" class="form-group">
                    <error-component :errors="errors" />
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        class="form-control form-control-lg"
                        id="email"
                        v-model="email"
                        placeholder="Enter email"
                        required
                        :disabled="sending"
                    />
                </div>

                <div class="form-group">
                    <label for="email">Name</label>
                    <input
                        type="text"
                        class="form-control form-control-lg"
                        id="name"
                        v-model="name"
                        placeholder="Your name"
                        required
                        :disabled="sending"
                    />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        class="form-control form-control-"
                        id="password"
                        placeholder="Password"
                        v-model="password"
                        required
                        minlength="8"
                        :disabled="sending"
                    />
                </div>
                <button
                    :disabled="sending"
                    type="submit"
                    class="btn btn-primary"
                >
                    Sign up
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import ErrorComponent from '../components/ErrorComponent.vue';
export default {
    name: 'Register',
    components: { ErrorComponent },
    data() {
        return {
            errors: null,
            name: '',
            email: '',
            password: '',
            sending: false,
        };
    },

    methods: {
        signup() {
            const email = this.email;
            const name = this.name;
            const password = this.password;
            this.sending = true;
            axios
                .post('api/user', {
                    email,
                    name,
                    password,
                })
                .then(response => {
                    const token = response.data.access_token;
                    if (response.data.is_admin){
                        localStorage.setItem('is_admin', true);
                    }
                    localStorage.setItem('token', token);
                    this.$router.replace('/');
                })
                .catch(error => {
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
