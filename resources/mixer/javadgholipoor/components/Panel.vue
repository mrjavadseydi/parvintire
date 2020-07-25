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
                buttons: []
            }
        },
        mounted() {
        },
        components: {
            'headContent': HeadContent
        }
    }
</script>
