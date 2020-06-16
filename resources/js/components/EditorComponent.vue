<template>

    <textarea class="editor" @input="update">{{this.content}}</textarea>

</template>

<script>
    export default {
        name: "EditorComponent",
        props: ['content'],

        methods: {
            update: function(event){
                this.$emit('update', event.target.innerText);
            }
        },

    }
</script>

<style scoped>

    .editor {
        width: 100%;
        height: 100%;
        border: none;
    }

</style>
