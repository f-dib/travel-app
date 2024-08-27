<script>
export default {
    name: 'Stage',
    props: {
        stage: Object,
    },
}
</script>

<template>
    <router-link :to="{ name: 'singlestage', params: { id: stage.stage_number } }">
        <div class="card text-bg-dark">
            <img src="https://www.contentviewspro.com/wp-content/uploads/2017/07/default_image.png" class="card-img" alt="...">
            <div class="card-img-overlay">
                <h5 class="card-title">Tappa: {{ stage.stage_number }}</h5>
                <p class="card-text"><small>{{ stage.title }}</small></p>
            </div>
        </div>
    </router-link>
</template>

<style lang="scss">

</style>