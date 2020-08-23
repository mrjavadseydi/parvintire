<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <input-text
            :id="id"
            :val="val"
            :name="name"
            :error="error"
            :classes="classes"
            v-model="inputValue"
            :placeholder="placeholder"
            @input="$emit('input', inputValue)"
        ></input-text>
    </div>
</template>
<script>
    import InputText from "../inputs/InputText";
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
            InputText
        },
        data() {
            return {
                inputValue: null
            }
        }
    }
</script>
