<template>
    <div>
        <div v-if="message" class="alert alert-info">
            {{ message }}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users" :key="user.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ user.user_detail.firstname }}</td>
                        <td>{{ user.user_detail.lastname }}</td>
                        <td>{{ user.username }}</td>
                        <td>{{ user.user_detail.email }}</td>
                        <td>{{ user.usertype }}</td>
                        <td>
                            <button @click="editUser(user)" class="btn btn-primary mx-1">Edit</button>
                            <button @click="openDeleteModal(user.id)" class="btn btn-danger mx-1">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" :class="{ show: isEditModalOpen }" style="display: isEditModalOpen ? 'block' : 'none';" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" @click="isEditModalOpen = false" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateUser">
                        <input v-model="editUserData.user_detail.firstname" class="form-control mb-3" placeholder="First Name" />
                        <input v-model="editUserData.user_detail.lastname" class="form-control mb-3" placeholder="Last Name" />
                        <input v-model="editUserData.username" class="form-control mb-3" placeholder="Username" />
                        <input v-model="editUserData.user_detail.email" class="form-control mb-3" placeholder="Email" />
                        <input v-model="editUserData.usertype" class="form-control mb-3" placeholder="User Type" />
                        <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" :class="{ show: isDeleteModalOpen }" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" style="display: isDeleteModalOpen ? 'block' : 'none';">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" @click="closeDeleteModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this item?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeDeleteModal">Cancel</button>
                        <button type="button" class="btn btn-danger" @click="deleteUser">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                users: [],
                isEditModalOpen: false,
                isDeleteModalOpen: false,
                editUserData: {
                    user_detail: {
                            firstname: '',
                            lastname: '',
                            email: ''
                        },
                    username: '',
                    usertype: ''
                },
                message: '',
                userId: ''
            };
        },
        methods: {
            fetchUsers() {
            axios.get('/api/user')
                .then(response => {
                    this.users = response.data.data;
                    console.log(response.data.data);
                })
                .catch(error => {
                    console.error(error);
                });
            },
            openDeleteModal(id) {
                this.userId = id;
                this.isDeleteModalOpen = true;
            },
            closeDeleteModal() {
                this.isDeleteModalOpen = false;
                this.userId = null;
            },
            deleteUser() {
                axios.delete(`/api/user/${this.userId}`)
                    .then(() => {
                        this.isDeleteModalOpen = false;
                        this.fetchUsers();
                        this.message = 'User deleted successfully!';
                    })
                    .catch(error => {
                        console.error(error);
                    });
                },
            editUser(user) {
                this.editUserData = { ...user };
                this.isEditModalOpen = true;
            },
            updateUser() {
                axios.put(`/api/user/${this.editUserData.id}`, this.editUserData)
                    .then(() => {
                        this.isEditModalOpen = false;
                        this.fetchUsers();
                        this.message = 'User updated successfully!';
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        },
        mounted() {
            this.fetchUsers();
        }
    };
</script>