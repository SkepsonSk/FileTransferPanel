<template>

    <div class="directory"
         v-bind:key="this.name"
         @dragover.prevent
         @drop="handleDrop">

        <div class="directory__content">

            <div class="directory__icon">
                <i class="fas fa-folder-open"></i>
            </div>

            <div class="directory__name">{{ shortName }}</div>

            <div class="directory__actions">
                <i class="fas fa-times"></i>
            </div>

        </div>

    </div>

</template>

<script>
    export default {

        props: {
            name: String,
            path: String,
        },

        data() {
            return {
                shortName: ''
            }
        },

        created() {
            this.shortName = this.shortenName();
        },

        methods: {

            shortenName() {
                if (this.name.length > 16)
                    return this.name.substring(0, 15) + '..';

                else return this.name;
            },

            handleDrop(e) {
                let fileName = e.dataTransfer.getData('fileName');
                let from = e.dataTransfer.getData('from');
                let to = this.path + '/' + this.name;
                this.$emit('fileMoved', {from: from, to: to, fileName: fileName});
            }

        }
    }
</script>

<style scoped>

    .directory:hover > .directory__content > .directory__actions {
        visibility: visible;
    }

    .directory__content {
        display: flex;
        width: 100%;
    }

    .directory__icon {
        width: 25%;
    }

    .directory__icon > i {
        margin: 0 auto;
        font-size: 1.5rem;
        color: #dbbe00
    }

    .directory__name {
        width: 50%;
    }

    .directory__actions {
        width: 25%;
        visibility: hidden;
        text-align: center;
    }

</style>
