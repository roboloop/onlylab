<template>
    <div>
        <h2>OnlyTracker project</h2>
        <button @click="counter++"> Clieck me {{ counter }}</button>
    </div>
</template>

<script>
    export default {
        data: () => ({
            counter: 0
        })
    }
</script>

<style scoped>

</style>