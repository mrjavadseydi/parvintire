<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .alert-success {
            background: #b8ffb8;
        }
        .alert-danger {
            background: #ff9cb3;
        }
    </style>
</head>
<body>

<main id="app">

    <alert title="اخطار" message="با موفقیت انجام شد" type="success"></alert>
    <alert title="اخطار" message="با موفقیت انجام شد" type="danger"></alert>

</main>

<script src="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
<script type="text/javascript">

    Vue.component('alert', {
        template: `
            <div :class="['alert', classAlert]" v-show="isActive" @click="isActive = false">
                <h1 @click="handleClick">@{{ title }}</h1>
                <p>@{{ message }}</p>
            </div>
        `,
        props: ['title', 'message', 'type'],
        data() {
            return {
                classAlert: `alert-${this.type}`,
                isActive: true,
                handleClick: function () {
                    alert(1);
                }
            }
        }
    });

    let app = new Vue({
        el: '#app',
    });

</script>

</body>
</html>
