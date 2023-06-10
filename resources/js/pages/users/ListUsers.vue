<script setup>
    import {
        onMounted,
        ref
    } from 'vue';
    import { Form, Field, useResetForm } from 'vee-validate';
    import * as yup from 'yup';

    const users = ref([]);

    const createUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().required().min(8)
    });

    const getUsers = () => {
        axios.get('/api/users').then((response) => {
            users.value = response.data;
        })
    }

    const createUser = (values, { resetForm  }) => {
       console.log(values)
       axios.post('/api/users', values).then((response)=>{
        users.value.unshift(response.data);
        //hide modal
        $('#addUserModal').model('hide');
        //clear form
        resetForm();
       })
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
                <Form  @submit="createUser" :validation-schema="createUserSchema" v-slot="{ errors }">
                    <div class="modal-body">

                        <div class="form-group mb-2">
                            <label for="name">Name</label>
                            <Field type="text" name="name" id="name" class="form-control" :class="{'is-invalid': errors.name}"  />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>
                        <div class="form-group mb-2">
                            <label for="email">Email</label>
                            <Field type="email" name="email" id="email" class="form-control" :class="{'is-invalid': errors.email}" />
                            <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>
                        <div class="form-group mb-2">
                            <label for="password">Password</label>
                            <Field type="password" name="password" id="password" class="form-control" :class="{'is-invalid': errors.password}" />
                            <span class="invalid-feedback">{{ errors.password }}</span>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
