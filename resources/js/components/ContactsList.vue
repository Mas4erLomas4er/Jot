<template>
    <div v-if="loading">Loading...</div>
    <div v-else>
        <div v-if="contacts.length === 0">
            <p class="mb-4">{{ message }}</p>
            <router-link to="/contacts/create" class="bg-blue-400 rounded px-4 py-2 text-sm text-white">Add a contact</router-link>
        </div>
        <div v-else class="">
            <div class="" v-for="contact in contacts">
                <router-link :to="contact.links.self" class="flex items-center justify-between border-b border-gray-200 p-4 hover:bg-gray-100 transition-all">
                    <div class="flex items-center">
                        <UserCircle :name="contact.data.name"/>

                        <div class="ml-4 ">
                            <p class="text-blue-500 font-bold">{{ contact.data.name }}</p>
                            <p class="text-gray-600">{{ contact.data.company }}</p>
                        </div>
                    </div>
                    <div class="mr-4 text-sm">
                        <p class="mr-2 text-gray-500 font-bold">Birthday: </p>
                        <p class="text-blue-500">{{ contact.data.birthday }}</p>
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import UserCircle from "./UserCircle";

export default {
    name : "ContactsList",

    components : {
        UserCircle,
    },

    props : [
        'endpoint', 'message',
    ],

    mounted () {
        axios.get( this.endpoint )
            .then( response => {
                this.contacts = response.data.data;
                this.loading = false;
            } )
            .catch( errors => {
                this.loading = false;
                if ( errors.response.status === 404 )
                    alert( 'Internal error! Unable to fetch contacts' )
            } );
    },

    data : function () {
        return {
            loading : true,
            contacts : null,
        }
    },
}
</script>

<style scoped>

</style>
