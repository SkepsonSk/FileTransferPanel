<template>

    <form @submit.prevent="submit">

        <div class="form__name">
            <label for="name"> Nazwa serwera </label>
            <input id="name" type="text" name="name" v-model="fields.name" >
        </div>

        <div class="form__path">
            <label for="path"> Ścieżka do katalogu serwera </label>
            <input id="path" type="text" name="path" v-model="fields.path" >
        </div>

        <input type="submit" value="Utwórz">

    </form>

</template>

<script>
    export default {
        name: "ServerCreateFormComponent",

        data() {
            return {
                fields: {}
            }
        },

        methods: {

            submit() {

                axios.post('/servers/store', {
                    name: this.fields.name,
                    path: this.fields.path})
                    .then(res => {

                        alert(JSON.stringify(res));

                    })
                    .catch(err => {
                        alert(JSON.stringify(err));
                    });

            }

        }
    }
</script>

<style scoped>

</style>
