<template>
    <div class="form-group">
        <label class="col-form-label">{{ title }}</label>
        <select-region
            ref="regionRef"
            :id="id"
            :val="val"
            :name="name"
            :error="error"
            :classes="classes"
            v-model="inputValue"
            @input="$emit('input', inputValue)"
            :town-id="townId"
        ></select-region>
    </div>
</template>
<script>
import SelectRegion from "../inputs/SelectRegion";
export default {
    props: [
        'id',
        'val',
        'name',
        'title',
        'error',
        'classes',
        'townId',
    ],
    components: {
        SelectRegion
    },
    data() {
        return {
            inputValue: null
        }
    },
    methods: {
        update(townId) {
            this.$refs.regionRef.update(townId);
        }
    }
}
</script>
