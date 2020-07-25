<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ favicon() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0085c1">
    <title>@yield('title')</title>

    {{ renderTheme('admin') }}

    @yield('head-content')

    {{ googleAnalytics() }}

</head>
<body class="rtl sidebar-hide @yield('body-class')">

<?php
    $theme = doAction('theme');
?>

<main id="app">

    @include('admin.javadgholipoor.header')
    @include('admin.javadgholipoor.sidebar')

    <div class="content">

        <div class="page-title">
            <h1>@yield('title')</h1>
            <div class="line"></div>
            <div class="buttons">
                @yield('buttons')
                @if (trim($__env->yieldContent('filter')))
                    <button type="button" class="btn btn-outline-secondary"><i class="fa fa-filter align-middle ml-2"></i>فیلتر</button>
                @endif
            </div>
        </div>

        @include('errors.default.messages')
        @yield('content', 'default')
    </div>

</main>

@include('admin.javadgholipoor.footer')
@yield('footer-content')

<style>
    .fancybox-content {
        height: 98% !important;
    }
</style>

<script SRC="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
<script type="text/javascript">

    var app = new Vue({
        el: '#app',
        data: {
            newTodo: '',
            todos: [
                'work 1',
                'work 2',
                'work 3',
                'work 4'
            ],
            imageUrl: 'https://picsum.photos/200/300',
            imageAlt: 'this is image alt'
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
