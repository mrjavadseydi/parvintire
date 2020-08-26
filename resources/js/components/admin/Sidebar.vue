<template>
    <div>
        <div @click="closeSidebar" id="sidebar-parent" :class="{ 'd-none' : !open }"></div>
        <div @mouseenter="toggleSidebar" @mouseleave="toggleSidebar" :class="['sidebar scrollbar-1', {'open' : open}]">
            <ul>
                <router-link v-if="!item.treeview" v-for="item in sidebar" :key="item.id" tag="li" :to="item.href">
                    <a class="sidebar-item">
                        <i :class="item.icon"></i>
                        <span v-text="item.title"></span>
                        <i class="arrow fad fa-angle-left"></i>
                    </a>
                </router-link>
                <sidebar-item v-else :item="item"></sidebar-item>
            </ul>
        </div>
    </div>
</template>
<script>
    import SidebarItem from "./SidebarItem";
    export default {
        data() {
            return {
                laravel: Laravel,
                sidebar: [],
                open: false,
                click: false
            }
        },
        mounted() {
            axios.get('/admin/sidebar')
            .then(response => this.sidebar = response.data)
            .catch(error => console.log(error));
            this.$emit('myMethod');
        },
        methods: {
            toggleSidebar() {
                if (this.open) {
                    this.open = false;
                    document.getElementById('content').classList.remove('sidebar-open-content');
                } else {
                    this.open = true;
                    document.getElementById('content').classList.add('sidebar-open-content');
                }
            },
            closeSidebar() {
                this.open = false;
                document.getElementsByClassName('sidebar')[0].classList.remove('open');
                document.getElementById('content').classList.remove('sidebar-open-content');
                document.getElementById('sidebar-parent').classList.add('d-none');
            }
        },
        components: {
            SidebarItem
        }
    }
</script>
