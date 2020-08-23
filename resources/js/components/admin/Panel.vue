<template>
    <div>
        <head-content :title="title" :buttons="buttons"></head-content>
    </div>
</template>

<script>
import HeadContent from './HeadContent';
export default {
    data() {
        return {
            title: '',
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
