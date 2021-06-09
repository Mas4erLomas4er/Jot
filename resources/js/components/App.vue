<template>
    <div class="h-screen bg-white">
        <div class="flex">
            <Sidebar/>
            <div class="flex flex-col flex-1 overflow-y-hidden h-screen">
                <div class="px-6 py-3 flex items-center justify-between border-b border-gray-400 w-full">
                    <div class="">{{ title }}</div>
                    <div class="flex items-center">
                        <SearchBar/>
                        <UserCircle :name="user.name"/>
                    </div>
                </div>
                <router-view class="p-6 flex flex-col overflow-auto" :key="$route.fullPath"></router-view>
            </div>
        </div>
    </div>
</template>

<script>
import UserCircle from "./UserCircle";
import SearchBar from "./SearchBar";
import Sidebar from './Sidebar';

export default {
    name : "App",

    props : [
        'user',
    ],

    components : {
        Sidebar, UserCircle, SearchBar,
    },

    created () {
        this.title = this.$route.meta.title;

        window.axios.interceptors.request.use( ( config ) => {
            if ( config.method === 'get' ) {
                config.url = `${ config.url }?api_token=${ this.user.api_token }`;
            } else {
                if (!config.data) config.data = {};
                config.data.api_token = this.user.api_token;
            }

            return config;
        } );
    },

    data : function () {
        return {
            title : 'JOT',
        };
    },

    watch : {
        $route ( to, from ) {
            this.title = to.meta.title;
        },
        title () {
            document.title = `${ this.title } | Jot - The SPA App`
        },
    },
}
</script>

<style scoped>

</style>
