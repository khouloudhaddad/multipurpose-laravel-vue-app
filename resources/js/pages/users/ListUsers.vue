<script setup>
    import {
        onMounted,
        ref
    } from 'vue';
    import { Form, Field, useResetForm  } from 'vee-validate';
    import * as yup from 'yup';
    import jQuery from 'jquery';
    import { useToastr } from '../../toastr';


    const toastr = useToastr();

    const users = ref([]);
    const editing = ref(false);
    const formValues = ref();
    const form = ref(null);
    const userToBeDeletedId = ref(null);

    const createUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().required().min(8)
    });

    const editUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().when(([password], schema) => {
            return password ? schema.min(8) : schema;
        }),
    });

    const getUsers = () => {
        axios.get('/api/users').then((response) => {
            users.value = response.data;
        })
    }

    const createUser = (values, { resetForm, setErrors }) => {
       console.log(values)
       axios.post('/api/users', values)
       .then((response)=>{
            users.value.unshift(response.data);
            toastr.success('User created successfully')
            //hide modal
            jQuery('#userFormModal').modal('hide');
            //clear form
            resetForm();
       })
        .catch((error) => {
            if(error.response.data.errors){

                setErrors(error.response.data.errors);

            }
       })
    }

    const editUser = (user) =>{
        editing.value = true;
        form.value.resetForm();
        jQuery('#userFormModal').modal('show');
        formValues.value = {
            id: user.id,
            name: user.name,
            email: user.email
        }
    }

    const addUser = () =>{
        editing.value = false;
        jQuery('#userFormModal').modal('show');
    }

    const updateUser = (values, {setErrors}) =>{
        axios.put('/api/users/' + formValues.value.id, values)
            .then((resp) => {
                const index = users.value.findIndex(user => user.id === resp.data.id)
                users.value[index] = resp.data;
                jQuery('#userFormModal').modal('hide');
                toastr.success('User updated successfully!');

            })
            .catch((error) => {

                if (error.response.data.errors) {

                    setErrors(error.response.data.errors);

                }

            });

    }

    const handleSubmit = (values, actions) => {

        console.log('actions', actions)

        if(editing.value){
            updateUser(values, actions)
        }else{
            createUser(values, actions)
        }
    }

    onMounted(() => {
        getUsers();
    });

    const confirmUserDelete = (user)=>{
        jQuery('#userDeleteFormModal').modal('show');
        userToBeDeletedId.value = user.id;
    }

    const deleteUser = () =>{
        axios.delete(`/api/users/${userToBeDeletedId.value}`)
        .then(()=>{
            jQuery('#userDeleteFormModal').modal('hide');
            users.value = users.value.filter(user => user.id !== userToBeDeletedId.value);
            toastr.success('User delete successfully !');
        })
    }

    /**
     * Format a date
     * @param {dateString} dateString
     */
     const formatDate =(dateString)=> {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('default', {dateStyle: 'long'}).format(date);
    }

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
                    <button type="button" class="btn btn-primary" @click="addUser">
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
                                <td>{{ user.created_at }}</td>
                                <td>-</td>
                                <td class="text-center">
                                    <a href="#" @click.prevent="editUser(user)">
                                        <i class="fa fa-edit mr-2"></i>
                                    </a>
                                    <a href="#" @click.prevent="confirmUserDelete(user)">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit User Modal -->
    <div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" aria-labelledby="userFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title fs-5" id="userFormModalLabel">
                        <i class="fas fa-user-plus font-weight-bold">&nbsp;</i>
                        <span v-if="editing">Edit user</span>
                        <span v-else>Create new user</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit"
                :validation-schema="editing ? editUserSchema : createUserSchema"
                v-slot="{ errors }"
                :initial-values="formValues">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="userDeleteFormModal" data-backdrop="static" tabindex="-1" aria-labelledby="userDeleteFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title fs-5" id="userDeleteFormModalLabel">
                        <i class="fas fa-user-plus font-weight-bold">&nbsp;</i>
                        <span>Delete user</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <div class="modal-body">
                        <h5>Are you sure you want to delete this user?</h5>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click.prevent="deleteUser">Confirm</button>
                    </div>

            </div>
        </div>
    </div>
</template>
