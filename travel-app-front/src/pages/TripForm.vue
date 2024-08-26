<script>
import axios from "axios";

export default {
  data() {
    return {
      trip: {
        title: "",
        description: "",
        start_date: "",
        cover: "",
      },
    };
  },
  methods: {
    submitForm() {
      axios
        .post(
          "http://localhost/travel-app/travel-app-back/api/trips.php",
          {
            title: this.trip.title,
            description: this.trip.description,
            start_date: this.trip.start_date || null,
            cover: this.trip.cover,
          },
          {
            headers: {
              "Content-Type": "application/json",
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
          this.message =
            "Si è verificato un errore durante l'invio del modulo.";
            alert(this.message);

        });
    },
  },
};
</script>
<template>
  <div class="container">
    <h1>Add your trip</h1>
    <form @submit.prevent="submitForm">
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
        <label for="trip-cover" class="form-label">Cover Link Image</label>
        <input
          type="text"
          class="form-control"
          id="trip-cover"
          v-model="trip.cover"
        />
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>
<style></style>
