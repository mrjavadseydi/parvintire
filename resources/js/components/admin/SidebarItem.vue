<template>
    <li @mouseenter="active = true" @mouseleave="active = false"  :class="{ 'active': active, 'treeview' : true}">
        <a class="sidebar-item">
            <i :class="item.icon"></i>
            <span v-text="item.title"></span>
            <i class="arrow fad fa-angle-left"></i>
        </a>
        <ul v-if="item.treeview" class="treeview-ul" v-show="active">
            <router-link v-for="item2 in item.treeview" :key="item2.id" tag="li" :to="item2.href">
                <a>
                    <i :class="item2.icon"></i>
                    <span v-text="item2.title"></span>
                </a>
            </router-link>
        </ul>
    </li>
</template>
<script>
    export default {
        props: ['item'],
        data() {
            return {
                active: false
            }
        }
    }
</script>
