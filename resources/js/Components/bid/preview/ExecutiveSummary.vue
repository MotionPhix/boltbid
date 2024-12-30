<template>
  <div class="space-y-4">
    <h2 class="text-2xl font-semibold">Executive Summary</h2>
    <div class="prose dark:prose-invert max-w-none" v-html="summary" />
  </div>
</template>

<script setup lang="ts">
defineProps<{
  summary: string
}>()
</script>
