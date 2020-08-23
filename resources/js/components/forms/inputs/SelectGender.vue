<template>
    <div>
        <select
            :id="id"
            name="gender"
            @input="$emit('input', $event.target.value)"
            :class=" ['form-control' , { 'is-invalid' : error }, classes]"
        >
            <option value="">انتخاب کنید</option>
            <option :selected="val == 1" value="1">مرد</option>
            <option :selected="val == 2" value="2">زن</option>
        </select>
    </div>
</template>

<script>
export default {
    props: [
        'id',
        'val',
        'error',
        'classes'
    ]
}
</script>
