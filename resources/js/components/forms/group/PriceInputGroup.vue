<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <input-price
            :id="id"
            :val="val"
            :name="name"
            :error="error"
            :classes="classes"
            v-model="inputValue"
            :placeholder="placeholder"
            @input="$emit('input', inputValue)"
        ></input-price>
    </div>
</template>
<script>
    import InputPrice from "../inputs/InputPrice";
    export default {
        props: [
            'id',
            'val',
            'name',
            'title',
            'error',
            'classes',
            'placeholder'
        ],
        components: {
            InputPrice
        },
        data() {
            return {
                inputValue: null
            }
        }
    }
</script>
