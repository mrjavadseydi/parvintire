<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

</head>
<body>

<main id="app">
    <div class="row">
        <div class="col-12">
            <my-button></my-button>
            <my-button></my-button>
            <my-button></my-button>
            <my-button></my-button>
        </div>
    </div>
</main>

<script src="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
<script type="text/javascript">

    Vue.component('myButton', {
        template: '<button v-on:click="counter++">@{{ counter }}</button>',
        data() {
            return {
                counter: 0
            }
        }
    });

    let app = new Vue({
        el: '#app',
    });

</script>

</body>
</html>
