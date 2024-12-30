<script setup lang="ts">
import BidPreviewHeader from './BidPreviewHeader.vue'
import BidPreviewExecutiveSummary from './BidPreviewExecutiveSummary.vue'
import BidPreviewSections from './BidPreviewSections.vue'
import BidPreviewRequirements from './BidPreviewRequirements.vue'
import BidPreviewAttachments from './Attachments.vue'
import type { Bid } from '@/types'

defineProps<{
  bid: Bid
}>()
</script>

<template>
  <div class="space-y-8 p-8 bg-white dark:bg-gray-800 rounded-lg shadow" id="bid-preview">
    <BidPreviewHeader :bid="bid" />
    <BidPreviewExecutiveSummary :summary="bid.executiveSummary" />
    <BidPreviewSections :sections="bid.sections" />
    <BidPreviewRequirements :requirements="bid.requirements" />
    <BidPreviewAttachments :attachments="bid.attachments" />
  </div>
</template>
