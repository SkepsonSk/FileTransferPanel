<template>
    <div class="creator">

        <div class="creator__content">
            <div class="creator__form">

                <p class="creator__close" @click="close"><i class="fas fa-times"></i></p>

                <p class="creator__title" v-if="isFileCreator"> Wprowadź nazwę nowego pliku. </p>
                <p class="creator__title" v-else> Wprowadź nazwę nowego katalogu. </p>

                <input class="creator__input" type="text" v-model="fileName">

                <button class="creator__submit" @click="submit"> <i class="fas fa-plus"></i> Utwórz </button>
            </div>
        </div>

    </div>
</template>

<script>

    export default {
        name: "FileCreator",
        props: ['server', 'type', 'path'],

        data() {
            return {
                fileName: ''
            }
        },

        methods: {

            isFileCreator() {
                return this.type == 'file';
            },

            close() {
                this.$emit('closed');
            },

            submit() {

                let name = this.fileName;

                if (this.type == 'file') {
                    axios.post('/createfile', {id: this.server, name: name, path: this.path})
                        .then(res => {

                            if (res.data.ok)
                                this.$emit('fileCreated', name)
                                //this.files.push(fileName);

                            else
                                this.$emit('fileCreationFailed', res.data.err);
                                //alert('err');

                        })
                        .catch(err => {
                            this.$emit('fileCreationFailed', JSON.stringify(err));
                        });
                }
                else {

                    axios.post('/createdirectory', {id: this.server, name: name, path: this.path})
                        .then(res => {
                            if (res.data.ok)
                                this.$emit('directoryCreated', name)
                            else
                                this.$emit('directoryCreationFailed', res.data.err);
                        })
                        .catch(err => {
                            this.$emit('directoryCreationFailed', JSON.stringify(err));
                        });

                }

            }

        }
    }

</script>

<style scoped>

    .creator{
        position: absolute;
        z-index: 1000;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        /*  */
    }

    .creator__content {
        background-color: #f8fafc;
        max-width: 40%;
        margin: 15% auto 0 auto;
        border-radius: 5px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .creator__form {
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 1rem;
    }

    .creator__title {
        font-size: 1rem;
        text-align: center;
    }

    .creator__input {
        align-self: center;
        min-width: 50%;
        max-width: 75%;
        padding: .5rem 1rem;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        text-align: center;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .creator__submit {
        align-self: center;
        max-width: 50%;
        margin-top: 2.5%;
        padding: .5rem 1rem;
        background-color: #3192a3;
        color: white;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
    }

    .creator__close {
        position: absolute;
        top: 2.5%;
        right: 2.5%;
        font-size: 1.2rem;
        cursor: pointer;
    }

</style>
