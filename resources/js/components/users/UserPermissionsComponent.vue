<template>

    <div class="permissions">

        <p class="permissions__user"> USERNAME </p>

        <p class="permissions__title"> Uprawnienia do serwerów </p>

        <div class="permissions__permissions">

            <div class="permissions_permission" v-for="perm in permissions">
                <p class="permission__server"> {{ perm.server }} </p>
                <p class="permission__level"> Poziom: {{ perm.level }} </p>
            </div>

            <a class="permissions_permission">
                <p class="permission__server" @click="showEditor"> <i class="fas fa-plus"></i> Dodaj </p>
            </a>

        </div>

        <div class="permissions__editor" v-if="editorVisible">

            <div class="editor__servers">

                <div class="servers__server" v-for="serv in servers">
                    <p :class="{'server__selected': isSelected(id)}"
                       @click="selectServer(serv.id)"> {{ serv.name }} </p>
                </div>

            </div>

            <div class="editor__level">
                <label for="level"> Poziom uprawnień </label>
                <input id="level" type="number" v-model="editor.level">
            </div>

            <button @click="submit"> Potwierdź </button>

        </div>

    </div>

</template>

<script>
    export default {
        name: "UserPermissionsComponent",
        props: ['id'],

        data() {

            return {
                permissions: [],
                servers: [],
                editor: {},
                editorVisible: false
            }

        },

        methods: {

            getPermissions() {

                axios.post('/users/permissions', {id: this.id})
                .then(res => {
                    this.permissions = res.data;
                })
                .catch(err => {
                    alert(JSON.stringify(err));
                });

            },

            getServers() {

                axios.post('/allservers', {id: this.id})
                    .then(res => {
                        this.servers = res.data;
                    })
                    .catch(err => {
                        alert(JSON.stringify(err));
                    });

            },

            showEditor() {
                this.getServers();
                this.editorVisible = true;
            },

            selectServer(id) {
                this.editor.server = id;
            },

            isSelected(id) {
              return this.editor.server == id;
            },

            submit() {

                axios.post('/users/permissions/give', {
                    id: this.id,
                    server: this.editor.server,
                    level: this.editor.level})
                .then(res => {
                    this.servers.push(res.data.permission)
                } )
                .catch(err => { alert(JSON.stringify(err)); } );

            }

        },

        created() {
            this.getPermissions();
        }
    }
</script>

<style scoped>

    .server__selected {
        color: yellow;
    }

</style>
