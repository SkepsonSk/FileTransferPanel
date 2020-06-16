<template>

    <div class="usermanager">

        <p class="usermanager__title"> Użytkownicy </p>

        <div class="usermanager__users">

            <div class="users__user" v-for="user in users">
                <p class="user__name"> {{ user.name }} </p>

                <div class="user__actions">
                    <a :href="getPermissionsHref(user)" class="actions__action"> Uprawnienia </a>
                    <a :href="getPowerHref(user)" class="actions__action"> Moce </a>
                    <a :href="getPermissionsHref(user)" class="actions__action"> Modyfikuj </a>
                    <a :href="getPermissionsHref(user)" class="actions__action"> Usuń </a>
                </div>

            </div>

            <a href="/users/create" class="users__user">
                <p class="user__name"> <i class="fas fa-plus"></i> Nowy </p>
            </a>

        </div>

    </div>

</template>

<script>
    export default {
        name: "UserManagerComponent",

        data() {
            return {
                users: []
            }
        },

        methods: {

            getUsers() {

                axios.post('/users')
                .then(res => {
                    this.users = res.data;
                })
                .catch(err => {
                    alert(JSON.stringify(err));
                });

            },

            getPermissionsHref(user) {
                return 'users/' + user.id + '/permissions';
            },

            getPowerHref(user) {
                return 'users/' + user.id + '/power';
            }

        },

        created() {
            this.getUsers();
        }
    }
</script>

<style scoped>

    .usermanager {
        width: 100%;
        padding: 2rem 5% 0 5%;
    }

    .usermanager__title {
        margin: 1.5rem 0 1.5rem 0;
        font-size: 1.2rem;
    }

    .usermanager__users {
        display: flex;
    }

    .users__user {
        position: relative;
        margin-right: 10px;
        padding: 2.5%;
        font-size: 1.2rem;
        border-radius: 2%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        cursor: pointer;
    }

    .user__name {
        margin: 0;
        font-size: 1.05rem;
    }

    .user__actions {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 95%;
        z-index: 1000;
        left: 0;
        width: 100%;
        font-size: 1rem;
        background-color: #f8fafc;
        border-radius: 0 0 2.5% 2.5%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        text-align: center;
    }

    .users__user:hover > .user__actions {
        display: flex;
        background-color: #f8fafc;
    }

    .actions__action {
        padding: .75rem 0;
        font-size: .95rem;
    }

</style>
