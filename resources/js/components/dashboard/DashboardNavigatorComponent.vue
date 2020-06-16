<template>

    <div class="navigator">
        <div class="navigator__content">

            <div class="navigator__user">

                <p class="user__name"> {{user}} <i class="user__menu--icon fas fa-sort-down"></i> </p>

                <div class="user__menu">
                    <p class="menu__action"
                       @click="logout"> Wyloguj </p>
                </div>

            </div>

        </div>
    </div>

</template>

<script>
    export default {
        name: "DashboardNavigatorComponent",
        props: ['user'],

        methods: {

            logout() {

                axios.post('/logout')
                .then(res => {

                    window.location.reload();

                })
                .catch(err => { alert(JSON.stringify(err)); });

            }

        }

    }
</script>

<style scoped>

    .navigator {
        width: 100%;
        padding: 1rem 1rem 0 0;
    }

    .navigator__content {
        display: flex;
        width: 100%;
    }

    .navigator__user {
        width: 10%;
        position: relative;
        margin-left: auto;
        text-align: center;
    }

    .user__name {
        cursor: pointer;
        font-size: 1.1rem;
    }

    .user__menu--icon {
        margin-left: 10px;
    }

    .user__menu {
        display: none;
        position: absolute;
        width: 100%;
        left: 0;
        top: 95%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }

    .navigator__user:hover > .user__menu {
        display: block;
    }

    .menu__action {
        margin: 0;
        padding: 1rem 0;
        cursor: pointer;
    }

    .menu__action:hover {
        background-color: #a9bff6;
        color: white;
    }

</style>
