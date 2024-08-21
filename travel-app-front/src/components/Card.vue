<script>
export default {
    name: 'Card',
    props: {
        trip: Object,
    },
}
</script>

<template>
    <div class="card text-bg-dark">
        <img :src="trip.cover" class="card-img" alt="...">
        <div class="card-img-overlay">
            <h5 class="card-title">{{ trip.title }}</h5>
            <p class="card-text">{{ trip.description }}</p>
            <p class="card-text"><small>{{ trip.start_date }}</small></p>
        </div>
    </div>
</template>

<style lang="scss">

</style>
