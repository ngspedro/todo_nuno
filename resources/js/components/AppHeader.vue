<template>
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
      
      <!-- Logo -->
      <a class="navbar-brand" href="#">
      <img src="../../../public/img/logo_nm_inverse.svg">
      </a>

      <!-- Burguer Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu -->
      <div class="collapse navbar-collapse" id="navbarResponsive">
         <ul class="navbar-nav ml-auto">
            
            <!-- Not Logged In -->
            <li class="nav-item" v-if="!loggedIn">
               <router-link :to="{ name: 'Login' }" class="nav-link">
                  Login
               </router-link>
            </li>
            <li class="nav-item" v-if="!loggedIn">
               <router-link :to="{ name: 'Register' }" class="nav-link">
                  Register
               </router-link>
            </li>

            <!-- Logged In -->
            <li class="nav-item dropdown" v-if="loggedIn">
               <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span v-if="this.name">{{ this.name }}</span>
                  <b-spinner small v-if="!this.name"></b-spinner>
               </a>
               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#" @click="logout">Logout</a>
               </div>
            </li>
         </ul>
      </div>
   </nav>
</template>

<script>
export default {
  name: 'TheHeader',
  data(){
     return {
        name: '',
        loggedIn: this.isLoggedIn()
      }
   },
   created () {
      this.$bus.$on('loginChanged', () => {
         this.loggedIn = this.isLoggedIn()
         if (this.loggedIn){
            this.getUserDetails();
         }
      })
   },
   mounted(){
      if (this.loggedIn){
         this.getUserDetails();
         } 
   },
  methods: {
     logout(){
        this.$http.post('/api/logout').then(
           result => { //if it succeeds, logout
              this.$cookie.delete('todo_token'); //delete token
              this.$bus.$emit('loginChanged', 'user logged out'); //fires the event that will update the navbar
              this.$router.push({ name: 'Login' }); //redirect to login page
            },
            error => { //if there is some error, logout anyways
               this.$cookie.delete('todo_token');
               this.$bus.$emit('loginChanged', 'user logged out');
               this.$router.push({ name: 'Login' });
            }
        )
     },
     getUserDetails(){
        this.$http.defaults.headers.common['Authorization'] = 'Bearer ' + this.$cookie.get('todo_token')
        this.$http.get('/api/user').then(
           result => {
              this.name = result.data.name
            },
            error => {
               console.log(error);
            }
         )
     },
     isLoggedIn(){
        return this.$cookie.get('todo_token') != null;
     }
  }
}
</script>

<style scoped>
img {
    height: 30px;
    margin-right: 5px;
    background: transparent;
}

.user-name {
   cursor: default;
}

nav {
   font-size: 15px;
}

nav .nav-item > a{
  color: #c6c7c9 !important;
  }
</style>
