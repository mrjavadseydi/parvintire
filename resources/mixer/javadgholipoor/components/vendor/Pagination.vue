<template>
    <div class="my-pagination">
        <pagination :data="data" @pagination-change-page="result"></pagination>
    </div>
</template>

<script>
    import Pagination from 'laravel-vue-pagination';
    export default {
        props: ['data', 'result'],
        components: {
            'pagination': Pagination
        }
    }
</script>
