import Vue from 'vue';
import VueRouter from 'vue-router';
import ContactsCreate from "./views/Contacts/Create";
import ContactsShow from "./views/Contacts/Show";
import ContactsEdit from "./views/Contacts/Edit";
import ContactsIndex from "./views/Contacts/Index";
import BirthdaysIndex from "./views/Birthdays/Index";
import Welcome from './views/Welcome';

Vue.use( VueRouter );

export default new VueRouter( {
    routes : [
        {
            path : '/', component : Welcome,
            meta : { title : 'Welcome' },
        }, {
            path : '/contacts', component : ContactsIndex,
            meta : { title : 'Contacts' },
        }, {
            path : '/contacts/create', component : ContactsCreate,
            meta : { title : 'Add a new contact' },
        }, {
            path : '/contacts/:id', component : ContactsShow,
            meta : { title : 'Detail for the contact' },
        }, {
            path : '/contacts/:id/edit', component : ContactsEdit,
            meta : { title : 'Edit the contact' },
        }, {
            path : '/birthdays', component : BirthdaysIndex,
            meta : { title : 'This month\'s birthdays' },
        },
    ],
    mode : 'history',
} )
