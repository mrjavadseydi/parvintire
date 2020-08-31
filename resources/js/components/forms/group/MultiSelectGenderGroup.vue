<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <simple-select :options="options" :value="value" :error="error" :classes="classes"></simple-select>
        <small class="text-danger" v-if="error">{{ error }}</small>
    </div>
</template>
<script>
    import SelectGender from "../inputs/SelectGender";
    export default {
        components: {
            SelectGender
        },
        props: [
            'error',
            'value',
            'classes'
        ],
        methods: {
            changeData(value) {
                var name = 'gender';
                this.$parent.data[name] = value;
                this.$parent.errors.clear(name);
                this.error = this.$parent.errors.get(name);
                if (!this.$parent.errors.any()) {
                    this.$parent.disabled = false;
                }
            }
        }
    }
</script>
