<template>

    <div class="uploader"
         @dragover.prevent
         @drop.prevent="addFile">

        <div class="uploader__close"
             @click="close">
            <i class="close__icon fas fa-times"></i>
        </div>

        <div class="uploader__content">

            <div class="uploader__files">

                <div class="files__file" v-for="file in files"
                     v-bind:key="file">

                    <p class="file__name"> {{file.name}} </p>
                    <i class="file__remove fas fa-times"/>
                </div>

            </div>

            <p class="uploader__upload"
               v-if="filesEmpty() == false"
               v-on:click="upload"> Prześlij </p>

            <p class="uploader__text" v-if="filesEmpty() == true"> Przeciągnij pliki tutaj </p>

        </div>

    </div>

</template>

<script>

    export default {
        props: ['id', 'path'],

        data() {
            return {
                files: []
            }
        },

        methods: {

            filesEmpty(){
                return this.files.length == 0;
            },

            addFile(e) {
                let droppedFiles = e.dataTransfer.files;
                if(!droppedFiles) return;
                this.files.push(...droppedFiles);
            },

            upload() {

                let formData = new FormData();

                formData.append('id', this.id);
                formData.append('path', this.path);

                for (let i = 0 ; i < this.files.length ; i++)
                    formData.append('files[' + i + ']', this.files[i]);

                axios.post('/upload', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                    })
                    .then(res => {
                        alert(JSON.stringify(res));
                    })
                    .catch(res => {
                        alert(JSON.stringify(res));
                });
            },

            close() {
                this.$emit('closed');
            }

        }
    }

</script>

<style scoped>

    .uploader {
        position: relative;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    .uploader__close {
        position: absolute;
        top: 0;
        right: 0;
        margin: 20px 20px 0 0;
        font-size: 2rem;
        color: white;
        cursor: pointer;
    }

    .uploader__content {
        width: 75%;
        height: 75%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 5% auto 0 auto;
        padding: 50px;
        border: 10px dashed #3192a3;
        border-radius: 5px;
        background: transparent;
    }

    .uploader__text {
        margin-bottom: 5px;
        font-size: 1.5rem;
        color: white
    }

    .uploader__upload {
        align-self: center;
        margin: 0;
        padding: 10px 15px;
        font-size: 1.2rem;
        background: #3192a3;
        color: white;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .uploader__files {
        width: 100%;
    }

    .files__file {
        display: flex;
        width: 100%;
        margin: 0 auto 10px auto;
        border-bottom: 3px solid white;
        font-size: 1.2rem;
        color: white;
    }

    .file__name {
        width: 75%;
        text-align: left;
    }

    .file__remove {
        width: 25%;
        text-align: right;
    }


</style>
