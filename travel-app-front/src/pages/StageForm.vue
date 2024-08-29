<script>
import axios from 'axios';
import { store } from '../store.js';

export default {
  data() {
    return {
        store,
        stage: {
            stage_number: null,
            title: '',
            description: '',
            location: '',
            latitude: null,
            longitude: null,
            image: '',
        },
    };
  },
  created (){
    this.store.day_id = this.$route.params.id; 
    console.log(this.store.day_id);
  },
  methods: {
    submitStage() {
        axios
            .post(
            "http://localhost/travel-app/travel-app-back/api/stages.php",
            {
                day_id: this.store.day_id,
                stage_number: this.stage.stage_number,
                title: this.stage.title,
                description: this.stage.description,
                location: this.stage.location,
                latitude: this.stage.latitude || null,
                longitude: this.stage.longitude || null,
                image: this.stage.image,
            },
            {
                headers: {
                "Content-Type": "application/json",
                },
            }
            )
            .then((response) => {
            if (response.data.success) {
                this.message = "Tappa aggiunta con successo!";
                alert(this.message);
                window.location.href = "http://localhost:5173/";
            } else {
                this.message = "Errore nell'aggiunta della tappa.";
                alert(this.message);
            }
            })
            .catch((error) => {
            console.error("Errore:", error);
            this.message = "Si è verificato un errore durante l'invio del modulo.";
            alert(this.message);
            });
    },
  },
};
</script>

<template>
    <div>
      <form @submit.prevent="submitStage">
        
        <div>
          <label for="stage_number">Numero Stage:</label>
          <input type="number" v-model="stage.stage_number" id="stage_number" required />
        </div>
  
        <div>
          <label for="title">Titolo:</label>
          <input type="text" v-model="stage.title" id="title" required />
        </div>
  
        <div>
          <label for="description">Descrizione:</label>
          <textarea v-model="stage.description" id="description"></textarea>
        </div>
  
        <div>
          <label for="location">Luogo:</label>
          <input type="text" v-model="stage.location" id="location" />
        </div>
  
        <div>
          <label for="latitude">Latitudine:</label>
          <input type="number" step="0.0000001" v-model="stage.latitude" id="latitude" />
        </div>
  
        <div>
          <label for="longitude">Longitudine:</label>
          <input type="number" step="0.0000001" v-model="stage.longitude" id="longitude" />
        </div>
  
        <div>
          <label for="image">Immagine URL:</label>
          <input type="text" v-model="stage.image" id="image" />
        </div>
  
        <button type="submit">Salva Stage</button>
      </form>
    </div>
  </template>

<style lang="scss">

</style>