<template>
  <div class="container has-text-centered">
    <div class="card is-primary">
      <div class="card-header has-text-dark">
        <p class="card-header-title has-text-centered">Calimed Todo List</p>
      </div>

      <div class="card-content">
        <transition-group name="list-complete" tag="ul" class="container">
          <TodoListItem
            v-for="todo in todos"
            :key="todo.id"
            :todo="todo"
            :onTodoRemoved="onTodoRemoved"
            :onTodoClicked="onTodoClicked"
            :onTodoEdited="onTodoEdited"
            class="list-complete-item"
          />
        </transition-group>

        <footer class="card-footer" id="add-field">
          <div class="field has-addons">
            <div class="control is-expanded">
              <input class="input" type="text" v-model="newItem" placeholder="Add Todo..." />
            </div>
            <div class="control">
              <a class="button is-info" v-on:click="onAdd">Add</a>
            </div>
          </div>
        </footer>

        <footer class="card-footer">
          <p class="card-footer-item">
            <span>
              View on
              <a href="https://github.com/devoluti0n/Calimed-Symfony-Vue-Demo">Github</a>
            </span>
          </p>
          <p class="card-footer-item">
            <span>
              Check my
              <a href="https://www.linkedin.com/in/alann-sapone/">LinkedIn</a>
            </span>
          </p>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
import TodoListItem from "./TodoListItem.vue";
import Api from "../api";

export default {
  name: "TodoList",
  data: () => ({
    newItem: "",
  }),
  props: {
    todos: Array,
    onTodoAdded: Function,
    onTodoRemoved: Function,
    onTodoClicked: Function,
    onTodoEdited: Function,
  },
  components: {
    TodoListItem
  },
  methods: {
    onAdd() {
      var body = new FormData();
      body.set('item', this.newItem);

      Api.post('create', body)
      .then(response => {
        this.newItem = "";
        let newTodo = response.data;
        this.onTodoAdded(newTodo);
      })
    }
  }
};
</script>

<style scoped>
#add-field {
  display: block;
  margin: 2rem 0;
}

.list-complete-item {
  transition: all 1s;
  margin-right: 10px;
}
.list-complete-enter, .list-complete-leave-to
/* .list-complete-leave-active below version 2.1.8 */ {
  opacity: 0;
  transform: translateY(30px);
}
.list-complete-leave-active {
  position: absolute;
}
</style>