<template>

    <div class="path" v-bind="path">
        <div class="path__content">

            <div class="path__back">
                <i class="back__icon fas fa-chevron-left"
                   @click="switchBack"></i>
            </div>

            <div class="path__path">

                <div class="path__element">
                    <p class="element__name"
                       @click="goToStart"
                       @dragover.prevent
                       @drop="handleStartDrop">
                        <i class="fas fa-home"></i></p>
                </div>

                <div class="path__element"
                     v-for="(element, index) in pathSplit">

                    <p class="element__separator">/</p>
                    <p class="element__name"
                       @click="switchTo(index)"
                       @dragover.prevent
                       @drop="handleDrop($event, index)">
                        {{element}}
                    </p>

                </div>

            </div>

        </div>
    </div>

</template>

<script>
    export default {
        name: "PathComponent",
        props: ['start', 'path'],

        data() {
            return {
                pathSplit: []
            }
        },

        methods: {

            split() {
                this.path = this.path.replace(this.start, '');

                if (this.path.length != 0) {
                    if (this.path.startsWith('/'))
                        this.path = this.path.substring(1);

                    this.pathSplit = this.path.split('/');
                }
                else this.pathSplit = [];

            },

            switchBack() {
                this.$emit('switchBack');
            },

            switchTo(index) {
                let targetPath = '';
                for (let i = 0 ; i <= index ; i++)
                    targetPath += '/' + this.pathSplit[i];

                this.$emit('switch', this.start + targetPath);
            },

            goToStart() {
                this.$emit('switchToStart')
            },

            handleStartDrop(e) {
                let fileName = e.dataTransfer.getData('fileName');
                let from = e.dataTransfer.getData('from');
                let to = this.start;
                this.$emit('fileMoved', {from: from, to: to, fileName: fileName});
            },

            handleDrop(e, index) {
                let fileName = e.dataTransfer.getData('fileName');
                let from = e.dataTransfer.getData('from');

                let targetPath = '';
                for (let i = 0 ; i <= index ; i++)
                    targetPath += '/' + this.pathSplit[i];

                let to = this.start + targetPath

                this.$emit('fileMoved', {from: from, to: to, fileName: fileName});
            }

        },

        created() {
            this.split();
        },

        watch: {
            path: function(newVal, oldVal) {
                this.split();
            }
        }

    }
</script>

<style scoped>

    .path__content {
        display: flex;
        align-items: center;
    }

    .back__icon {
        font-size: 1.4rem;
        cursor: pointer;
    }

    .path__path {
        display: flex;
        align-items: center;
        margin-left: 1rem;
        font-size: 1.2rem;
    }

    .path__element {
        display: flex;
    }

    .path__element:hover > .element__name {
        text-decoration: underline;
    }

    .element__name {
        margin: 0;
        cursor: pointer;
    }

    .element__separator {
        margin: 0 .75rem;
    }

</style>
