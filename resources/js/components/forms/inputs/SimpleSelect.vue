<template>
    <div>
        <select
            :id="name"
            :name="name"
            @change="changeData"
            :class=" ['form-control' , { 'is-invalid' : error }, classes]"
        >
            <option value="">انتخاب کنید</option>
            <option :selected="option.value == value" v-for="option in options" :value="option.value">{{ option.title }}</option>
        </select>
    </div>
</template>

<script>
export default {
    props: [
        'name',
        'value',
        'error',
        'classes',
        'options'
    ],
    methods: {
        changeData(event) {
            this.$parent.changeData(event.target.value);
        }
    }
}
</script>
