<template>
  <div id="app">
    <AppHeader/>
    <router-view/>
  </div>
</template>

<script>
import AppHeader from '../AppHeader';

export default {
  name: 'App',
  components: {
    AppHeader
  }
}
</script>