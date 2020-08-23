<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <input
            :id="name"
            type="text"
            :name="name"
            :class=" ['form-control' , { 'is-invalid' : error }, classes]"
            @keyup="changeData"
            :value="value"
        >
        <small class="text-danger" v-if="error">{{ error }}</small>
    </div>
</template>
<script>
    export default {
        props: [
            'type',
            'title',
            'error',
            'name',
            'model',
            'value',
            'classes'
        ],
        methods: {
            changeData(e) {
                this.$parent.data[this.name] = e.target.value;
                this.$parent.errors.clear(this.name);
                this.error = this.$parent.errors.get(this.name);
                if (!this.$parent.errors.any()) {
                    this.$parent.disabled = false;
                }
            }
        }
    }
</script>
