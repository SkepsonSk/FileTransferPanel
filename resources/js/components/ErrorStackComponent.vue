<template>

    <div class="errors">
        <div class="errors__content">

            <div class="error__error" v-for="error in errors">
                <i class="error__dismiss fas fa-times"
                   @click="dismiss(error.time)"></i>

                <p class="error__time"> {{error.time}} </p>
                <p class="error__text"> {{error.text}} </p>
            </div>

        </div>
    </div>

</template>

<script>
    export default {
        name: "ErrorStackComponent",
        props: ['errors'],

        methods: {

            get(time){
                for (let i = 0 ; i < this.errors.length ; i++)
                    if (this.errors[i].time == time) return this.errors[i];
                return null;
            },

            dismiss(time) {
                let ind = this.errors.indexOf(this.get(time));
                this.errors.splice(ind, 1);
            }

        },

        watch: {
            errors: function() {}
        }
    }
</script>

<style scoped>

    .errors {
        position: absolute;
        z-index: 10000;
        right: 0;
        bottom: 0;
        width: 20%;
        max-height: 60%;
        margin: 0 1rem 1rem 0;
        overscroll-behavior-y: auto;
    }

    .error__error {
        position: relative;
        margin-bottom: .9rem;
        padding: 1rem;
        background-color: #ef7777;
        color: white;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        border-radius: 5px;
    }

    .error__time {
        margin: 0;
        font-size: 1.1rem;
    }

    .error__text {
        margin: 0;
        font-size: 1rem;
    }

    .error__dismiss {
        position: absolute;
        top: 0;
        right: 0;
        margin: .2rem .4rem 0 0;
        font-size: 1rem;
        cursor: pointer;
    }

</style>
