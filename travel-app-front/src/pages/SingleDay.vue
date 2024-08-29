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
        localStorage.setItem('currentTripId', this.currentTrip)
        localStorage.setItem('currentDayId', this.store.dayId)
        // localStorage.getItem('currentTripId') 
        axios.get('http://localhost/travel-app/travel-app-back/api/trips.php?id=' + this.currentTrip + '&day=' + this.store.dayId).then(res => {

            this.singleday = res.data
            console.log(this.singleday)

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
                <Stage v-for="stage in singleday.stages" :stage="stage"></Stage>
            </div>
        </div>
    </section>
    <section>
        <div>
            <router-link :to="{name: 'stage', params: { id: this.singleday.id }  }">
                <div class="border border-1 border-black px-1 rounded-5">
                    <i class="fa-solid fa-plus"></i>
                </div>
            </router-link>
        </div>
    </section>
</template>

<style lang="scss">

</style>