<template>
   <b-modal id="createTaskModal" title="Create Task" @show="resetModal" @hidden="resetModal" @ok="createNewTask">
      <div class="form-group">
          <label for="input-live" class="input-required">Title:</label>
          <b-form-input id="input-live" v-model="newTask.title" placeholder="Enter your title" trim></b-form-input>
      </div>

      <div class="form-group">
          <label for="input-live">Due Date:</label>
          <b-form-input id="type-date" type="date" v-model="newTask.dueDate"></b-form-input>
      </div>
      
      <div class="form-group">
          <label for="input-live" class="input-required">Priority:</label>
          <b-form-select v-model="newTask.priorityId">
            <option v-for="priority in priorities" :key="priority.id" :value="priority.id">{{ priority.name }}</option>
          </b-form-select>
      </div>
   </b-modal>
</template>

<script>
export default {
  name: 'TaskListItemCreateModal',
  data() {
    return {
      newTask: {
        title: '',
        dueDate: '',
        completed: false,
        priorityId: '1',
      },
      priorities: [] //priorities to fill select
    }
  },
  mounted(){
    this.loadProperties();
  },
  methods: {
    loadProperties(){
      this.$http.get('/api/priorities').then(
        result => {
          this.priorities = result.data;
        },
        error => {
          console.log(error);
        }
      )
    },
    createNewTask(){
      if (this.newTask.title && this.newTask.priorityId) { //mandatory fields
      this.$emit('newTask', this.newTask);
      } else {
        this.$emit('newTaskError', "Please fill the form correctly.");
      }

      this.$bvModal.hide("createTaskModal");
    },

    /* Reset method to be called when modal closed/shown */
    resetModal(){
      this.newTask.title = '';
      this.newTask.dueDate = '';
      this.newTask.priorityId = '1';
    }
  }
}
</script>

<style scoped>
.input-required:after {
  content:" *";
  color:red;
  font-weight: bold;
}
</style>
