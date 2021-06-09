<template>
    <div>
        <div class="flex justify-between">
            <a href="#" class="text-blue-400" @click="$router.back()">
                < Back
            </a>
        </div>
        <form class="pt-6" @submit.prevent="submitForm()">
            <InputField name="name" label="Contact Name" placeholder="Contact Name"
                        @update:field="form.name = $event" :errors="getErrors('name')" :data="form.name"/>
            <InputField name="email" label="Contact Email" placeholder="contact@email.com"
                        @update:field="form.email = $event" :errors="getErrors('email')" :data="form.email"/>
            <InputField name="company" label="Company" placeholder="Company"
                        @update:field="form.company = $event" :errors="getErrors('company')" :data="form.company"/>
            <InputField name="birthday" label="Birthday" placeholder="dd.mm.yyyy"
                        @update:field="form.birthday = $event" :errors="getErrors('birthday')" :data="form.birthday"/>

            <div class="flex justify-end">
                <button class="py-2 px-4 rounded text-red-700 border mr-5 hover:border-red-300 transition-all"
                        @click="$router.back()">Cancel</button>
                <button class="bg-blue-600 py-2 px-4 rounded text-white hover:bg-blue-500 transition-all">Save</button>
            </div>
        </form>
    </div>
</template>

<script>
import InputField from "../../components/InputField";

export default {
    name : "ContactsEdit",

    components : {
        InputField
    },

    mounted () {
        axios.get( `/api/contacts/${ this.$route.params.id }` )
            .then( response => {
                this.form = response.data.data;
                this.loading = false;
            } )
            .catch( errors => {
                this.loading = false;

                if ( errors.response.status === 404 )
                    this.$router.push( '/contacts/' );
            } );
    },

    data : () => ( {
        form : {
            'name' : '',
            'email' : '',
            'company' : '',
            'birthday' : '',
        },
        errors : null
    } ),

    methods : {
        submitForm () {
            axios.patch( `/api/contacts/${ this.$route.params.id }`, this.form )
                .then( response => {
                    // console.log(response.data.links.self);
                    this.$router.push( response.data.links.self );
                } )
                .catch( errors => {
                    this.errors = errors.response.data.errors;
                } );
        },

        getErrors ( field ) {
            if ( this.errors && this.errors[ field ] )
                return this.errors[ field ];
        }
    }
}
</script>

<style scoped>

</style>
