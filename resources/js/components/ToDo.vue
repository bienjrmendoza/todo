<template>
    <div>
        <div class="container">
            <form @submit.prevent="addTodo" class="mb-4">
                <div class="form-group mb-3">
                    <label for="todoTitle" class="mb-1">Title</label>
                    <input type="text" id="todoTitle" v-model="newTodo.todo" class="form-control" placeholder="Enter Todo Title" required/>
                </div>
                <div class="form-group mb-3">
                    <label for="todoDescription" class="mb-1">Description</label>
                    <textarea id="todoDescription" v-model="newTodo.description" class="form-control" placeholder="Enter Todo Description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Add Todo</button>
            </form>
        </div>
        <div v-if="message" class="alert alert-info">
            {{ message }}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Todo</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(todo, index) in todos" :key="todo.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ todo.todo }}</td>
                        <td>{{ todo.description }}</td>
                        <td>{{ todo.status }}</td>
                        <td>
                            <button @click="editTodo(todo)" class="btn btn-primary mx-1">Edit</button>
                            <button @click="openDeleteModal(todo.id)" class="btn btn-danger mx-1">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" :class="{ show: isEditModalOpen }" style="display: isEditModalOpen ? 'block' : 'none';" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit Todo</h5>
                        <button type="button" class="btn-close" @click="isEditModalOpen = false" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateTodo">
                            <input v-model="editTodoData.todo" class="form-control mb-3" placeholder="Todo" />
                            <input v-model="editTodoData.description" class="form-control mb-3" placeholder="Description" />
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select mb-3" id="exampleSelect" v-model="editTodoData.status" >
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                            </select>
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
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Todo</h5>
                        <button type="button" class="btn-close" @click="closeDeleteModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this todo?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeDeleteModal">Cancel</button>
                        <button type="button" class="btn btn-danger" @click="deleteTodo">Delete</button>
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
                user: {},
                todos: [],
                newTodo: {
                    todo: '',
                    description: '',
                    status: ''
                },
                editTodoData: {
                    todo: '',
                    description: '',
                    status: ''
                },
                editingTodo: {},
                isEditModalOpen: false,
                isDeleteModalOpen: false,
                todoId: null
            };
        },
        created() {
            this.fetchTodos();
        },
        methods: {
            fetchTodos() {
                axios.get('/api/todo')
                    .then(response => {
                        this.todos = response.data.data;
                    })
                    .catch(error => {
                        console.error("Error fetching todos:", error);
                    });
            },
            addTodo() {
                axios.post('/api/todo', this.newTodo)
                    .then(response => {
                        this.todos.push(response.data);
                        this.newTodo = { title: '', description: '' };
                    })
                    .catch(error => {
                        console.error("Error adding todo:", error);
                    });
            },
            editTodo(todo) {
                this.editTodoData = { ...todo };
                this.isEditModalOpen = true;
            },
            updateTodo(todo) {
                axios.put(`/api/todo/${this.editTodoData.id}`, this.editTodoData)
                    .then(() => {
                        this.isEditModalOpen = false;
                        this.fetchTodos();
                        this.message = 'Todo updated successfully!';
                    })
                    .catch(error => {
                        console.error("Error updating todo:", error);
                    });
            },
            openDeleteModal(id) {
                this.todoId = id;
                this.isDeleteModalOpen = true;
            },
            closeDeleteModal() {
                this.isDeleteModalOpen = false;
                this.todoId = null;
            },
            deleteTodo() {
                axios.delete(`/api/todo/${this.todoId}`)
                    .then(() => {
                        this.fetchTodos();
                        this.isDeleteModalOpen = false;
                    })
                    .catch(error => {
                        console.error("Error deleting todo:", error);
                    });
            },
            cancelEdit() {
                this.editingTodo = null;
            },
            fetchCurrentUser() {
                axios.get('/api/current-user', { withCredentials: true })
                    .then(response => {
                        this.user = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error("Error fetching user:", error);
                        this.loading = false; 
                    });
            }
        }
    };
</script>