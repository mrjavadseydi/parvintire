<template>
    <div :class="['my-pagination', classes]">
        <pagination :data="data" @pagination-change-page="result"></pagination>
    </div>
</template>

<script>
    import Pagination from 'laravel-vue-pagination';
    export default {
        props: ['data', 'result', 'classes'],
        components: {
            'pagination': Pagination
        }
    }
</script>
