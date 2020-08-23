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
        'cityId'
    ],
    updated() {

    },
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
        update(cityId) {
            this.get(cityId);
            this.fire = false;
        },
        get(cityId) {
            this.items = {};
            if(cityId) {
                axios.get('/api/v1/world/towns', {
                    params: {
                        cityId: cityId
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
        cityId(cityId) {
            if(this.fire) {
                this.get(cityId)
                this.fire = false;
            }
        }
    }
}
</script>
