<template>
   <div class="signup-form col-md-4 offset-md-4">
      <h2>Register</h2>
      <p class="hint-text">Create your account. It's free and only takes a minute.</p>
      
      <!-- Error Area -->
      <div class="alert alert-danger" role="alert" id="register-error" v-if="errorList[0]">
         <div id="error-list" v-for="(error, index) in errorList" :key="index">{{ error[0] }}</div>
      </div>

      <!-- Form -->
      <div class="form-group">
         <input type="text" class="form-control" name="name" placeholder="Name" v-model="name">
      </div>
      <div class="form-group">
         <input type="email" class="form-control" name="email" placeholder="Email" v-model="email">
      </div>
      <div class="form-group">
         <input type="password" class="form-control" name="password" placeholder="Password" v-model="password">
      </div>
      <div class="form-group">
         <button class="btn btn-dark btn-lg btn-block" type="button" @click="register">
         <span class="spinner-border spinner-border-md" role="status" aria-hidden="true" v-if="loading"></span>
         <span v-if="!loading">Register</span>
         </button>
      </div>
      <div class="text-center">
         Already have an account? 
         <router-link :to="{ name: 'Login' }">Login now</router-link>
      </div>
   </div>
</template>

<script>
export default {
  name: 'Register',
  data(){
    return {
      name: '',
      email: '',
      password: '',
      errorList: [],
      loading: false
    }
  },
  methods: {
    register(){
      this.loading = true;
      this.$http.post('/api/register', {
        name: this.name,
        email: this.email,
        password: this.password,
        }).then(
          result => {
            this.$router.push({ name: 'Login', params: {
              registerSuccessMessage: "Registered successfully! You can now login."
            }});
          },
          error => {
            this.errorList = Object.values(error.response.data.errors);
            this.loading = false;
          }
        )
    }
  }
}
</script>

<style scoped>
.terms, .hint-text {
  margin-left: 3px;
}

#error-list {
  display:list-item;
  margin-left: 20px;
}
</style>