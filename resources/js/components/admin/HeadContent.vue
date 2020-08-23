'<template>
    <div>
        <div class="page-title mb-3">
            <h1>{{ title }}</h1>
            <div class="line"></div>
            <div class="buttons">
                <div v-for="button in buttons" class="d-inline-block">
                    <router-link :to="button.to">
                        <button :class="`btn btn-outline-${button.type} mr-2`">
                            <i :class="`${button.icon} align-middle ml-2`"></i>{{ button.title }}
                        </button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['title', 'buttons']
    }
</script>
'
