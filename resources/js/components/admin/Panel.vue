<template>
    <div>
        <head-content :title="title" :buttons="buttons"></head-content>
         <h1>در حال توسعه هستیم...</h1>
         <h3>به بخش های تکمیل شده سر بزنید</h3>
    </div>
</template>

<script>
import HeadContent from './HeadContent';
export default {
    data() {
        return {
            title: 'پیشخوان',
            buttons: [],
            date: '',
            test: 'javad'
        }
    },
    mounted() {
    },
    components: {
        'head-content': HeadContent,
    }
}
</script>
