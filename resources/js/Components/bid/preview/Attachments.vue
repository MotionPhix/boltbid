<template>
  <div class="space-y-4">
    <h2 class="text-2xl font-semibold">Attachments</h2>
    <div class="grid gap-4 md:grid-cols-2">
      <a
        v-for="file in attachments"
        :key="file.id"
        :href="file.url"
        target="_blank"
        class="flex items-center p-3 border dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700"
      >
        <FileIcon class="w-5 h-5 mr-2" />
        <span>{{ file.name }}</span>
      </a>
    </div>
  </div>
</template>

<script setup lang="ts">
import { FileIcon } from 'lucide-vue-next'
import type { BidAttachment } from '@/types'

defineProps<{
  attachments: BidAttachment[]
}>()
</script>
