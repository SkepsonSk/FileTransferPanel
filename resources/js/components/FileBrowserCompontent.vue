<template>

        <div class="browser"
             v-bind:key="path"
             @click="reset">

            <uploader :server="server"
                      :path="current"
                      :class="uploader"
                      v-on:uploadSuccessful="uploadSuccessful"
                      v-on:uploadFailed="uploadFailed"
                      v-on:closed="hideUploader"/>

            <file-creator :server="server"
                          :type="newFileType"
                          :path="current"
                          :class="fileCreator"
                          v-on:fileCreated="handleFileCreation"
                          v-on:directoryCreated="handleDirectoryCreation"
                          v-on:fileCreationFailed="fileCreationFailed"
                          v-on:directoryCreationFailed="directoryCreationFailed"
                          v-on:closed="closeCreator"/>

            <div class="browser__editor"
                 v-bind:class="{'browser__editor--hidden': editorHidden}">

                <div class="editor__content">

                    <div class="editor__tools">

                        <div class="tools__left">
                            <i class="tools__tool tools__tool--spaced fas fa-save"
                               @click="writeToFile"></i>
                        </div>

                        <div class="tools__right">
                            <i class="tools__tool fas fa-times"
                               v-on:click="closeEditor"></i>
                        </div>

                    </div>

                    <textarea class="editor__editable" v-model="fileData"></textarea>

                </div>
            </div>

            <div class="browser__topbar">

                <navigation-path class="topbar__navigation"
                                 :start="this.start"
                                 :path="this.current"
                                 v-on:switchToStart="handleSwitchToStart"
                                 v-on:switch="handleSwitch"
                                 v-on:switchBack="handleSwitchBack"
                                 v-on:fileMoved="handleFileMoved"/>

                <div class="topbar__actions">

                    <div class="actions__action">
                        <i class="fas fa-plus"></i> Nowy <i class="fas fa-sort-down"></i>
                        <div class="action__menu">
                            <p class="menu__item" @click="createDirectory"> Katalog </p>
                            <p class="menu__item" @click="createFile"> Plik </p>
                        </div>
                    </div>

                    <p class="actions__action" @click="showUploader"> <i class="fas fa-upload"></i> Prześlij </p>
                </div>
            </div>

            <div class="browser__context"
                 :style="context">

                <div class="context__content">

                    <div class="context__option context__option--normal">
                        <p class="option__name"
                           @click="download"> Pobierz </p>
                    </div>

                    <div class="context__option context__option--danger">
                        <p class="option__name"
                           @click="removeFile"> Usuń </p>
                    </div>

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
                    :server="server"
                    :name="file"
                    :path="current"
                    v-on:fileRead="handleFileRead"
                    v-on:contextOpened="openContext"/>

            </ul>

            <error-stack :errors="errors"></error-stack>

        </div>

</template>

<script>

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
                editorFile: '',

                uploaderHidden: true,

                fileData: '',

                context: {display: 'none', left: 0, top: 0},
                fileSelected: null,

                fileCreatorHidden: true,
                newFileType: '',

                errors: []
            }
        },

        computed: {

            uploader() {
                return {
                    'hidden': this.uploaderHidden
                }
            },

            fileCreator() {
                return {
                    'hidden': this.fileCreatorHidden
                }
            }

        },

        created() {
            if (this.start == null) return;
            this.openDir(this.current);
        },

        methods: {

            error(text) {
                let now = new Date();

                let h = now.getHours();
                let m = now.getMinutes();
                let s = now.getSeconds();

                if (h < 10) h = '0' + h;
                if (m < 10) m = '0' + m;
                if (s < 10) s = '0' + s;

                let time = h + ':' + m + ':' + s;

                this.errors.unshift({time: time, text: text});
            },

            isUploaderHidden() { return this.uploaderHidden; },

            /* ------------------------------------------------------------------------------ */

            uploadSuccessful(files) {
                for (let i = 0 ; i < files.length ; i++)
                    this.files.push(files[i].name);
                this.uploaderHidden = true;
            },

            uploadFailed(message) {
                this.error(message);
                this.uploaderHidden = true;
            },

            showUploader() { this.uploaderHidden = false; },
            hideUploader() { this.uploaderHidden = true; },

            /* ------------------------------------------------------------------------------ */

            getDirectory(fileName) { return this.current + '/' + fileName;},
            getCurrentPath() { return this.current; },

            openDir(path) {
                axios.post('/files', {id: this.server, path: path})
                .then(res => {

                    let data = res.data;
                    if (data.ok) {
                        this.current = path;
                        this.dirs = data.files.dirs;
                        this.files = data.files.files;
                    }
                    else
                        this.current = this.start;

                })
                .catch(error => {
                    alert(error)
                })
            },

            /* ------------------------------------------------------------------------------ */

            handleSwitchToStart() {
                this.openDir(this.start);
            },

            handleSwitch(path) {
                this.openDir(path);
            },

            handleSwitchBack() {
                let ind = this.current.lastIndexOf('/');
                this.current = this.current.substr(0, ind);
                this.openDir(this.current);
            },

            /* ------------------------------------------------------------------------------ */

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

            handleDrop(e) {

                const target = e.target;
                const fileName = e.dataTransfer.getData('fileName');

                const from = this.current + '/' + fileName;
                const to = this.current + '/' + target.innerHTML;

                axios.post('/move', {id: 1, from: from, to: to, fileName: fileName})
                    .then( res => {

                        const data = res.data;
                        if (data.ok)
                            this.files.splice(this.files.indexOf(fileName), 1);
                        else
                            this.error(data.err);

                    })
                    .catch( err => {
                        alert('Nastąpił błąd! ' + JSON.stringify(err));
                    } );
            },

            /* ------------------------------------------------------------------------------ */

            handleFileRead(fileData, fileName) {
                this.editorFile = fileName;
                this.fileData = fileData;
                this.editorHidden = false;
            },

            writeToFile() {

                axios.post('/write', {id: this.server, file: this.editorFile, data: this.fileData})
                .then(res => {
                    let data = res.data;
                    if (!data.ok)
                        this.error(data.err);
                })
                .catch(err => {
                   alert(JSON.stringify(err));
                });

            },

            closeEditor() {
                this.fileData = '';
                this.editorHidden = true;
            },

            /* ------------------------------------------------------------------------------ */

            handleFileMoved(delta) {

                axios.post('/move', {id: this.server, from: delta.from, to: delta.to, fileName: delta.fileName})
                    .then( res => {

                        const data = res.data;
                        if (data.ok)
                            this.files.splice(this.files.indexOf(delta.fileName), 1);
                        else
                            this.error(data.err);

                    })
                    .catch( err => {
                        alert('Nastąpił błąd! ' + JSON.stringify(err));
                    } );

            },

            /* ------------------------------------------------------------------------------ */

            createDirectory() {
                this.newFileType = 'dir';
                this.fileCreatorHidden = false;
            },

            directoryCreationFailed(message) {
                this.error(message);
            },

            createFile() {
                this.newFileType = 'file';
                this.fileCreatorHidden = false;
            },

            fileCreationFailed(message) {
                this.error(message);
            },

            closeCreator() {
                this.fileCreatorHidden = true;
            },

            handleFileCreation(fileName) {
                this.files.push(fileName);
            },

            handleDirectoryCreation(directoryName) {
                this.dirs.push(directoryName);
            },

            /* ------------------------------------------------------------------------------ */

            openContext(data) {
                let name = data.name;
                let x = data.x;
                let y = data.y;

                this.fileSelected = name;
                this.context.display = 'block';
                this.context.left = x + 'px';
                this.context.top = y + 'px';
            },

            removeFile() {

                let fileName = this.fileSelected;
                let file = this.current + '/' + fileName;

                axios.post('/remove', {id: this.server, file: file})
                .then(res => {

                    if (res.data.ok)
                        this.files.splice(this.files.indexOf(fileName), 1);

                    else
                        this.error('Brak uprawnień do usuwania plików.');

                })
                .catch(err => { alert(JSON.stringify(err)); });

            },

            download() {

                let fileName = this.fileSelected;
                let file = this.current + '/' + fileName;

                axios.post('/download', {id: this.server, file: file, name: fileName})
                .then(res => {

                    if (res.data.ok != null && !res.data.ok)
                        this.error(res.data.err);

                    else {
                        let fileURL = window.URL.createObjectURL(new Blob([res.data]));
                        let fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', fileName);
                        document.body.appendChild(fileLink);

                        fileLink.click();
                    }


                })
                .catch(err => { alert(JSON.stringify(err)); })

            },

            reset() {
                this.fileSelected = null;
                this.context.display = 'none';
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

    .hidden {
        display: none;
    }

    .browser__editor {
        position: absolute;
        z-index: 1000;
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
        width: 100%;
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

    .topbar__navigation {
        width: 75%;
        align-self: center;
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
        display: inline-block;
        position: relative;
        align-self: center;
        margin: 0 0 0 10px;
        padding: 10px 15px;
        background: #3192a3;
        color: white;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .action__menu {
        display: none;
        width: 150%;
        z-index: 1000;
        position: absolute;
        left: 0;
        top: 100%;
        padding: 10px 15px;
        background-color: #f8fafc;
        color: black;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        border-radius: 5px;
    }

    .actions__action:hover > .action__menu {
        display: block;
    }

    .menu__item {
        margin: 7.5px 0;
        font-size: .95rem;
    }

    .menu__item:hover {
        text-decoration: underline;
    }


    /* ----------------------------------------------------------------------- */

    .browser__context {
        position: absolute;
        width: 7.5%;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        font-size: 1rem;
        text-align: center;
        background-color: #f8fafc;
    }

    .context__option {
        cursor: pointer;
    }

    .context__option--danger:hover {
        background-color: #ef7777;
        color: white;
    }

    .option__name {
        margin: 0;
        padding: 1rem 0;
    }

    /* ----------------------------------------------------------------------- */

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
