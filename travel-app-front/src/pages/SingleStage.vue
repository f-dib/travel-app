<script>
import axios from 'axios';
import { store } from '../store.js';

export default {
    name: 'SingleStage',
      
    data(){
      return {
        store,
        singlestage: '',
        currentTrip: localStorage.getItem('currentTripId'),
        currentDay: localStorage.getItem('currentDayId')
      }
    },
    created() {
        this.store.stageId = this.$route.params.id; 
        console.log(this.store.stageId)
        // localStorage.getItem('currentTripId') 
        axios.get('http://localhost/travel-app/travel-app-back/api/trips.php?id=' + this.currentTrip + '&day=' + this.currentDay + '&stage=' + this.store.stageId).then(res => {

            this.singlestage = res.data
            console.log(this.singlestage)

        }).catch(err => {
        console.log(err)
        });
    }
}
</script>

<template>
    <section class="mid-section">
        <div class="container-md py-4">
            <div class="d-flex gap-3">
                <div>
                    {{ singlestage.title }}
                </div>
            </div>
        </div>
    </section>
</template>

<style lang="scss">

</style>