<script setup>
    import { formatDate } from '../../helper.js';
    import jQuery from 'jquery';
    import { ref } from 'vue'
    import { useToastr } from '../../toastr';

    const toastr = useToastr();

    defineProps({
        user: Object,
        index: Number
    });

    const emit = defineEmits(['userDeleted', 'editUser']);

    const userToBeDeletedId = ref(null);

    const confirmUserDelete = (user)=>{
        jQuery('#userDeleteFormModal').modal('show');
        userToBeDeletedId.value = user.id;
    }

    const deleteUser = () =>{
        axios.delete(`/api/users/${userToBeDeletedId.value}`)
        .then(()=>{
            jQuery('#userDeleteFormModal').modal('hide');
            toastr.success('User delete successfully !');

            emit('userDeleted', userToBeDeletedId.value)
        })
    }

    const editUser = (user) => {
        emit('editUser', user)
    }
</script>

<template>
    <tr>
        <td>{{ index+1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ formatDate(user.created_at) }}</td>
        <td>{{ user.role }}</td>
        <td class="text-center">
            <a href="#" @click.prevent="editUser(user)">
                <i class="fa fa-edit mr-2"></i>
            </a>
            <a href="#" @click.prevent="confirmUserDelete(user)">
                <i class="fa fa-trash text-danger"></i>
            </a>
        </td>
    </tr>

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
