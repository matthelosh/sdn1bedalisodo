<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
</script>

<template>
    <div>
        {{page}}
    </div>
</template>
