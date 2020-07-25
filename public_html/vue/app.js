window.Event = new Vue();

Vue.component('alert', {
    props: ['title', 'message', 'type'],
    template: `
        <div :class="['alert', type]" @click="$emit('close')">
            <h1>{{ title }}</h1>
            <p>{{ message }}</p>
        </div>
    `
});

Vue.component('todos', {
    props: ['todos'],
    template: `
        <ul>
            <todo v-for="(todo, index) in todos" :todo="todo" :index="index"></todo>
        </ul>
    `
});

Vue.component('todo', {
    props: ['todo', 'index'],
    template: `
        <li>
            <a :class="{ complete : todo.complete }" href="#" v-on:click.prevent="removeTodo(index)">{{ todo.title }}</a>
        </li>
    `,
    methods: {
        removeTodo(index) {
            Event.$emit('remove', index);
        }
    }
})

let app = new Vue({
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
        alert: {
            title: '',
            message: '',
            show: false,
            type: ''
        }
    },
    methods: {
        addTodo(val) {
            if(this.newTodo.title != '') {
                this.todos.push({title: this.newTodo.title, complete: false});
                this.newTodo = {title: '', complete: false};
                this.alert = {
                    title: 'موفق',
                    message: 'successfuly complete',
                    type: 'alert-success',
                    show: true
                }
            } else {
                this.alert = {
                    title: 'موفق',
                    message: 'successfuly complete',
                    type: 'alert-danger',
                    show: true
                }
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
    },
    created() {
        Event.$on('remove', (index) => {
            this.removeTodo(index);
        })
    }
});
