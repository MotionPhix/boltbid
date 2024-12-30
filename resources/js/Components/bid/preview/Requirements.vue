<template>
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
      <tr v-for="req in requirements" :key="req.id">
        <td class="py-3 px-4">{{ req.description }}</td>
        <td class="py-3 px-4 prose dark:prose-invert" v-html="req.response" />
        <td class="py-3 px-4">
          <Badge :variant="getStatusVariant(req.status)">
            {{ req.status }}
          </Badge>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import Badge from '@/components/ui/badge'
import type { BidRequirement } from '@/types'

defineProps<{
  requirements: BidRequirement[]
}>()

function getStatusVariant(status: string) {
  return {
    pending: 'warning',
    fulfilled: 'success',
    'non-compliant': 'destructive'
  }[status] || 'default'
}
</script>
