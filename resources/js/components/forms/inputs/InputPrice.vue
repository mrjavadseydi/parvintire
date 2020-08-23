<template>
    <div>
        <input
            :id="id"
            :name="name"
            @keyup="keyup"
            :placeholder="placeholder"
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
    ],
    data() {
        return {
            inputValue: ''
        }
    },
    methods: {
        keyup(event) {
            var price = this.numberFormat(event.target.value);
            event.target.value = price;
            this.$emit('input', price.split(',').join(''))
        },
        numberFormat(value) {
            value = value.split(',').join('');
            value = this.toEnglish(value);
            if (isNaN(value)) {
                if (value != '-') {
                    if(value.length == 1)
                        this.error = 'فقط اعداد مجاز هستند';

                    value = value.substring(0, value.length - 1);
                }
            } else {
                this.error = '';
            }
            value += '';
            var x = value.split('.');
            var x1 = x[0];
            var x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        },
        toEnglish(value) {
            var numbers = {
                '۰': 0,
                '۱': 1,
                '۲': 2,
                '۳': 3,
                '۴': 4,
                '۵': 5,
                '۶': 6,
                '۷': 7,
                '۸': 8,
                '۹': 9
            };
            for (const prop in numbers) {
                if (numbers.hasOwnProperty(prop)) {
                    value = value.split(`${prop}`).join(`${numbers[prop]}`);
                }
            }
            return value;
        }
    }
}
</script>
