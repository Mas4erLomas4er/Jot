<template>
    <div class="relative pb-4">
        <label :for="name" class="text-blue-600 uppercase text-xs font-bold absolute pt-2">{{ label }}</label>
        <input class="pt-8 w-full border-b pb-2 outline-none text-gray-900 focus:border-blue-400" :class="errorClassObject()"
               type="text" :id="name" :placeholder="placeholder" v-model="value" @input="updateField()"  @keypress.enter.prevent>

        <p class="text-red-600 text-sm pt-1" v-text="errorMessage()">Error here</p>
    </div>
</template>

<script>
export default {
    name : 'InputField',

    props : [
        'name', 'label', 'placeholder', 'errors', 'data',
    ],

    data : () => ( {
        value : ''
    } ),

    computed: {
        hasErrors () {
            return ( this.errors && this.errors.length );
        },
    },

    methods : {
        updateField () {
            this.clearErrors();
            this.$emit( 'update:field', this.value );
        },

        errorMessage () {
            if ( this.hasErrors )
                return this.errors[ 0 ];
        },

        clearErrors () {
            if ( this.hasErrors )
                this.errors = null;
        },

        errorClassObject () {
            return {
                'error-field' : this.hasErrors
            }
        }
    },

    watch: {
        data: function ( val ) {
            this.value = val;
        }
    }
}
</script>

<style scoped>
.error-field {
    @apply border-red-500 border-b-2
}
</style>
