<script setup>
import { onMounted } from 'vue';

const onDrag = (e) => {
    


    // draggable.style.left = `${left+e.x}px`
    // draggable.style.top = `${top+e.y}px`
    // console.log(e)
}
const onDragStart = (e) => {
    const draggable = document.querySelector(".draggable")
    const handler = draggable.querySelector(".handler")
    const position = window.getComputedStyle(draggable)
    const left = parseInt(position.left)
    const top = parseInt(position.top)

    handler.addEventListener("mousemove", function(e) {
        draggable.style.top = `${e.y}px`
        draggable.style.left = `${e.x}px`
    })

    handler.addEventListener("mouseup", function() {
        handler.removeEventListener("mousemove", onDragStart)
    })
    // handler.removeEventListener("mousemove", onDragStart)
}

const onDragStop = (e) => {
    // alert('halo')
    const handler = document.querySelector(".draggable .handler")
    handler.removeEventListener("mousemove", onDragStart)
}

const drop = (e) => {
    const draggable = document.querySelector(".draggable")
    draggable.style.top = e.y+'px'
    draggable.style.left = e.x+'px'
    draggable.style.background = 'pink!important'
}
</script>

<template>
    <div class="draggable w-[450px] bg-white rounded-lg absolute top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%] overflow-hidden">
        <div class="handler p-4 bg-gray-200" @mousedown="onDragStart" @mouseup="drop">
            <h1 class="font-bold text-xl cursor-move">Draggable</h1>
        </div>
        <div class="content p-4">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates, consequatur, assumenda tenetur expedita non cum dolorem molestias, eius enim atque ex? Doloribus placeat repudiandae provident nemo cum nihil eum cumque. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Explicabo, itaque magnam deserunt ipsa minima earum perspiciatis, quam animi quo iusto eligendi architecto! Error atque non beatae, perspiciatis nisi dolorem saepe.</p>
        </div>
    </div>
</template>