<template>
    <ContactsList endpoint="/api/birthdays" message="None of your contacts have a birthday this month"/>
</template>

<script>
import ContactsList from "../../components/ContactsList";

export default {
    name : "BirthdaysIndex",

    components : {
        ContactsList,
    },
}
</script>

<style scoped>

</style>
