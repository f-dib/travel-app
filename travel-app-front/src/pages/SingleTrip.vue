<script>
import axios from 'axios';
import { store } from '../store.js';
import Day from '../components/Day.vue';

export default {
    name: 'SingleTrip',
      
    data(){
      return {
        store,
        singletrip: '',
      }
    },
    created() {
        this.store.tripId = this.$route.params.id; 
        console.log(this.store.tripId) 
        localStorage.setItem('currentTripId', this.store.tripId)
        axios.get('http://localhost/travel-app/travel-app-back/api/trips.php?id=' + this.store.tripId).then(res => {

            this.singletrip = res.data
            console.log(this.singletrip.days)

        }).catch(err => {
        console.log(err)
        });
    },
    components: {
      Day
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