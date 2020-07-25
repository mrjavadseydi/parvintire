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

    <ul>
        <li v-for="todo in todos">
            <a href="#" v-on:click.prevent.once="removeTodo(index)">@{{ todo }}</a>
        </li>
    </ul>

    <input type="text" placeholder="new todo" v-model="newTodo" v-on:keyup.enter="addTodo('javad')">

    <div>
        <img :src="imageUrl" :alt="altImage" :style="style" :class="{ image : activeClass }">
    </div>

</main>

<script SRC="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
<script type="text/javascript">

    var app = new Vue({
        el: '#app',
        data: {
            newTodo: '',
            todos: [
                'work 1',
                'work 2',
                'work 3'
            ],
            imageUrl: 'https://hoorbook.com/uploads/2020/02/177x270-5e5a16ef0635e.jpg',
            altImage: 'یک کتاب',
            style: {
                width: '200px',
                border: '10px solid #ddd',
                borderRadius: '50px',
                marginTop: '10px'
            },
            activeClass: true
        },
        methods: {
            addTodo: function (val) {
                if(this.newTodo != '') {
                    this.todos.push(val + ' ' + this.newTodo);
                    this.newTodo = '';
                }
            },
            removeTodo: function (index) {
                this.todos.splice(index, 1);
            }
        }
    });

</script>

</body>
</html>
