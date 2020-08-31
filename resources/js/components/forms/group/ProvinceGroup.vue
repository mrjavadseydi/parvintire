<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <select-province
            :id="id"
            :val="val"
            :name="name"
            :error="error"
            :classes="classes"
            v-model="inputValue"
            @input="$emit('input', inputValue)"
        ></select-province>
    </div>
</template>
<script>
import SelectProvince from "../inputs/SelectProvince";
export default {
    props: [
        'id',
        'val',
        'name',
        'title',
        'error',
        'classes',
    ],
    components: {
        SelectProvince
    },
    data() {
        return {
            inputValue: null
        }
    }
}
</script>
