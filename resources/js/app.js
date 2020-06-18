require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import Homepage from './components/admin/Homepage'
import Dashboard from './components/admin/Dashboard'
import Comments from './components/admin/Comments'

import Create from './components/admin/Create'
import Read from './components/admin/Read'
import Update from './components/admin/Update'

import Categories from './components/admin/forum/Categories'
import CategoriesUpdate from './components/admin/forum/CategoriesUpdate'
import CategoriesCreate from './components/admin/forum/CategoriesCreate'
import Tags from './components/admin/forum/Tags'
import TagsUpdate from './components/admin/forum/TagsUpdate'
import TagsCreate from './components/admin/forum/TagsCreate'
import Jobs from './components/admin/forum/Jobs'
import JobsUpdate from './components/admin/forum/JobsUpdate'
import JobsCreate from './components/admin/forum/JobsCreate'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin/dashboard',
            name: 'dashboard',
            component: Dashboard,
            props: true
        },
        {
            path: '/admin/read',
            name: 'read',
            component: Read,
            props: true
        },
        {
            path: '/admin/create',
            name: 'create',
            component: Create,
            props: true
        },
        {
            path: '/admin/update',
            name: 'update',
            component: Update,
            props: true
        },
        {
            path: '/admin/forum/categories',
            name: 'categories',
            component: Categories,
            props: true
        },
        {
            path: '/admin/forum/categories/create',
            name: 'categoriescreate',
            component: CategoriesCreate,
            props: true
        },
        {
            path: '/admin/forum/categories/update',
            name: 'categoriesupdate',
            component: CategoriesUpdate,
            props: true
        },
        {
            path: '/admin/forum/tags',
            name: 'tags',
            component: Tags,
            props: true
        },
        {
            path: '/admin/forum/tags/create',
            name: 'tagscreate',
            component: TagsCreate,
            props: true
        },
        {
            path: '/admin/forum/tags/update',
            name: 'tagsupdate',
            component: TagsUpdate,
            props: true
        },
        {
            path: '/admin/forum/jobs',
            name: 'jobs',
            component: Jobs,
            props: true
        },
        {
            path: '/admin/forum/jobs/create',
            name: 'jobscreate',
            component: JobsCreate,
            props: true
        },
        {
            path: '/admin/forum/jobs/update',
            name: 'jobsupdate',
            component: JobsUpdate,
            props: true
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { Homepage, Comments },
    router,
});