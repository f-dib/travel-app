<script>
export default {
    name: 'Card',
    props: {
        trip: Object,
    },
    methods: {
        getImageUrl(imageName) {
          if (imageName) {
            return `http://localhost/travel-app/travel-app-back/api/uploads/${imageName}`;
          }
          return "path/to/default-image.jpg"; // Immagine di default se non c'è cover
        },
    }
}
</script>

<template>
    <router-link :to="{ name: 'singletrip', params: { id: trip.id } }">
        <div class="card text-bg-dark">
            <img :src="getImageUrl(trip.cover)" class="card-img" :alt="`${trip.title}`">
            <div class="card-img-overlay">
                <h5 class="card-title">{{ trip.title }}</h5>
                <p class="card-text">{{ trip.description }}</p>
                <p class="card-text"><small>{{ trip.start_date }}</small></p>
            </div>
        </div>
    </router-link>
</template>

<style lang="scss">

</style>