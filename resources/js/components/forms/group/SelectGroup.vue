<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <simple-select
            :id="id"
            :val="val"
            :name="name"
            :error="error"
            :classes="classes"
            :options="options"
            v-model="inputValue"
            @input="$emit('input', inputValue)"
        ></simple-select>
    </div>
</template>
<script>
    import SimpleSelect from "../inputs/SimpleSelect";
    export default {
        components: {SimpleSelect},
        props: [
            'id',
            'val',
            'name',
            'title',
            'error',
            'model',
            'classes',
            'options'
        ],
        data() {
            return {
                inputValue: null
            }
        }
    }
</script>
