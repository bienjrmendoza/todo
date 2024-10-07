<template>
    <div class="register-container mt-5">
        <h2 class="text-center">REGISTER</h2>
        <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
        <form @submit.prevent="registerUser">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" placeholder="First Name" v-model="form.firstname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" placeholder="Last Name" v-model="form.lastname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" placeholder="Username" v-model="form.username" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" placeholder="Email" v-model="form.email" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="confirmPassword" class="form-label">User Type</label>
                    <select class="form-select" id="exampleSelect" v-model="form.usertype" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" v-model="form.password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" v-model="form.confirm_password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <div class="mt-3 text-center">
                <small>Already have an account? <a href="login">Login here</a></small>
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
                firstname: '',
                lastname: '',
                email: '',
                password: '',
                usertype: ''
            },
                errorMessage: '',
            };
        },
        methods: {
            async registerUser() {
            this.errorMessage = '';

            try {
                const response = await axios.post('/api/register', this.form);

                if (response.data.status === 'Success') {
                    window.location.href = '/home';
                } else if (response.data.status === 'Fail' && response.data.errors) {
                    this.errorMessage = Object.values(response.data.errors)
                        .flat()
                        .join(' ');
                }
            } catch (error) {
                this.errorMessage = 'Registration failed. Please try again.';
            }
            },
        },
    };
</script>

<style scoped>
    .register {
        max-width: 400px;
        margin: auto;
    }
    .error {
        color: red;
    }
</style>
