<template>
    <ContactsList endpoint="/api/contacts" message="No contacts yet"/>
</template>

<script>
import ContactsList from "../../components/ContactsList";

export default {
    name : "ContactsIndex",

    components : {
        ContactsList,
    },
}
</script>

<style scoped>

</style>
