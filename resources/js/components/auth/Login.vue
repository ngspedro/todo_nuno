<template>
   <div class="signup-form col-md-4 offset-md-4">
      <h2>Login</h2>

      <!-- Error Area -->
      <div class="alert alert-danger" role="alert" id="login-error" v-if="loginError">
         {{ loginError }}
      </div>

      <!-- Form -->
      <div class="form-group">
         <input type="email" class="form-control" name="email" placeholder="Email" required="required" v-model="username">
      </div>
      <div class="form-group">
         <input type="password" class="form-control" name="password" placeholder="Password" required="required" v-model="password"> 
      </div>
      <div class="form-group">
         <button class="btn btn-dark btn-lg btn-block" type="button" @click="login">
         <span class="spinner-border spinner-border-md" role="status" aria-hidden="true" v-if="loading"></span>
         <span v-if="!loading">Login</span>
         </button>
      </div>
      <div class="text-center">
         Don't have an account? 
         <router-link :to="{ name: 'Register' }">Register now</router-link>
      </div>
   </div>
</template>

<script>
export default {
  name: 'Login',
  props: {
    registerSuccessMessage: {
      type: String
    }
  },
  data(){
    return {
      username: '',
      password: '',
      loginError: '',
      loading: false
    }
  },
  mounted(){  
    if (this.registerSuccessMessage){
      this.$toast.success({
        title: "Registered Successfully!",
        message: "You can now login."
      })
    }
  },
  methods: {
    login(){
      this.loading = true;
      this.$http.post('/api/login', {
        username: this.username,
        password: this.password,
        }).then(
          result => {
            this.$cookie.set('todo_token', result.data.access_token); //set the logged in cookie
            this.$bus.$emit('loginChanged', 'user logged in'); //fires the event that will update the navbar
            this.$router.push({ name: 'Overview' }); //redirect to the overview route
          },
          error => {
            this.loginError = error.response.data;
            this.loading = false;
          }
        )
    }
  }
}
</script>

<style scoped>
h2 {
  margin-bottom: 15px;
}
</style>