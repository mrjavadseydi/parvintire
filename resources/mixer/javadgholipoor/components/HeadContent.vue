'<template>
    <div>
        <div class="page-title">
            <h1>{{ title }}</h1>
            <div class="line"></div>
            <div class="buttons">
                <button v-for="button in buttons" type="button" :class="`btn btn-outline-${button.type} mr-2`"><i :class="`${button.icon} align-middle ml-2`"></i>{{ button.title }}</button>
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
