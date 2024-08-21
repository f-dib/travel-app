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
    <Nav class="top-nav"></Nav>
    <div class="mid">
      <router-view></router-view>
    </div>
    <SubNav class="sub-nav"></SubNav>
</template>

<style>
.top-nav{
  height: 15vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: aqua;
  margin: 0;
}
.sub-nav{
  height: 15vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: aqua;
  margin: 0;
}
</style>