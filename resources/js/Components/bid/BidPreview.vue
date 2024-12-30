<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="text-center space-y-4">
      <h1 class="text-3xl font-bold">{{ bid.title }}</h1>
      <p class="text-lg text-gray-600 dark:text-gray-400">
        Prepared for {{ bid.tender.client }}
      </p>
      <p class="text-sm">
        Submission Date: {{ formatDate(bid.submission_date) }}
      </p>
    </div>

    <!-- Executive Summary -->
    <div class="space-y-4">
      <h2 class="text-2xl font-semibold">Executive Summary</h2>
      <div class="prose dark:prose-invert max-w-none" v-html="bid.executive_summary" />
    </div>

    <!-- Sections -->
    <div v-for="section in bid.sections" :key="section.id" class="space-y-4">
      <h2 class="text-2xl font-semibold">{{ section.title }}</h2>
      <div class="prose dark:prose-invert max-w-none" v-html="section.content" />
    </div>

    <!-- Requirements -->
    <div class="space-y-4">
      <h2 class="text-2xl font-semibold">Compliance Matrix</h2>
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead>
        <tr>
          <th class="text-left py-3 px-4">Requirement</th>
          <th class="text-left py-3 px-4">Response</th>
          <th class="text-left py-3 px-4">Status</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        <tr v-for="req in bid.requirements" :key="req.id">
          <td class="py-3 px-4">{{ req.requirement.description }}</td>
          <td class="py-3 px-4">{{ req.response }}</td>
          <td class="py-3 px-4">
            <Badge :variant="getStatusVariant(req.status)">
              {{ req.status }}
            </Badge>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { formatDate } from '@/utils/format'
import Badge from '@/components/ui/badge'
import type { Bid } from '@/types'

defineProps<{
  bid: Bid
}>()

function getStatusVariant(status: string) {
  return {
    pending: 'warning',
    fulfilled: 'success',
    'non-compliant': 'destructive'
  }[status] || 'default'
}
</script>
