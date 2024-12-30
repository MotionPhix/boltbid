<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          {{ bid.title }}
        </h2>
        <div class="flex items-center gap-4">
          <Badge :variant="statusVariant">{{ bid.status }}</Badge>
          <button
            v-if="bid.status === 'draft'"
            @click="submitBid"
            class="btn-primary"
          >
            Submit Bid
          </button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Executive Summary -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
          <h3 class="text-lg font-medium mb-4">Executive Summary</h3>
          <p class="text-gray-600 dark:text-gray-300">
            {{ bid.executive_summary }}
          </p>
          <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
            <div>Price: {{ formatCurrency(bid.price) }}</div>
            <div>Submitted: {{ formatDate(bid.submission_date) }}</div>
          </div>
        </div>

        <!-- Requirements -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
          <h3 class="text-lg font-medium mb-4">Requirements</h3>
          <div class="space-y-4">
            <div
              v-for="req in bid.requirements"
              :key="req.id"
              class="border dark:border-gray-700 p-4 rounded-lg"
            >
              <div class="flex items-start justify-between">
                <div>
                  <h4 class="font-medium">{{ req.requirement.description }}</h4>
                  <p class="mt-2 text-gray-600 dark:text-gray-300">
                    {{ req.response }}
                  </p>
                </div>
                <Badge :variant="getRequirementStatusVariant(req.status)">
                  {{ req.status }}
                </Badge>
              </div>
            </div>
          </div>
        </div>

        <!-- Attachments -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
          <h3 class="text-lg font-medium mb-4">Attachments</h3>
          <div class="grid gap-4 md:grid-cols-2">
            <a
              v-for="file in bid.media"
              :key="file.id"
              :href="file.original_url"
              target="_blank"
              class="flex items-center p-3 border dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              <FileIcon class="w-5 h-5 mr-2" />
              <span>{{ file.file_name }}</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Badge from '@/Components/Badge.vue'
import { FileIcon } from 'lucide-vue-next'
import { formatCurrency, formatDate } from '@/utils/format'
import type { Bid } from '@/types'

const props = defineProps<{
  bid: Bid
}>()

const statusVariant = computed(() => {
  const variants = {
    draft: 'default',
    review: 'warning',
    submitted: 'info',
    accepted: 'success',
    rejected: 'destructive'
  }
  return variants[props.bid.status]
})

function getRequirementStatusVariant(status: string) {
  const variants = {
    pending: 'default',
    fulfilled: 'success',
    non_compliant: 'destructive'
  }
  return variants[status]
}

function submitBid() {
  router.post(route('bids.submit', props.bid.id), {}, {
    preserveScroll: true
  })
}
</script>
