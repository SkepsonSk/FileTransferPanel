<template>

    <div class="dashboard">

        <div class="dashboard__nav">
            <div class="nav__content">

                <div class="nav__user">
                    <p class="user__name"> Witaj, {{ this.username }} </p>
                </div>

                <div class="nav__actions">
                    <p
                        class="actions__action"
                        @click="logout">
                        Wyloguj </p>
                </div>

            </div>
        </div>

        <servers/>
        <usermanager/>
        <serversmanager/>

    </div>

</template>

<script>
    import ServersComponent from "./ServersComponent";
    import UserManagerComponent from "./UserManagerComponent";

    export default {
        name: "DashboardComponent",

        data() {
            return {
                username: null
            }
        },

        methods: {

            getUser() {

                axios.post('/users/current')
                .then(res => {
                    this.username = res.data.name;
                })
                .catch(err => {
                   alert(JSON.stringify(err));
                });

            },

            logout() {
                axios.post('/logout')
                .then(res => {
                    window.location.reload();
                })
                .catch(err => {

                });
            }

        },

        created() {
            this.getUser();
        }

    }
</script>

<style scoped>

    .dashboard__nav {
        width: 100%;
    }

    .nav__content {
        width: 100%;
        display: flex;
        padding: 1.2rem 5% 0 5%;
        font-size: 1.2rem;
    }

    .nav__actions {
        margin-left: auto;
    }

</style>
