<template>
    <div class="">
        <div class="fixed left-0 top-0 w-screen h-screen bg-black opacity-10 z-10" v-if="focus" @click="focus = false"></div>
        <div class="relative z-10">
            <div class="absolute">
                <svg viewBox="0 0 24 24" class="w-5 h-5 mt-2 ml-3">
                    <path fill-rule="evenodd" d="M20.2 18.1l-1.4 1.3-5.5-5.2 1.4-1.3 5.5 5.2zM7.5 12c-2.7 0-4.9-2.1-4.9-4.6s2.2-4.6 4.9-4.6 4.9 2.1 4.9 4.6S10.2 12 7.5 12zM7.5.8C3.7.8.7 3.7.7 7.3s3.1 6.5 6.8 6.5c3.8 0 6.8-2.9 6.8-6.5S11.3.8 7.5.8z" clip-rule="evenodd"/>
                </svg>
            </div>
            <input class="w-64 mr-6 bg-gray-100 border border-gray-200 pl-10 pr-3 py-1 rounded-full text-gray-600 focus:border-blue-400 focus:shadow focus:bg-gray-50 transition-all" type="text" placeholder="Search.." id="searchTerm" v-model="searchTerm" @input="search" @focus="focus = true">

            <div class="absolute bg-blue-900 text-white rounded-lg p-4 w-96 right-0 mr-6 mt-2 shadow z-20" v-if="focus && searchTerm.length >= 3">
                <div v-if="!results.length">
                    No results found for "{{ searchTerm }}"
                </div>
                <div class="" v-for="result in results"  @click="focus = false">
                    <router-link :to="result.links.self" class="flex py-2 items-center">
                        <UserCircle :name="result.data.name"/>
                        <div class="pl-3">
                            <p class="">{{ result.data.name }}</p>
                            <p class="">{{ result.data.company }}</p>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import UserCircle from './UserCircle';

export default {
    name : "SearchBar",

    components : {
        UserCircle,
    },

    data : function () {
        return {
            searchTerm : '',
            results : [],
            focus : false,
        };
    },

    methods : {
        search : _.debounce( function ( e ) {
            if ( this.searchTerm.length < 3 ) return;

            axios.post( '/api/search', { searchTerm : this.searchTerm } )
                .then( response => {
                    this.results = response.data.data;
                } )
                .catch( errors => {
                    console.log( errors.response );
                } );
        }, 300 ),

    },
}
</script>

<style scoped>

</style>
