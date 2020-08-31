<template>
    <div>
        <select
            :id="name"
            :name="name"
            @change="onChange"
            :class="['form-control' , { 'is-invalid' : error }, classes]"
        >
            <option value="">انتخاب کنید</option>
            <option :selected="item.id == val" v-for="item in items" :value="item.id">{{ item.name }}</option>
        </select>
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
        'provinceId'
    ],
    data() {
        return {
            items: {},
            fire: true
        }
    },
    methods: {
        onChange(event) {
            this.$emit('input', event.target.value)
        },
        update(provinceId) {
            this.get(provinceId);
            this.fire = false;
        },
        get(provinceId) {
            this.items = {};
            if(provinceId) {
                axios.get('/api/v1/world/cities', {
                    params: {
                        provinceId: provinceId
                    }
                })
                .then(response => {
                    this.items = response.data;
                })
                .catch(error => {

                });
            }
        }
    },
    watch: {
        provinceId(provinceId) {
            if(this.fire) {
                alert('fire2');
                this.get(provinceId);
                this.fire = false;
            }
        }
    }
}
</script>
