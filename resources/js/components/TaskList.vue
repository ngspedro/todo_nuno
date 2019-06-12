<template>
   <main role="main" class="col-md-8 offset-md-2">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
         <!-- Title -->
         <h1 class="h2">Your Tasks</h1>

         <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group flex-wrap">
               <input id="search-input" placeholder="Search" v-model="search"/> <!-- Search Input -->

               <!-- Filter Area -->
               <div class="btn-group">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                     </svg>
                     {{ currDateFilter }}
                  </button>
                  <div class="dropdown-menu">
                     <a v-for="dateFilter in dateFiltersToDisplay" 
                        :key="dateFilter" 
                        @click="setNewCurrDateFilter(dateFilter)"
                        class="dropdown-item" href="#" >{{dateFilter}}</a>
                  </div>
               </div>

               <!-- Sort By Area -->
               <div class="btn-group">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <b>Sort by:</b> {{ currSortBy }}
                  </button>
                  <div class="dropdown-menu">
                     <a v-for="sortOption in sortByOptionsToDisplay" 
                        :key="sortOption" 
                        @click="setCurrSortBy(sortOption)"
                        class="dropdown-item" href="#" >{{sortOption}}</a>
                  </div>
               </div>

               <!-- Add Button -->
               <button class="btn btn-sm btn-outline-secondary" v-b-modal.createTaskModal>
                  <svg class="feather feather-plus" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-reactid="986">
                     <line x1="12" y1="5" x2="12" y2="19"></line>
                     <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                  Add
               </button>

               <!-- Show/Hide Completed Button -->
               <button class="btn btn-sm btn-outline-secondary" @click="triggerHideCompleted">
                  <span v-if="hideCompleted">
                     <svg class="feather feather-eye sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                     </svg>
                     Show Completed
                  </span>
                  <span v-if="!hideCompleted">
                     <svg class="feather feather-eye-off sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                     </svg>
                     Hide Completed
                  </span>
               </button>
            </div>
         </div>
      </div>

      <!-- New Task Modal Component -->
      <TaskListItemCreateModal @newTask="createNewTask" @newTaskError="createNewTaskError"/>

      
      <!-- Content -->
      <b-spinner label="Spinning" v-if="loading"></b-spinner>
      <div id="tasksContainer" v-if="!loading">
         <span v-if="!filteredTasks.length">No tasks to show...</span>
         <TaskListItem  v-for="(task, index) in filteredTasks" 
                        :key="task.id" 
                        :task="task" 
                        :index="index" 
                        @completedTask="completeTask" 
                        @removedTask="removeTask"/>
         
         <!-- Bottom Info (TODO: Pagination) -->
         <div class="bottom-container" v-if="tasks.length > 0">
            <hr>
            <span>Showing {{ filteredTasks.length }} of {{ tasks.length }}</span>
         </div>
      </div>
   </main>
</template>

<script>
import TaskListItem from './TaskListItem';
import TaskListItemCreateModal from './TaskListItemCreateModal';

export default {
  name: 'TaskList',
  components: {
      TaskListItem, TaskListItemCreateModal
  },
  data () {
    return {
      tasks: [],
      loading: true, 
      
      /* Filter Stuff */
      search: '', 
      availableDateFilters: [ "All", "Today", "Next 7 days", "Overdue"],
      currDateFilter: 'All',
      hideCompleted : true,
      
      /* Sort Stuff */
      sortByOptions: [ "None", "Date", "Priority"],
      currSortBy: 'None',
    }
  },
  mounted(){ 
    this.getTasks();
  },
  computed: {
    filteredTasks() {
      let result = this.tasks;
      
      /* Sorting */
      if (this.currSortBy == "None"){
        result = this.tasks.sort((a, b) => {
          return a.id - b.id;
        });
      }

      if (this.currSortBy == "Date"){
        result = this.tasks.sort((a, b) => {
          if (!a.dueDate)
            return 1;
          else if (!b.dueDate)
            return -1;
          
          return new Date(a.dueDate) - new Date(b.dueDate);
        });
      }

      /* This can be done like this because the priorities table was populated by me
      and i know how the priorities are ordered. If i would let each user manage their priorities instead
      of having those 4 presets, then this would need to be changed */
      if (this.currSortBy == "Priority"){
        result = this.tasks.sort((a, b) => {
          return b.priority.id - a.priority.id;
        });
      }

      /* Filtering */
      result = this.tasks.filter(task => {
        return (task.title.toLowerCase().indexOf(this.search.toLowerCase()) > -1 ||  //searchInput -> title filter
               (task.priority.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1)) && //searchInput -> priority
               (!this.hideCompleted || (this.hideCompleted && !task.completed)) &&      //show/hide completed
               this.filterByDate(task);
      });

      return result;
    },

    /* Properties to display only the options that aren't the selected one. */
    sortByOptionsToDisplay() {
      return this.sortByOptions.filter(filter => {
        return filter != this.currSortyBy;
      })
    },
    dateFiltersToDisplay() {
      return this.availableDateFilters.filter(filter => {
        return filter != this.currDateFilter;
      })
    },
  },
  methods: {
    /* Database Operations */
      getTasks(){
        this.$http.get('/api/tasks').then(
          result => {
            this.tasks = result.data;
            this.loading = false;
            },
          error => {
            console.log(error);
          }
        )
      },
      createNewTask(newTask){
        this.$http.post('/api/tasks', {
                    title: newTask.title,
                    dueDate: newTask.dueDate,
                    completed: newTask.completed,
                    priorityId: newTask.priorityId
                }).then(
                  result => {
                    this.tasks.push(result.data);
                  },
                  error => {
                    createNewTaskError("Something went wrong.");
                  }
                )
      },
      completeTask(task){
        this.$http.put('/api/tasks/' + task.id, {
          completed: task.completed
          }).then(
            result => {
              if (this.hideCompleted){
                this.taskCompletedSuccess(task.title);
              }
            },
            error => {
              console.log(error);
            }
          )
      },
      removeTask(index){
        this.$http.delete('/api/tasks/' + this.tasks[index].id)
        .then(res => {
          this.tasks.splice(index, 1);
        })
        .catch(err => {
          console.log(err);
        })
      },

      /* Toast messages  */
      taskCompletedSuccess(taskTitle){
        this.$toast.success({
          title: "Task Completed",
          message: "Congratulations on completing: " + taskTitle
        })
      },
      createNewTaskError(msg){
        this.$toast.error({
          title: "Error Creating Task",
          message: msg
        })
      },

      /* Setters for the current sortBy & dateFilter  */
      triggerHideCompleted(){
          this.hideCompleted = !this.hideCompleted;
      },
      setCurrSortBy(newSortBy){
          this.currSortBy = newSortBy;
      },
      setNewCurrDateFilter(newDateFilter){
          this.currDateFilter = newDateFilter;
      },
      
      /* Methods to help deal with date filtering */
      filterByDate(task){
        const taskDate = new Date(task.dueDate);
        const today = new Date();

        if (this.currDateFilter == 'Today')
           return this.isToday(taskDate);
           
        if (this.currDateFilter == 'Overdue')
          return task.dueDate && taskDate < today && !this.isToday(taskDate);
        
        if (this.currDateFilter == 'Next 7 days')
          return task.dueDate && taskDate < today.setDate(today.getDate() + 7);
        
        return true;
      },
      isToday(date){
        const today = new Date();

        return date.getDate() == today.getDate() &&
               date.getMonth() == today.getMonth() &&
               date.getFullYear() == today.getFullYear()
     }
  }
}
</script>

<style scoped> 
.dropdown {
    margin-right: 10px;
}

#tasksContainer {
  overflow: auto !important;
}

#tasksContainer div {
    margin: 10px 0;
}

#search-input {
  padding: 5px;
  min-width: 250px;
  outline: none;
}

.dropdown-toggle {
  margin-right: 20px;
}

main {
  background: white;
  border: 1px solid #d9d9d9;
  padding: 0 30px 20px 30px;
}

@media (max-width: 1309px) {
   input {
     width: 100%;
   }
    button{
        width:50%;
        margin: 3px 0;
    }
}

.bottom-container {
  font-size: small;
  width: 100%;
  margin: 0 auto;
  text-align: center
}
</style>
