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

    <todos :todos="todos"></todos>
    <input type="text" placeholder="new todo" v-model="newTodo.title" v-on:keyup.enter="addTodo">
    <alert :title="alert.title" :message="alert.message" :type="alert.type" @close="alert.show = false"></alert>

    <hr>
    <h2>کارهای انجام شده</h2>
    <ul>
        <li v-for="(todo, index) in completeTodos">
            <a href="#" v-on:click.prevent="removeTodo(index)">@{{ todo.title }}</a>
        </li>
    </ul>

</main>

<ul>
    <li v-for="(todo, index) in todos">
        <a :class="{ complete : todo.complete }"></a>
    </li>
</ul>

<script src="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
<script src="{{ asset('vue/app.js') }}?v={{ strtotime('now') }}"></script>

</body>
</html>
