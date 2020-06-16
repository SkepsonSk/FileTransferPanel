<template>

    <form @submit.prevent="submit">

        <div class="">
            <label for="power"> Moc użytkownika </label>
            <input id="power" v-model="power" type="number">
        </div>

        <button type="submit"> Zatwierdź </button>

    </form>

</template>

<script>
    export default {
        name: "UserPowerComponent",
        props: ['id'],

        data() {
            return {
                power: -1
            }
        },

        methods: {

            getPower() {

                axios.post('/users/power',{
                    id: this.id
                })
                .then(res => { this.power = res.data.power; })
                .catch(err => { alert(JSON.stringify(err)); })

            },

            submit() {

                axios.post('/users/power/update',{
                    id: this.id,
                    power: this.power
                })
                    .then(res => { alert(JSON.stringify(res)); })
                    .catch(err => { alert(JSON.stringify(err)); })

            }

        },

        created() {
            this.getPower();
        }
    }
</script>

<style scoped>

</style>
