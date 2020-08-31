<template>
    <div>
        <label>
            <input
                :id="id"
                :checked="val"
                :name="name"
                type="checkbox"
                @input="$emit('input', $event.target.value)"
                :class=" [{ 'is-invalid' : error }, classes]"
            >
            <span v-text="title"></span>
        </label>
        <small class="text-danger" v-if="error">{{ error }}</small>
    </div>
</template>

<script>
export default {
    props: [
        'id',
        'val',
        'name',
        'title',
        'error',
        'classes',
    ]
}
</script>
