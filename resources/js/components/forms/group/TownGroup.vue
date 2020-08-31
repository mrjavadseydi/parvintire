<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <select-town
            ref="townRef"
            :id="id"
            :val="val"
            :name="name"
            :error="error"
            :classes="classes"
            v-model="inputValue"
            @input="$emit('input', inputValue)"
            :city-id="cityId"
        ></select-town>
    </div>
</template>
<script>
import SelectTown from "../inputs/SelectTown";
export default {
    props: [
        'id',
        'val',
        'name',
        'title',
        'error',
        'classes',
        'cityId',
    ],
    components: {
        SelectTown
    },
    data() {
        return {
            inputValue: null
        }
    },
    methods: {
        update(cityId) {
            this.$refs.townRef.update(cityId);
        }
    }
}
</script>
