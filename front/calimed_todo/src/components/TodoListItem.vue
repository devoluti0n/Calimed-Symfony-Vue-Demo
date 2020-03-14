<template>
  <li class="container">
    <div class="media todo-entry" v-on:click="evt => onClick(todo.id)">
      <figure class="media-left">
        <span class="icon has-text-primary">
          <div v-if="this.todo.done">
            <span>
              <i class="far fa-check-square" aria-hidden="true"></i>
            </span>
          </div>
          <div v-else>
            <span>
              <i class="far fa-square" aria-hidden="true"></i>
            </span>
          </div>
        </span>
      </figure>

      <div class="media-content">
        <span class="has-text-weight-normal" :class="{'done': todo.done}">{{ todo.item }}</span>
      </div>

      <div class="media-right">
        <a class="delete is-danger has-background-danger" v-on:click="evt => onDelete(todo.id, evt)"></a>
      </div>
    </div>
  </li>
</template>

<script>
import Api from "../api";

export default {
  name: "TodoListItem",
  props: {
    todo: Object,
    onTodoRemoved: Function,
    onTodoClicked: Function
  },
  methods: {
    onDelete(id, evt) {
      if (confirm('Are you sure ?')) {
        Api.delete("delete/" + id).then(response => {
          this.onTodoRemoved(response.data);
        });
        evt.stopPropagation();
      }
    },
    onClick(id) {
      Api.put("toggle/" + id).then(response => {
        this.onTodoClicked(response.data);
      });
    }
  }
};
</script>

<style scoped>
.done {
  text-decoration: line-through;
}

.container .todo-entry {
  padding: 1rem 0.5rem;
  cursor: pointer;
}

.container .todo-entry:hover {
  background-color: #EFEFEFDD;
  border-radius: 4px;
}
</style>