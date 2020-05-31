<template>

    <div v-bind:key="path">

        <div class="browser">

            <uploader :class="uploader"
                      :id="this.server"
                      :path="current"
                      v-on:closed="hideUploader"/>

            <div class="browser__editor"
                 v-bind:class="{'browser__editor--hidden': editorHidden}">

                <div class="editor__content">

                    <div class="editor__tools">

                        <div class="tools__left">
                            <i class="tools__tool tools__tool--spaced fas fa-save"></i>
                            <i class="tools__tool fas fa-download"></i>
                        </div>

                        <div class="tools__right">
                            <i class="tools__tool fas fa-times"
                               v-on:click="closeEditor"></i>
                        </div>

                    </div>

                    <div class="editor__editable"
                         spellcheck="false"
                         contenteditable="true"
                         v-html="fileData"
                         v-on:blur="updateFile">
                    </div>

                </div>
            </div>

            <div class="browser__topbar">
                <ul class="dirs">
                    <li v-if="notBaseDir()" v-on:click="switchBack()"> <i class="fas fa-chevron-left"></i> </li>
                    <li class="dirs__back--inactive" v-else> <i class="fas fa-chevron-left"></i> </li>

                    <li class="dirs__dir"
                        v-on:click="openDir(start)">
                        <i class="fas fa-home"></i> </li>

                    <li class="dirs__dir" v-for="(dir, index) in getCurrent()"
                        :id="getPath(index)"
                        v-bind:key="dir"
                        v-on:click="openDir(getPath(index))"

                        @dragover.prevent
                        @drop.prevent="wideDrop">
                        {{ dir.trim() }}
                    </li>
                </ul>

                <div class="topbar__actions">
                    <p class="actions__action" @click="showUploader"> Prześlij </p>
                </div>
            </div>

            <ul class="files" v-bind:key="token">

                <directory
                    v-for="dir in this.dirs"
                    v-bind:key="dir"
                    class="files__file"
                    :name="dir"
                    :path="current"
                    v-on:click.native="openDir(getDirectory(dir))"
                    v-on:fileMoved="handleFileMoved"/>

                <file
                    v-for="file in this.files"
                    v-bind:key="file"
                    class="files__file"
                    :name="file"
                    :path="current"
                    v-on:fileRemoved="handleFileRemoved"
                    v-on:fileRead="handleFileRead"/>

            </ul>

        </div>

    </div>

</template>

<script>

    import FileComponent from "./FileComponent";
    import DirectoryComponent from "./DirectoryComponent";
    import UploaderComponent from "./UploaderComponent";

    export default {

        props: [ 'server', 'start' ],

        data() {
            return {
                current: this.start,
                length: this.start.split('/').length - 1,
                dirs: [],
                files: [],
                token: 0,

                editorHidden: true,
                uploaderHidden: true,

                fileData: ''
            }
        },

        computed: {

            uploader() {
                return {
                    'uploader--hidden': this.uploaderHidden
                }
            }

        },

        created() {
            this.openDir(this.current);
        },

        methods: {

            isUploaderHidden() { return this.uploaderHidden; },
            showUploader() { this.uploaderHidden = false; },
            hideUploader() { this.uploaderHidden = true; },

            getDirectory(fileName) { return this.current + '/' + fileName;},
            getCurrentPath() { return this.current; },

            openDir(path) {
                axios.post('/files', {id: 1, path: path})
                .then(response => {
                    this.current = path;
                    this.dirs = response.data.dirs;
                    this.files = response.data.files;
                })
                .catch(error => {
                    alert(error)
                })
            },

            switchBack() {
                let ind = this.current.lastIndexOf('/');
                this.current = this.current.substr(0, ind);
                this.openDir(this.current);
            },

            notBaseDir() { return this.current != this.start; },
            getCurrent() {return this.current.replace(this.start, '').substring(1).split('/');},

            getPath(index) {
                let path = '';
                if (index < this.length) path = this.start;
                else {
                    let arr = this.current.substring(1).split('/');

                    for (let i = 0 ; i < index+1 ; i++)
                        path += '/' + arr[i];
                }

                return path;
            },

            switchTo(index) {

                let path = '';
                if (index < this.length) path = this.start;
                else {
                    let arr = this.current.substring(1).split('/');

                    for (let i = 0 ; i < index+1 ; i++)
                        path += '/' + arr[i];
                }

                this.current = path;
                this.openDir(this.current);
            },

            wideDrop(e) {
                const target = e.target;
                alert(target.id);
            },

            dragStart(e) {
                const target = e.target;
                e.dataTransfer.setData('fileName', target.id);
            },

            handleDrop(e) {

                const target = e.target;
                const fileName = e.dataTransfer.getData('fileName');

                alert(target.id);

                return;

                const from = this.current + '/' + fileName;
                const to = this.current + '/' + target.innerHTML;

                axios.post('/move', {id: 1, from: from, to: to, fileName: fileName})
                    .then( res => {

                        const data = res.data;
                        if (data.ok)
                            this.files.splice(this.files.indexOf(fileName), 1);
                        else
                            alert(data.err);

                    })
                    .catch( err => {
                        alert('Nastąpił błąd! ' + JSON.stringify(err));
                    } );
            },

            handleFileRemoved(fileName) { this.files.splice(this.files.indexOf(fileName), 1);},

            handleFileRead(fileData) {
                for (let i = 0 ; i < fileData.length - 1 ; i++) {
                    let line = fileData[i];
                    this.fileData += '<p style="margin-bottom: 0">' + line + "</p>";
                }

                this.editorHidden = false;
            },

            handleFileMoved(delta) {

                axios.post('/move', {id: 1, from: delta.from, to: delta.to, fileName: delta.fileName})
                    .then( res => {

                        const data = res.data;
                        if (data.ok)
                            this.files.splice(this.files.indexOf(delta.fileName), 1);
                        else
                            alert(data.err);

                    })
                    .catch( err => {
                        alert('Nastąpił błąd! ' + JSON.stringify(err));
                    } );

            },

            removeHTML() {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                return doc.body.textContent || "";
            },

            updateFile(e) {
                alert(e.target.innerHTML);
            },

            closeEditor() {
                this.fileData = '';
                this.editorHidden = true;


            }

        }
    }

</script>

<style scoped>

    .browser {
        position: relative;
        height: 100%;
    }

    ul {
        list-style-type: none;
    }

    .uploader {
        position: absolute;
        width: 100%;
        height: 100%;
    }

    .uploader--hidden {
        display: none;
    }

    .browser__editor {
        position: absolute;
        width: 100%;
        height: 100%;
        padding: 15px 0;
        background: rgba(0, 0, 0, 0.6);
    }

    .browser__editor--hidden{
        display: none;
    }

    .editor__content {
        width: 80%;
        height: 100%;
        margin: auto;
    }

    .editor__tools {
        display: flex;
        align-items: center;
        width: 100%;
        height: 10%;
    }

    .tools__right {
        margin-left: auto;
    }

    .tools__tool {
        color: white;
        font-size: 1.5rem;
    }

    .tools__tool--spaced {
        margin-right: 15px;
    }

    .editor__editable {
        height: 90%;
        padding: 10px;
        background: white;
        border-radius: 5px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        white-space: pre;
        overflow-y: auto;
    }

    .browser__topbar {
        display: flex;
        justify-content: center;
        padding: 20px 40px;
        width: 100%;
    }

    .topbar__actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        align-self: center;
        width: 25%;
        margin: 0 auto 0 40px;
    }

    .actions__action {
        align-self: center;
        margin: 0;
        padding: 10px 15px;
        background: #3192a3;
        color: white;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .dirs {
        display: flex;
        width: 75%;
        margin-bottom: 0;
        padding-left: 0;
        align-items: center;
        font-size: 1.2rem;
    }

    .dirs__back--inactive {
        color: #7b7b7b;
    }

    .dirs__dir {
        margin-left: 15px;
    }

    .fa-chevron-left {
        align-self: center;
    }

    .files {
        display: flex;
        flex-wrap: wrap;
    }

    .files__file {
        display: flex;
        align-items: center;
        width: 24.25%;
        padding: 5px 5px 5px 0;
        cursor: pointer;
        transition: transform 0.25s;
    }

</style>
