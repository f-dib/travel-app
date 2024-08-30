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
    methods: {
        deleteTrip() {
            if (confirm("Sei sicuro di voler eliminare questo viaggio? Questa azione non può essere annullata.")) {
                axios.delete('http://localhost/travel-app/travel-app-back/api/trips.php?id=' + this.store.tripId)
                .then(response => {
                    if (response.data.success) {
                        alert("Viaggio eliminato con successo!");
                        window.location.href = "http://localhost:5173/";
                    } else {
                        alert("Errore durante l'eliminazione del viaggio.");
                    }
                })
                .catch(error => {
                    console.error(error);
                });
            }
        },
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
    
    <router-link :to="{ name: 'edittrip', params: { id: this.singletrip.id } }">
        Modifica viaggio
    </router-link>
    
    <!-- Pulsante per eliminare il trip -->
    <button @click="deleteTrip">Elimina Viaggio</button>
</template>

<style lang="scss">

</style>