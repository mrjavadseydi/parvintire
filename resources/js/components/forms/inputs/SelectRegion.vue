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
        'townId'
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
        update(townId) {
            this.get(townId);
            this.fire = false;
        },
        get(townId) {
            this.items = {};
            if(townId) {
                axios.get('/api/v1/world/regions', {
                    params: {
                        townId: townId
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
        townId(townId) {
            if (this.fire) {
                this.get(townId);
                this.fire = false;
            }
        }
    }
}
</script>
