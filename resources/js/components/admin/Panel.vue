<template>
    <div>
        <loader v-if="loader"></loader>
        <div v-else>
            <head-content :title="title" :buttons="buttons"></head-content>
             <h1>در حال توسعه هستیم...</h1>
             <h3>به بخش های تکمیل شده سر بزنید</h3>
        </div>
    </div>
</template>

<script>
import HeadContent from './HeadContent';
export default {
    data() {
        return {
            loader: true,
            title: 'پیشخوان',
            buttons: [],
            date: '',
            test: 'javad'
        }
    },
    mounted() {
        this.loader = false;
    },
    components: {
        'head-content': HeadContent,
    }
}
</script>
