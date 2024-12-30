<template>
  <div class="text-center space-y-4 pb-6 border-b dark:border-gray-700">
    <h1 class="text-3xl font-bold">{{ bid.title }}</h1>
    <p class="text-lg text-gray-600 dark:text-gray-400">
      Prepared for {{ bid.tender.client }}
    </p>
    <p class="text-sm text-gray-500">
      Submission Date: {{ formatDate(bid.submissionDate) }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { formatDate } from '@/utils/format'
import type { Bid } from '@/types'

defineProps<{
  bid: Bid
}>()
</script>
