<template>
    <div>
        <simple-select name="day" :options="options"></simple-select>
    </div>
</template>

<script>
import SimpleSelect from "./SimpleSelect";

export default {
    components: {
        SimpleSelect
    },
    props: [
        'name',
        'value',
        'error',
        'classes'
    ],
    data() {
        return {
            options: []
        }
    },
    methods: {
        changeData(event) {
            this.$parent.changeData(event.target.value);
        }
    }
}
</script>
