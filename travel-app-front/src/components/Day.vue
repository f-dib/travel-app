<script>
export default {
    name: 'Day',
    props: {
        day: Object,
    },
}
</script>

<template>
    <router-link :to="{ name: 'singleday', params: { id: day.day_number } }">
        <div class="card text-bg-dark">
            <img src="https://www.contentviewspro.com/wp-content/uploads/2017/07/default_image.png" class="card-img" alt="...">
            <div class="card-img-overlay">
                <h5 class="card-title">Giorno: {{ day.day_number }}</h5>
                <!-- <p class="card-text">{{ trip.description }}</p> -->
                <p class="card-text"><small>{{ day.date }}</small></p>
            </div>
        </div>
    </router-link>
</template>

<style lang="scss">

</style>