<template>

    <div class="serversmanager">

        <p class="serversmanager__title"> Wszystkie serwery </p>

        <div class="serversmanager__servers">

            <div class="servers__server" v-for="server in servers">
                <p class="server__name"> {{ server.name }} </p>

                <div class="server__actions">
                    <p class="actions__action"
                       @click="deleteServer(server.id)"> Usuń </p>
                </div>

            </div>

            <a href="/servers/create" class="servers__server">
                <p class="server__name"> <i class="fas fa-plus"></i> Nowy </p>
            </a>

        </div>

    </div>

</template>

<script>
    export default {
        name: "ServersManagerComponent",

        data() {
            return {
                servers: []
            }
        },

        methods: {

            getServer() {

                axios.post('/allservers')
                    .then(res => {
                        this.servers = res.data;
                    })
                    .catch(err => {
                        alert(JSON.stringify(err));
                    });

            },

            deleteServer(id) {

                axios.post('/servers/destroy', {id: id})
                .then(res => {
                    alert(JSON.stringify(res));
                })
                .catch(err => {
                    alert(JSON.stringify(err));
                });

            }

        },

        created() {
            this.getServer();
        }

    }
</script>

<style scoped>

    .serversmanager {
        width: 100%;
        padding: 2rem 5% 0 5%;
    }

    .serversmanager__title {
        margin: 1.5rem 0 1.5rem 0;
        font-size: 1.2rem;
    }

    .serversmanager__servers {
        display: flex;
    }

    .servers__server {
        position: relative;
        margin-right: 10px;
        padding: 2.5%;
        font-size: 1.2rem;
        border-radius: 2%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        cursor: pointer;
    }

    .server__name {
        margin: 0;
        font-size: 1.05rem;
    }

    .server__actions {
        display: none;
        position: absolute;
        top: 95%;
        left: 0;
        width: 100%;
        font-size: 1rem;
        background-color: #f8fafc;
        border-radius: 0 0 2.5% 2.5%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        text-align: center;
    }

    .servers__server:hover > .server__actions {
        display: block;
    }

    .actions__action {
        margin: 0;
        padding: .75rem 0 0.75rem 0;
        font-size: .95rem;
    }

</style>
