<template>

    <form @submit.prevent="submit">

        <div class="form__email">
            <label for="email"> Adres email </label>
            <input id="email" type="text" name="email" v-model="fields.email" >
        </div>

        <div class="form__name">
            <label for="name"> Nazwa użytkownika </label>
            <input id="name" type="text" name="name" v-model="fields.name" >
        </div>

        <div class="form__password">
            <label for="password"> Hasło </label>
            <input id="password" type="password" name="password" v-model="fields.password" >
        </div>

        <input type="submit" value="Utwórz">

    </form>

</template>

<script>
    export default {
        name: "UserCreateFormComponent",

        data() {

            return {
                fields: {}
            }

        },

        methods: {

            submit() {

                axios.post('/users/store', {
                    email: this.fields.email,
                    name: this.fields.name,
                    password: this.fields.password})
                .then(res => {

                    let data = res.data;
                    if (data.ok) {
                        let href = data.href;
                        window.location.href = href;
                    }

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
