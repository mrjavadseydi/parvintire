<template>
    <div>
        <input
            :id="id"
            type="text"
            :value="val"
            :name="name"
            :placeholder="placeholder"
            @input="$emit('input', $event.target.value)"
            :class=" ['form-control' , { 'is-invalid' : error }, classes]"
        >
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
        'placeholder'
    ]
}
</script>
