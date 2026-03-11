<script setup>
import { markRaw } from 'vue'
import {
  Pencil, Eraser, PaintBucket, Pipette,
  Minus, Circle, Square, RectangleHorizontal,
  Move, Crop, Wand2
} from 'lucide-vue-next'

const defaultPalette = [
    { id: 1,  color: '#ff0000' }, // Red
    { id: 2,  color: '#ff8800' }, // Orange
    { id: 3,  color: '#ffff00' }, // Yellow
    { id: 4,  color: '#00ff00' }, // Green
    { id: 5,  color: '#0000ff' }, // Blue
    { id: 6,  color: '#8800ff' }, // Violet
    { id: 7,  color: '#ffffff' }, // White
    { id: 8,  color: '#888888' }, // Gray
    { id: 9,  color: '#000000' }, // Black
]

const recentColors = [

]

const tools = [
  { id: 'draw',     icon: markRaw(Pencil) },
  { id: 'erase',    icon: markRaw(Eraser) },
  { id: 'fill',     icon: markRaw(PaintBucket) },
  { id: 'eyedrop',  icon: markRaw(Pipette) },
  { id: 'line',     icon: markRaw(Minus) },
  { id: 'circle',   icon: markRaw(Circle) },
  { id: 'square',   icon: markRaw(Square) },
  { id: 'rect',     icon: markRaw(RectangleHorizontal) },
  { id: 'select',   icon: markRaw(Crop) },
  { id: 'move',     icon: markRaw(Move) },
]

</script>

<template>
    <div>
        <div class="flex flex-wrap gap-2 justify-center">
            <div
                v-for="color in defaultPalette"
                :key="color.id"
                :style="{ backgroundColor: color.color }"
                class="w-12 h-12 rounded-full cursor-pointer"
            />
        </div>

        <div class="flex flex-wrap gap-2 justify-center">
            <div
                v-for="color in recentColors"
                :key="color.id"
                :style="{ backgroundColor: color.color }"
                class="w-12 h-12 rounded-full cursor-pointer"
            />
        </div>

        <hr class="mt-10 mb-10">

        <div class="grid grid-cols-2 gap-1 place-items-center w-fit mx-auto">
            <div
                v-for="tool in tools"
                :key="tool.id"
                class="p-2 rounded cursor-pointer text-gray-300 hover:bg-gray-600 hover:text-white active:bg-gray-500"
                :class="{ 'bg-gray-600 text-white': activeTool === tool.id }"
                @click="activeTool = tool.id"
            >
                <component :is="tool.icon" :size="24" />
            </div>
        </div>
    </div>
</template>
