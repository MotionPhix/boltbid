<template>
  <div class="flex items-center gap-4">
    <button
      @click="exportToPDF"
      class="btn-secondary flex items-center gap-2"
    >
      <FileText class="w-4 h-4" />
      Export PDF
    </button>

    <button
      @click="exportToWord"
      class="btn-secondary flex items-center gap-2"
    >
      <FileText class="w-4 h-4" />
      Export Word
    </button>

    <button
      v-if="bid.status === 'draft'"
      @click="$emit('submit')"
      class="btn-primary"
    >
      Submit Bid
    </button>
  </div>
</template>

<script setup lang="ts">
import { FileText } from 'lucide-vue-next'
import { exportToPDF, exportToWord } from '@/utils/exportBid'
import type { Bid } from '@/types'

const props = defineProps<{
  bid: Bid
}>()

defineEmits<{
  submit: []
}>()
</script>
