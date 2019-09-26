<template>
    <div class="main">
        <form @submit.prevent="login">
            <div class="form-group">
                <h3 class="text-info form-control-sm text-uppercase">Log in</h3>
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
                <label for="password">Password</label>
                <input
                    type="password"
                    class="form-control form-control-"
                    id="password"
                    placeholder="Password"
                    v-model="password"
                    minlength="8"
                    :disabled="sending"
                />
            </div>
            <button :disabled="sending" type="submit" class="btn btn-primary">
                Login
            </button>
            <router-link to="/reset">Forgot Password?</router-link>
        </form>
    </div>
</template>

<script>
import ErrorComponent from '../components/ErrorComponent.vue';
export default {
    name: 'Login',
    components: {
        ErrorComponent,
    },
    data() {
        return {
            email: '',
            password: '',
            sending: false,
            errors: null,
        };
    },
    methods: {
        login() {
            const email = this.email;
            const password = this.password;
            this.sending = true;
            axios
                .post('/api/login', {
                    email,
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
                    if (error.response.status === 401) {
                        this.errors = {
                            message: 'Incorrect username or password',
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
                        message:
                            `Something went wrong on our end please check your internet
                            connection. If problem persists contact our admin. Thanks.`,
                    };
                })
                .finally(() => {
                    this.sending = false;
                });
        },
    },

    beforeRouteEnter(to, from, next) {
        if (localStorage.getItem('token') !== null) {
            return next('/');
        }

        next();
    },
};
</script>
