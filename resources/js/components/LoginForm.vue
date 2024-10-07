<template>
    <div class="login-container">
        <h3 class="text-center mb-4">Login</h3>
        <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
        <form @submit.prevent="login">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Username" v-model="form.username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter password" v-model="form.password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
            <div class="mt-3 text-center">
                <p>Don't have an account? <a href="/register">Register here</a></p>
                <a href="#">Forgot password?</a>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
            form: {
                username: '',
                password: ''
            },
                errorMessage: '',
            };
        },
        methods: {
            async login() {
                try {
                    const response = await axios.post('/api/login', this.form);

                    if (response.data.status === 'Success') {
                        localStorage.setItem('user', JSON.stringify(response.data));
                        window.location.href = '/home';
                    } else if (response.data.status === 'Fail' && response.data.errors) {
                        this.errorMessage = Object.values(response.data.errors)
                            .flat()
                            .join(' ');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }
    };
</script>

<style scoped>
    .register {
        max-width: 400px;
        margin: auto;
    }
    .error {
        color: red;
        letter-spacing: 0.1em;
    }
</style>
