<script>
import axios from 'axios';
import Card from '../components/Card.vue';

export default {
    name: 'HomePage',
      
    data(){
      return {
        trips: [],
      }
    },
    created() {
      axios.get('http://localhost/travel-app/travel-app-back/api/trips.php').then(res => {

        this.trips = res.data
        console.log(this.trips)

    }).catch(err => {
      console.log(err)
    });
    },
    components: {
      Card
    },
}
</script>

<template>
    <section class="mid-section">
        <div class="container-md py-4">
            <div class="d-flex flex-column gap-3">
                <Card v-for="trip in trips" :trip="trip"></Card>
            </div>
        </div>
    </section>
</template>

<style lang="scss">
</style>
