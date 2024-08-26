<script>
export default {
  name: 'SubNav'
}
</script>

<template>
    <div class="sub-nav">
        <router-link :to="{name: 'trip' }">
            <div class="border border-1 border-black px-1 rounded-5">
                <i class="fa-solid fa-plus"></i>
            </div>
        </router-link>
    </div>
</template>

<style lang="scss">
    .sub-nav{
        height: 10vh;
        display: flex;
        justify-content: center;
        align-items: center;
        border-top: 1px solid black;
    }
</style>
