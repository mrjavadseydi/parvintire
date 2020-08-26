<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <multi-select
            dir="rtl"
            :val="val"
            :error="error"
            :options="options"
            :classes="classes"
            v-model="selected"
        ></multi-select>
    </div>
</template>
<script>
    import MultiSelect from "../inputs/MultiSelect";
    export default {
        components: {
            MultiSelect
        },
        props: [
            'val',
            'title',
            'error',
            'classes',
            'options'
        ],
        data(){
            return {
                selected: []
            }
        },
        updated() {
            if (this.selected.length == 0) {
                this.selected = this.val;
            }
        },
        watch: {
            selected(selected){
                this.$emit('input', selected);
            }
        }
    }
</script>
