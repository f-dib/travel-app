<script>
import axios from 'axios';

export default {
  data() {
    return {
      tripData: {},  // Dati del trip che verranno popolati tramite API
      coverFile: null,
    };
  },
  created() {
    this.tripId = this.$route.params.id;
    axios.get('http://localhost/travel-app/travel-app-back/api/trips.php?id=' + this.tripId)
      .then(res => {
        this.tripData = res.data;
        console.log(this.tripData)
      })
      .catch(err => {
        console.log(err);
      });
  },
  methods: {
    onFileChange(event) {
      this.coverFile = event.target.files[0];
      console.log(this.coverFile)
    },
    submitForm() {
        const formData = new FormData();
        formData.append('id', this.tripData.id);
        formData.append('title', this.tripData.title);
        formData.append('description', this.tripData.description);
        formData.append('start_date', this.tripData.start_date);
        formData.append('number_of_days', this.tripData.number_of_days);

        if (this.coverFile) {
            formData.append('cover', this.coverFile);
        } else {
            formData.append('cover', this.tripData.cover);
        }

        axios.post(`http://localhost/travel-app/travel-app-back/api/trips.php`, formData,
                {
                headers: {
                "Content-Type": "multipart/form-data",
                },
            }
        )
            .then(response => {
            if (response.data.success) {
                alert("Viaggio aggiornato con successo!");
                this.$router.push({ name: 'singletrip', params: { id: this.tripData.id } });
            } else {
                alert("Errore durante l'aggiornamento del viaggio.");
            }
            })
            .catch(error => {
            console.error(error);
            });
        }
  },
};
</script>

<template>
    <div class="container-md">
        <h3>Modifica Trip</h3>
        <form @submit.prevent="submitForm" enctype="multipart/form-data">
            <div>
                <label for="title">Titolo:</label>
                <input type="text" v-model="tripData.title" id="title" required />
            </div>
    
            <div>
                <label for="description">Descrizione:</label>
                <textarea v-model="tripData.description" id="description"></textarea>
            </div>
    
            <div>
                <label for="start_date">Data di Inizio:</label>
                <input type="date" v-model="tripData.start_date" id="start_date" required />
            </div>
    
            <div>
                <label for="number_of_days">Numero di Giorni:</label>
                <input type="number" v-model="tripData.number_of_days" id="number_of_days" required />
            </div>
    
            <div>
                <label for="cover">Immagine di Copertina:</label>
                <input type="file" class="form-control" @change="onFileChange" id="cover" />
                <p v-if="tripData.cover">Immagine attuale: 
                    <img
                    class="m-2 w-25 rounded-3"
                    :src="`http://localhost/travel-app/travel-app-back/api/uploads/${tripData.cover}`"
                    alt="Cover Image"
                    />
                </p>
            </div>
    
            <button type="submit">Aggiorna</button>
        </form>
    </div>
</template>

<style></style>
