<script setup>
    import {
        onMounted,
        reactive,
        ref
    } from 'vue';

    const users = ref([]);

    const form = reactive({
        name: '',
        email: '',
        password: ''
    });

    const getUsers = () => {
        axios.get('/api/users').then((response) => {
            users.value = response.data;
        })
    }

    const createUser = () => {
        axios.post('/api/users', form)
    }

    onMounted(() => {
        getUsers()
    })
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mb-3 text-end">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                        <i class="fas fa-user-plus">&nbsp;</i>
                        Add user
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <th style="width: 10px">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th>Role</th>
                            <th>Options</th>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td>{{ index+1 }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>--</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addUserModal" data-backdrop="static" tabindex="-1" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title fs-5" id="addUserModalLabel">
                        <i class="fas fa-user-plus font-weight-bold">&nbsp;</i>
                        Create new user
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" autocomplete="off">
                    <div class="modal-body">

                        <div class="form-group mb-2">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" v-model="form.name">
                        </div>
                        <div class="form-group mb-2">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" v-model="form.email">
                        </div>
                        <div class="form-group mb-2">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                v-model="form.password">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="createUser">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
