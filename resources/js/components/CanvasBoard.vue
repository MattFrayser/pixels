<script setup>
import {ref, onMounted, onUnmounted } from 'vue'

const pixels = new Map()
const canvasRef = ref(null)
let canvas = null
const containerRef = ref(null)
let isDrawing = false
let ctx = null

// Will go in store
let pixelSize = 24
let gridX = 32
let gridY = 32

onMounted(() => {
    canvas = canvasRef.value
    ctx = canvas.getContext('2d')
    canvas.addEventListener('mousedown', onMouseDown)
    canvas.addEventListener('mousemove', onMouseMove)
    canvas.addEventListener('mouseup', onMouseUp)
    drawCanvas()
})

onUnmounted(() => {
    canvas.removeEventListener('mousedown', onMouseDown)
    canvas.removeEventListener('mousemove', onMouseMove)
    canvas.removeEventListener('mouseup', onMouseUp)
})

function drawPixel(x, y) {
    ctx.fillStyle = '#ff0000'
    ctx.fillRect(x * pixelSize, y * pixelSize, pixelSize, pixelSize)
}

function getCoords(e) {
    var rect = canvasRef.value.getBoundingClientRect();
    return {
        x: Math.floor((e.clientX - rect.left) / pixelSize),
        y: Math.floor((e.clientY - rect.top) / pixelSize)
    }
}

function onMouseDown(e) {
    isDrawing = true
    drawPixel(...Object.values(getCoords(e)))
}

function onMouseMove(e) {
    if (isDrawing) {
        drawPixel(...Object.values(getCoords(e)))
    }
}

function onMouseUp(e) {
   isDrawing = false
}

function drawCheckerboard() {
    const { width, height } = canvasRef.value
    const size = pixelSize

    for (let y = 0; y < height; y += size) {
        for (let x = 0; x < width; x += size) {
            const isEven = (x / size + y / size) % 2 === 0
            ctx.fillStyle = isEven ? '#e0e0e0' : '#c0c0c0'
            ctx.fillRect(x, y, size, size)
        }
    }
}

function drawCanvas() {
    canvas.width = gridX * pixelSize
    canvas.height = gridY * pixelSize

    drawCheckerboard()
}


</script>

<template>
    <div class="flex justify-center">
        <canvas ref="canvasRef" class="bg-white" />
    </div>
</template>
