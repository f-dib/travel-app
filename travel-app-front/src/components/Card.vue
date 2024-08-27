<script>
export default {
    name: 'Card',
    props: {
        trip: Object,
    },
}
</script>

<template>
    <router-link :to="{ name: 'singletrip', params: { id: trip.id } }">
        <div class="card text-bg-dark">
            <img :src="trip.cover" class="card-img" alt="...">
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