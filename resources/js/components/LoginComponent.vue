<template>

    <div class="login__page">

        <div class="error" v-if="error">
            <div class="error__content">
                <p class="error__text"> Niepoprawne dane logowania. </p>
            </div>
        </div>

        <div class="login">

            <div class="login__content">

                <div class="login__welcome">
                    <p class="welcome__title"> Logowanie do panelu TC </p>
                </div>

                <div class="login__email">
                    <label for="email"> Adres email </label>
                    <input type="text" id="email" placeholder="Wprowadź adres email..." v-model="email">
                </div>

                <div class="login__password">
                    <label for="password"> Hasło </label>
                    <input type="password" id="password" placeholder="Wprowadź hasło..." v-model="password">
                </div>

                <div class="login__checkbox"
                     @click="switchRemember">
                    <div class="checkbox__tick">
                        <i class="tick__icon fas fa-check"
                           v-if="remember" ></i>
                    </div>
                    <p class="checkbox__text"> Zapamiętaj mnie </p>
                </div>

                <button class="login__submit" @click="submit"> Zaloguj się </button>

            </div>
        </div>

    </div>

</template>

<script>
    export default {
        name: "LoginComponent",

        props: {
            route: String,
            errors: Array
        },

        data() {
          return {
              email: '',
              password: '',
              remember: false,

              error: false
          }
        },

        methods: {

            doNotRemember() {
                return this.remember == false;
            },

            switchRemember() {
                this.remember = !this.remember;
            },

            submit() {

                axios.post(this.route, {
                    email: this.email,
                    password: this.password,
                    remember: this.remember})

                .then(res => {
                    window.location.reload();
                })
                .catch(err => {
                    this.error = true;
                });

            }
        }

    }
</script>

<style scoped>

    .login__page {
        width: 100%;
        height: 100%;
    }

    .error {
        position: absolute;
        top: 0;
        width: 100%;
        margin-top: 2rem;
    }

    .error__content{
        margin: 0 auto;
        padding: 1rem 0;
        font-size: 1rem;
        color: white;
        background-color: #ef7777;
        text-align: center;
    }

    .error__text{
        margin: 0;
    }

    .login {
        width: 40%;
        margin: auto;
        padding: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        border-radius: 0 0 5px 5px;
        border-top: 10px solid #3192a3;
        transform: translateY(50%);
    }

    .login__content {
        display: flex;
        flex-direction: column;
    }

    .login__welcome {
        margin: 1rem 0 1.75rem 0;
    }

    .welcome__title {
        margin: 0 0 1rem 0;
        font-size: 1.1rem;
        text-align: center;
    }

    .login__email, .login__password {
        align-self: center;
        display: flex;
        flex-direction: column;
        width: 50%;
        margin-bottom: 20px;
        padding: 5px 10px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    /* ------------------------------------- */

    input {
        border: none;
    }

    input:focus {
        outline: none;
    }

    label {
        margin: 0;
    }

    /* ------------------------------------- */

    .login__checkbox {
        align-self: center;
        display: flex;
        justify-content: center;
        align-content: center;
    }

    .checkbox__tick {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 1rem;
        width: 1.5rem;
        height: 1.5rem;
        border: 2.5px solid black;
        cursor: pointer;
    }

    .tick__icon--hidden {
        display: none;
    }

    /* ------------------------------------- */

    .login__submit {
        align-self: center;
        padding: .5rem 1rem;
        border: none;
        color: white;
        background-color: #3192a3;
        border-radius: 5px;
    }

</style>
