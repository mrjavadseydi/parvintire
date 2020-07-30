<template>
    <div @mouseenter="openSidebar = 'open'" @mouseleave="openSidebar = ''" :class="['sidebar scrollbar-1', openSidebar]">
        <ul>
            <router-link v-if="!item.treeview" v-for="item in sidebar" :key="item.id" tag="li" :to="item.href">
                <a class="sidebar-item">
                    <i :class="item.icon"></i>
                    <span v-text="item.title"></span>
                    <i class="arrow fad fa-angle-left"></i>
                </a>
            </router-link>
            <li v-else :class="{ 'treeview': item.treeview}">
                <a @click="li" class="sidebar-item">
                    <i :class="item.icon"></i>
                    <span v-text="item.title"></span>
                    <i class="arrow fad fa-angle-left"></i>
                </a>
                <ul v-if="item.treeview" class="treeview-ul" v-show="openUl">
                    <router-link v-for="item2 in item.treeview" :key="item2.id" tag="li" :to="item2.href">
                        <a>
                            <i :class="item2.icon"></i>
                            <span v-text="item2.title"></span>
                        </a>
                    </router-link>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                laravel: Laravel,
                sidebar: [],
                openSidebar: '',
                openUl: false

            }
        },
        mounted() {
            axios.get('/sidebar')
                .then(response => this.sidebar = response.data)
                .catch(error => console.log(error));
        },
        methods: {
            li(event) {
                // event.target.prevent.remove()
            }
        }
    }
</script>
