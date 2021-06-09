<template>
    <div>
        <form class="" @submit.prevent="submitForm()">
            <InputField name="name" label="Contact Name" placeholder="Contact Name"
                        @update:field="form.name = $event" :errors="getErrors('name')"/>
            <InputField name="email" label="Contact Email" placeholder="contact@email.com"
                        @update:field="form.email = $event" :errors="getErrors('email')"/>
            <InputField name="company" label="Company" placeholder="Company"
                        @update:field="form.company = $event" :errors="getErrors('company')"/>
            <InputField name="birthday" label="Birthday" placeholder="dd.mm.yyyy"
                        @update:field="form.birthday = $event" :errors="getErrors('birthday')"/>

            <div class="flex justify-end">
                <button class="py-2 px-4 rounded text-red-700 border mr-5 hover:border-red-300 transition-all"
                        @click="$router.back()">Cancel</button>
                <button class="bg-blue-600 py-2 px-4 rounded text-white hover:bg-blue-500 transition-all">Add New Contact</button>
            </div>
        </form>
    </div>
</template>

<script>
import InputField from "../../components/InputField";

export default {
    name : "ContactsCreate",

    components : {
        InputField
    },

    data : () => ( {
        form : {
            'name' : '',
            'email' : '',
            'company' : '',
            'birthday' : ''
        },
        errors : null
    } ),

    methods : {
        submitForm () {
            axios.post( '/api/contacts/', this.form )
                .then( response => {
                    this.$router.push(response.data.links.self);
                } )
                .catch( errors => {
                    this.errors = errors.response.data.errors;
                } );
        },

        getErrors (field) {
            if (this.errors && this.errors[field])
                return this.errors[field];
        }
    }
}
</script>

<style scoped>

</style>
