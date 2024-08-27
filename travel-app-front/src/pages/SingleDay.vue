<script>
import axios from 'axios';
import { store } from '../store.js';
import Stage from '../components/Stage.vue';

export default {
    name: 'SingleDay',
      
    data(){
      return {
        store,
        singleday: '',
        currentTrip: localStorage.getItem('currentTripId') 
      }
    },
    created() {
        this.store.dayId = this.$route.params.id; 
        console.log(this.store.dayId)
        // localStorage.getItem('currentTripId') 
        axios.get('http://localhost/travel-app/travel-app-back/api/trips.php?id=' + this.currentTrip + '&day=' + this.store.dayId).then(res => {

            this.singleday = res.data
            console.log(this.singleday.stages)

        }).catch(err => {
        console.log(err)
        });
    },
    components: {
      Stage
    },
}
</script>

<template>
    <section class="mid-section">
        <div class="container-md py-4">
            <div class="d-flex gap-3">
                <Day v-for="day in singletrip.days" :day="day"></Day>
            </div>
        </div>
    </section>
</template>

<style lang="scss">

</style>