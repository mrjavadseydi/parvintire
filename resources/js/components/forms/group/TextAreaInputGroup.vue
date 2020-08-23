<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <text-area
            :id="id"
            :val="val"
            :name="name"
            :error="error"
            :classes="classes"
            v-model="inputValue"
            :placeholder="placeholder"
            @input="$emit('input', inputValue)"
        ></text-area>
    </div>
</template>
<script>
    import TextArea from "../inputs/TextArea";
    export default {
        props: [
            'id',
            'val',
            'name',
            'title',
            'error',
            'classes',
            'placeholder'
        ],
        components: {
            TextArea
        },
        data() {
            return {
                inputValue: null
            }
        }
    }
</script>
