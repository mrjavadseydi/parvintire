'<template>
    <div>
        <div class="page-title mb-3">
            <h1>{{ title }}</h1>
            <div class="line"></div>
            <div class="buttons">
                <a href="/change-panel" class="btn btn-danger mr-2">
                    <i class="fal fa-tachometer align-middle ml-2"></i>نسخه قدیمی
                </a>
                <slot></slot>
                <div v-for="button in buttons" class="d-inline-block">
                    <router-link v-if="button.to" :to="button.to">
                        <button :class="`btn btn-outline-${button.type} mr-2`">
                            <i :class="`${button.icon} align-middle ml-2`"></i>{{ button.title }}
                        </button>
                    </router-link>
                    <button v-else @click="click(button.method)" :class="`btn btn-outline-${button.type} mr-2`">
                        <i :class="`${button.icon} align-middle ml-2`"></i>{{ button.title }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['title', 'buttons'],
        methods: {
            click(method) {
                this.$parent.buttonsMethod(method);
            }
        }
    }
</script>
'
