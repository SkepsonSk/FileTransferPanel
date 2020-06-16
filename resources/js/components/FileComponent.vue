<template>

    <div class="file"
         v-bind:key="this.name"
         v-on:click="open"
         v-on:contextmenu="openContext($event)"
         :draggable="true"
         @dragover.stop
         @dragstart="handleDragStart">

        <div class="file__content">

            <div class="file__icon">
                <i class="fas fas fa-file" ></i>
            </div>

            <div class="file__name">{{ this.name }}</div>

        </div>

    </div>

</template>

<script>
    export default {

        data() {

            return {
                context: {display: 'none'}
            }

        },

        props: {

            server: Number,
            name: String,
            path: String

        },

        methods: {

            open() {
                let fileName = this.path + '/' + this.name;
                axios.post('/read', {id: this.server, file: fileName})
                    .then(response => {

                        let file = response.data.file;
                        this.$emit('fileRead', file, fileName);

                    })
                    .catch(error => {
                        alert('err');
                    })
            },

            handleDragStart(e) {
                e.dataTransfer.setData('fileName', this.name);
                e.dataTransfer.setData('from', this.path + '/' + this.name);
            },

            openContext(event) {
                event.preventDefault();
                let x = event.clientX;
                let y = event.clientY;
                this.$emit('contextOpened', { name: this.name, x: x, y: y });
            }

        }

    }
</script>

<style scoped>

    .file:hover .file__name {
        text-decoration: underline;
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
