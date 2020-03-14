<template>
  <li class="container">
    <div class="media todo-entry" v-on:click="evt => onClick(todo)">
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
        <div v-if="this.isEditing">
          <div class="field has-addons">
            <div class="control is-expanded">
              <input
                class="input"
                type="text"
                v-model="newValue"
                placeholder="Add Todo..."
                v-on:click="evt => evt.stopPropagation()"
              />
            </div>
            <div class="control">
              <div class="buttons has-addons">
                <a class="button is-info" v-on:click="(evt) => onEditClicked(todo.id, evt)">Edit</a>
                <a class="button is-info" v-on:click="onEditCancelClicked">Cancel</a>
              </div>
            </div>
          </div>
        </div>
        <div v-else>
          <span class="has-text-weight-normal" :class="{'done': todo.done}">{{ todo.item }}</span>
        </div>
      </div>

      <div class="media-right">
        <span class="icon" v-on:click="evt => onEdit(todo.id, evt)">
          <i class="fas fa-edit has-text-warning" aria-hidden="true"></i>
        </span>
        <span class="icon" v-on:click="evt => onDelete(todo.id, evt)">
          <i class="fas fa-trash has-text-danger" aria-hidden="true"></i>
        </span>
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
    onTodoClicked: Function,
    onTodoEdited: Function,
  },
  data: () => ({
    isEditing: false,
    newValue: ""
  }),
  methods: {
    onClick(todo) {
      var body = new FormData();
      body.set("done", todo.done ? 0 : 1);
      Api.post("edit/" + todo.id, body).then(response => {
        this.onTodoClicked(response.data, !todo.done);
      });
    },

    onDelete(id, evt) {
      if (confirm("Are you sure ?")) {
        Api.delete("delete/" + id).then(response => {
          this.onTodoRemoved(response.data);
        });
        evt.stopPropagation();
      }
    },

    onEdit(id, evt) {
      this.isEditing = true;
      evt.stopPropagation();
    },

    onEditClicked(id, evt) {
      var body = new FormData();
      body.set("item", this.newValue);

      Api.post("edit/" + id, body).then(response => {
        let newValue = this.newValue;
        
        this.isEditing = false;
        this.newValue = "";

        this.onTodoEdited(response.data, newValue);     
      });

      evt.stopPropagation();
    },
    onEditCancelClicked(evt) {
      this.isEditing = false;
      evt.stopPropagation();
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
  background-color: #efefefdd;
  border-radius: 4px;
}
</style>