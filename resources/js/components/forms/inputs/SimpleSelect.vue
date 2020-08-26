<template>
    <div>
        <select
            :id="name"
            :name="name"
            @input="$emit('input', $event.target.value)"
            :class=" ['form-control' , { 'is-invalid' : error }, classes]"
        >
            <option value="">انتخاب کنید</option>
            <option :selected="option.value == val" v-for="option in options" :value="option.value">{{ option.title }}</option>
        </select>
        <small class="text-danger" v-if="error">{{ error }}</small>
    </div>
</template>

<script>
export default {
    props: [
        'id',
        'val',
        'name',
        'error',
        'classes',
        'options'
    ]
}
</script>
