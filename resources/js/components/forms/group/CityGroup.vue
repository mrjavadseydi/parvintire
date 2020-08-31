<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <select-city
            ref="cityRef"
            :id="id"
            :val="val"
            :name="name"
            :error="error"
            :classes="classes"
            v-model="inputValue"
            :province-id="provinceId"
            @input="$emit('input', inputValue)"
        ></select-city>
    </div>
</template>
<script>
import SelectCity from "../inputs/SelectCity";
export default {
    props: [
        'id',
        'val',
        'name',
        'title',
        'error',
        'classes',
        'provinceId',
    ],
    components: {
        SelectCity
    },
    data() {
        return {
            inputValue: null
        }
    },
    methods: {
        update(provinceId) {
            this.$refs.cityRef.update(provinceId);
        }
    }
}
</script>
