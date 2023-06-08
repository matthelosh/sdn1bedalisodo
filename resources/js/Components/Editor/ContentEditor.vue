<script setup>
import { ref, onMounted } from 'vue';
import ContentTools from 'ContentTools';


onMounted(() => {
    const editor = ContentTools.EditorApp.get()
    editor.init('*[data-editable]','data-name')
    editor.start()
    console.log(editor.getState())
})
</script>

<template>
    <div data-editable data-name="Editor">
        <h1>Halo</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium id in voluptatem eaque sint eius libero ullam ea rerum ratione deleniti reprehenderit quo nulla, reiciendis ipsam aperiam dolor consectetur sed? Lorem ipsum dolor sit, amet consectetur adipisicing elit. Tempora quo, quasi repellendus ex ab dicta deleniti, voluptatum, voluptas vero repellat alias rerum ut? Sapiente perferendis, placeat mollitia reiciendis nam suscipit? Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde commodi corporis obcaecati ipsum! Quam necessitatibus id quidem voluptate tenetur neque, in saepe autem, modi provident enim soluta voluptas impedit dolorem!</p>
    </div>
</template>
<style scoped>
    /* Alignment styles for images, videos and iframes in editable regions */

    /* Center (default) */
    [data-editable] iframe,
    [data-editable] image,
    [data-editable] [data-ce-tag=img],
    [data-editable] img,
    [data-editable] video {
        clear: both;
        display: block;
        margin-left: auto;
        margin-right: auto;
        max-width: 100%;
    }

    /* Left align */
    [data-editable] .align-left {
        clear: initial;
        float: left;
        margin-right: 0.5em;
    }

    /* Right align */
    [data-editable].align-right {
        clear: initial;
        float: right;
        margin-left: 0.5em;
    }

    /* Alignment styles for text in editable regions */
    [data-editable] .text-center {
        text-align: center;
    }

    [data-editable] .text-left {
        text-align: left;
    }

    [data-editable] .text-right {
        text-align: right;
    }
</style>