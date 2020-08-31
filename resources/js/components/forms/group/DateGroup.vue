<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <input
            :id="id"
            type="text"
            :name="name"
            :value="date"
            :placeholder="placeholder"
            :class=" ['form-control' , { 'is-invalid' : error }, classes]"
        >
        <date-picker
            view="year"
            :value="val"
            :element="id"
            v-model="date"
            color="#05AAF4"
            :auto-submit="true"
            format="jYYYY/jMM/jDD"
            @input="$emit('input', date)"
        />
        <small class="text-danger" v-if="error">{{ error }}</small>
    </div>
</template>
<script>
    import DatePicker from 'vue-persian-datetime-picker';
    export default {
        components: {
            DatePicker
        },
        props: [
            'id',
            'val',
            'name',
            'error',
            'title',
            'classes',
            'placeholder'
        ],
        data() {
            return {
                date: ''
            }
        },
        updated() {
            this.date = this.val;
        }
    }
</script>
