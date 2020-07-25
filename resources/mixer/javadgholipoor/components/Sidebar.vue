<template>
    <div class="sidebar scrollbar-1">
        <ul>
            <router-link v-for="item in sidebar" :to="item.href">
                <li :class="{ treeview: item.treeview }">
                    <a class="sidebar-item">
                        <i :class="item.icon"></i>
                        <span v-text="item.title"></span>
                        <i class="arrow fad fa-angle-left"></i>
                    </a>
                    <ul v-if="item.treeview" class="treeview-ul">
                        <router-link v-for="item2 in item.treeview" :to="`${item2.href}`">
                            <li>
                                <a href="">
                                    <i :class="item2.icon"></i>
                                    <span v-text="item2.title"></span>
                                </a>
                            </li>
                        </router-link>
                    </ul>
                </li>
            </router-link>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                laravel: Laravel,
                sidebar: []
            }
        },
        mounted() {
            axios.get('/sidebar')
                .then(response => this.sidebar = response.data)
                .catch(error => console.log(error));
        }
    }
</script>
