<template>
  <div id="app">
    <section class="hero is-fullheight is-primary">
      <div class="hero-body">
        <div class="columns container">
          <div class="column is-6 is-offset-3">
            <TodoList
              :todos="todos"
              :onTodoAdded="onTodoAdded"
              :onTodoRemoved="onTodoRemoved"
              :onTodoClicked="onTodoClicked"
            />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import TodoList from "./components/TodoList.vue";
import Api from "./api";

export default {
  name: "App",
  components: {
    TodoList
  },
  data() {
    return {
      todos: []
    };
  },
  created() {
    this.getTodos();
  },
  methods: {
    getTodos() {
      Api.get("list").then(response => {
        if (response.data) {
          this.todos = response.data;
        }
      });
    },

    onTodoAdded(todo) {
      this.todos.push(todo);
    },

    onTodoRemoved(id) {
      this.todos = this.todos.filter(todo => {
        return todo.id !== id;
      });
    },

    onTodoClicked(id) {
      for (let i = 0; i < this.todos.length; i++) {
        let todo = this.todos[i];
        if (todo.id === id) {
          this.$set(this.todos[i], 'done', !todo.done);
          break;
        }
      }
    }
  }
};
</script>