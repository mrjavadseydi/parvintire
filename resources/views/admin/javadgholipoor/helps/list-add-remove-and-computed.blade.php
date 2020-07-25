<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        a:hover {
            color: red;
        }
        .complete {
            color: green;
            font-size: 25px;
        }
    </style>
</head>
<body>

<main id="app">

    <h1>@{{ name }}</h1>

    <ul>
        <li v-for="(todo, index) in todos">
            <a :class="{ complete : todo.complete }" href="#" v-on:click.prevent="removeTodo(index)">@{{ todo.title }}</a>
        </li>
    </ul>

    <input type="text" placeholder="new todo" v-model="newTodo.title" v-on:keyup.enter="addTodo">

    <hr>

    <h2>کارهای انجام شده</h2>
    <ul>
        <li v-for="(todo, index) in completeTodos">
            <a href="#" v-on:click.prevent="removeTodo(index)">@{{ todo.title }}</a>
        </li>
    </ul>

</main>

<script SRC="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
<script type="text/javascript">

    var app = new Vue({
        el: '#app',
        data: {
            newTodo: {
                title: '',
                complete: false
            },
            todos: [
                {title: 'title 1', complete: true},
                {title: 'title 2', complete: false},
                {title: 'title 3', complete: true},
            ],
            firstname: "javad",
            lastname: "gholipoor"
        },
        methods: {
            addTodo(val) {
                if(this.newTodo.title != '') {
                    this.todos.push({title: this.newTodo.title, complete: false});
                    this.newTodo = {title: '', complete: false};
                }
            },
            removeTodo(index) {
                this.todos[index].complete = ! this.todos[index].complete;
            }
        },
        computed: {
            completeTodos() {
                return this.todos.filter(todo => todo.complete);
            },
            name() {
                return this.firstname + ' ' + this.lastname
            }
        }
    });

</script>

</body>
</html>
