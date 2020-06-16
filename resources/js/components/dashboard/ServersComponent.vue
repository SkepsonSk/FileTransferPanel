<template>

    <div class="servers">

        <p class="servers__title"> Udostępnione serwery </p>

        <div class="servers__servers" v-if="hasAccessToAnyServer">
            <div class="server" v-for="serv in this.servers">
                <a :href="getHref(serv)" class="server__name"> {{ serv.name }} </a>
            </div>
        </div>

        <div class="servers__servers" v-else>
            <p class="servers__no"> Obecnie nie masz uprawnień do zarządzania żadnym serwerem. </p>
        </div>

    </div>

</template>

<script>
    export default {
        name: "ServersComponent",

        data() {
            return {
                servers: []
            }
        },

        methods: {

            getServers() {
                axios.post('/servers')
                .then( res => {
                    this.servers = res.data.servers;
                }).catch( err => {
                    alert('Nastąpił błąd! ' + JSON.stringify(err));
                } );
            },

            hasAccessToAnyServer() {
                return server.length > 0;
            },

            getHref(serv) {
                return '/ftp/' + serv.id;
            }

        },

        created() {
            this.getServers();
        }
    }
</script>

<style scoped>

    .servers {
        padding: 2.5% 5% 0 5%;
    }

    .servers__title {
        margin: 1.5rem 0 1.5rem 0;
        font-size: 1.2rem;
    }

    .servers__servers {
        display: flex;
        font-size: 1.2rem;
    }

    .server {
        margin-right: 10px;
        padding: 5%;
        border-radius: 2%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        cursor: pointer;
    }

    .server__name {
        margin: 0;
        text-decoration: none;
        color: inherit;
    }

</style>
