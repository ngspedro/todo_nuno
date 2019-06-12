<template>
   <div class="task-item" style="position: relative;">
      <button id="remove-task" @click="removeTask">X</button> <!-- Remove button at the right -->

      <!-- Task info -->
      <div class="resizable_box">
         <button v-bind:class="['task-info', task.completed ? 'completed' : '']" 
                 @click="completeTask">

            {{ task.title }}

            <span id="extra-info">
               <b-badge :variant="task.priority.variant">
                   {{ task.priority.name }}
                </b-badge> 
                <b-badge variant="success" v-if="task.completed">
                    Completed
                </b-badge> 
            </span>

            <br />
            <div class="date">
                <b>Due Date:</b> 
                <span v-if="task.dueDate">{{ task.dueDate }}</span>
                <span v-if="!task.dueDate">None</span>
            </div>
         </button>
      </div>
   </div>
</template>

<script>
export default {
  name: 'TaskListItem',
  props: {
    task: {
        type: Object,
        required: true,
    },
    index: {
        type: Number,
        required: true,
    },
  },
  methods: {
      completeTask(){
          this.task.completed = !this.task.completed;
          this.$emit('completedTask', this.task); 
      },
      removeTask(){
          this.$emit('removedTask', this.index); 
      }
  }
}
</script>

<style scoped>
.task-item button{
    padding: 5px 10px;
}

button {
    outline: 0;
}

#remove-task {
    right: 0;
    position: absolute;
    height: 100%;
    background: #ec5840;
    color: white;
    font-weight: bold;
    border: 0;
    min-width: 40px;
}

.resizable_box {
    padding: 0 10px 0 0;
    overflow: hidden;
}

.resizable_box button {
    width: 100%;
    text-align: left;
}

.task-info {
    border: 1px solid #d9d9d9;
    background: #f2f2f2;
}

.completed {
    background: #d9ecd9; 
    border: 1px solid #aed7ae;
    color: black;
}

#completed-indicator {
    color: grey;
    font-size: 12px;
    font-style: italic;
}

#extra-info {
    margin-left: 5px;
}

.date {
    font-size: 11px;
}
</style>
