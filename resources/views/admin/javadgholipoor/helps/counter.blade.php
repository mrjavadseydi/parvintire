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
    <span>count is @{{ counter }}</span>
    <button id="plus" v-on:click="counter++">plus</button>
    <button id="mines" v-on:click="counter--">mines</button>
</main>

<script SRC="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
<script type="text/javascript">

    var app = new Vue({
        el: '#app',
        data: {
            counter: 0
        }
    });

</script>

</body>
</html>
