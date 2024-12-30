<template>
  <div v-for="section in sections" :key="section.id" class="space-y-4">
    <h2 class="text-2xl font-semibold">{{ section.title }}</h2>
    <div class="prose dark:prose-invert max-w-none" v-html="section.content" />
  </div>
</template>

<script setup lang="ts">
import type { BidSection } from '@/types'

defineProps<{
  sections: BidSection[]
}>()
</script>
