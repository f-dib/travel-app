<script>
import axios from "axios";

export default {
  data() {
    return {
      trip: {
        title: "",
        description: "",
        start_date: "",
        cover: null,
        number_of_days: 1,  // Default to 1 day
      },
    };
  },
  methods: {
    handleFileUpload(event) {
      this.trip.cover = event.target.files[0]; // Salva il file selezionato in this.trip.cover
      console.log(this.trip.cover);
    },

    submitForm() {
      const formData = new FormData();
      formData.append("title", this.trip.title);
      formData.append("description", this.trip.description);
      formData.append("start_date", this.trip.start_date || null);
      formData.append("number_of_days", this.trip.number_of_days);

      if (this.trip.cover) {
        formData.append("cover", this.trip.cover); // Aggiunge il file a FormData
      } else {
        console.error("File non selezionato!");
      }
      console.log(this.trip);
      console.log(formData);

      axios
        .post(
          "http://localhost/travel-app/travel-app-back/api/trips.php",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
        .then((response) => {
          if (response.data.success) {
            this.message = "Viaggio aggiunto con successo!";
            alert(this.message);
            window.location.href = "http://localhost:5173/";
          } else {
            this.message = "Errore nell'aggiunta del viaggio.";
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
  <div class="container">
    <h1>Add your trip</h1>
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <!-- Existing form fields -->
      
      <div class="mb-3">
        <label for="trip-title" class="form-label">Trip Title</label>
        <input
          type="text"
          class="form-control"
          id="trip-title"
          aria-describedby="trip-title"
          v-model="trip.title"
        />
        <div id="trip-title" class="form-text">Insert name of your trip</div>
      </div>
      <div class="mb-3">
        <label for="trip-description" class="form-label"
          >Trip Description</label
        >
        <textarea
          class="form-control"
          id="trip-desc"
          aria-describedby="trip-desc"
          rows="3"
          v-model="trip.description"
        >
        </textarea>
        <div id="trip-title" class="form-text">Insert trip description</div>
      </div>
      <div class="mb-3">
        <label for="trip-date" class="form-label">Trip Date</label>
        <input
          type="date"
          data-date-format="YYYY/MM/DD"
          class="form-control"
          id="trip-date"
          v-model="trip.start_date"
        />
      </div>
      <div class="mb-3">
        <label for="trip-cover" class="form-label">Cover Image</label>
        <input
          type="file"
          class="form-control"
          id="trip-cover"
          @change="handleFileUpload"
        />
      </div>

      <!-- New field for the number of days -->
      <div class="mb-3">
        <label for="trip-days" class="form-label">Number of Days</label>
        <input
          type="number"
          class="form-control"
          id="trip-days"
          v-model="trip.number_of_days"
          min="1"
        />
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>

<style></style>
