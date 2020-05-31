<template>

    <div class="file"
         v-bind:key="this.name"
         v-on:click="open"
         :draggable="true"
         @dragover.stop
         @dragstart="handleDragStart">

        <div class="file__content">

            <div class="file__icon">
                <i class="fas fas fa-file" ></i>
            </div>

            <div class="file__name">{{ this.name }}</div>

            <div class="file__actions">
                <i class="fas fa-times" v-on:click="remove()"></i>
            </div>

        </div>

    </div>

</template>

<script>
    export default {

        props: {

            name: String,
            path: String

        },

        methods: {

            remove() {

                let file = this.path + '/' + this.name;

                axios.post('/remove', {id: 1, file: file})
                    .then(response => {
                        this.$emit('fileRemoved', this.name);
                    })
                    .catch(error => {
                        alert(error)
                    })

            },

            open() {
                axios.post('/read', {id: 1, file: this.path + '/' + this.name})
                    .then(response => {
                        let file = response.data.file;
                        this.$emit('fileRead', file);

                    })
                    .catch(error => {
                        alert(error);
                    })
            },

            handleDragStart(e) {
                e.dataTransfer.setData('fileName', this.name);
                e.dataTransfer.setData('from', this.path + '/' + this.name);
                //alert(this.name);
            }

        }

    }
</script>

<style scoped>

    .file:hover > .file__content > .file__actions {
        opacity: 1;
    }

    .file__content {
        display: flex;
        width: 100%;
    }

    .file__icon {
        width: 25%;
    }

    .file__icon > i {
        margin: 0 auto;
        font-size: 1.5rem;
        color: #d8d8d8
    }

    .file__name {
        width: 50%;
    }

    .file__actions {
        width: 25%;
        visibility: hidden;
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

</style>
