<template>
    <div>
        <select
            :id="id"
            :dir="dir"
            :name="name"
            multiple="multiple"
            :disabled="disabled"
            :placeholder="placeholder"
            :class=" ['form-control' , { 'is-invalid' : error }, classes]"
        >
            <option
                :selected="isSelected(option.id)"
                v-for="option in options"
                v-text="option.title"
                :value="option.id"
            ></option>
        </select>
        <small class="text-danger" v-if="error">{{ error }}</small>
    </div>
</template>

<script>
import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.min.css'

export default {
    data() {
        return {
            select2: null
        };
    },
    props: {
        id: null,
        val: null,
        dir: null,
        name: null,
        error: null,
        options: {},
        settings: {},
        classes: null,
        disabled: false,
        placeholder: false
    },
    methods: {
        isSelected(val) {
            if (this.val.includes(val)) {
                return true;
            }
            return false;
        }
    },
    mounted() {
        this.select2 = $(this.$el).find('select')
            .select2().on('select2:select select2:unselect', ev => {
                var selected = [];
                var values = this.select2.val();
                values.forEach(function (item, i) {
                    selected[i] = parseInt(item);
                });
                this.$emit('input', selected);
            });
    },
    beforeDestroy() {
        this.select2.select2('destroy');
    }
};
</script>
