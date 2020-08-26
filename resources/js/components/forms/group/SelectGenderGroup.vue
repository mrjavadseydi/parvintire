<template>
    <div class="form-group">
        <label class="col-form-label">جنسیت</label>
        <select-gender
            :id="id"
            :val="val"
            :error="error"
            :classes="classes"
            v-model="inputValue"
            @input="$emit('input', inputValue)"
        ></select-gender>
    </div>
</template>
<script>
    import SelectGender from "../inputs/SelectGender";
    export default {
        components: {
            SelectGender
        },
        props: [
            'id',
            'val',
            'error',
            'classes'
        ],
        data() {
            return {
                inputValue: null
            }
        }
    }
</script>
