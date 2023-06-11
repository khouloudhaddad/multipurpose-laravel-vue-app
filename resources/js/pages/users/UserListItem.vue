<script setup>
    import { formatDate } from '../../helper.js';
    defineProps({
        user: Object,
        index: Number
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
</template>
