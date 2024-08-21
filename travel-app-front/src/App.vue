<script>
import Nav from './components/Nav.vue'
import SubNav from './components/SubNav.vue';

export default {
  data(){
      return {

      }
    },
    components: {
      Nav,
      SubNav
    },
}
</script>

<template>
    <Nav></Nav>
    <router-view></router-view>
    <SubNav></SubNav>
</template>

<style>
</style>