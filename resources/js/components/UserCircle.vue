<template>
    <div class="rounded-full text-white bg-blue-400 w-10 h-10 flex items-center justify-center border border-gray-300 font-bold">
        {{ initials }}
    </div>
</template>

<script>
export default {
    name : "UserCircle",

    props : [
        'name',
    ],

    computed : {
        initials ( ) {
            return this.name.match(/[A-ZА-Я]/g).slice(0, 2).join('');
        },
    }
}
</script>

<style scoped>

</style>
