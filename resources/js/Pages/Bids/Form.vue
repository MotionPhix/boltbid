<script setup lang="ts">
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { FileUpload } from '@/Components/ui/file-upload'
import type { Tender } from '@/types'

const props = defineProps<{
  tender: Tender
}>()

const form = useForm({
  tender_id: props.tender.id,
  title: '',
  executive_summary: '',
  price: 0,
  requirements: {} as Record<number, string>,
  attachments: [] as File[]
})

const submit = () => {
  form.post(route('bids.store'), {
    preserveScroll: true
  })
}
</script>

<template>
  <AppLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Create Bid for {{ tender.title }}
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <form @submit.prevent="submit" class="space-y-8">
          <!-- Basic Information -->
          <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-medium mb-4">Basic Information</h3>
            <div class="grid gap-6 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input
                  v-model="form.title"
                  type="text"
                  class="w-full input"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Price</label>
                <input
                  v-model="form.price"
                  type="number"
                  step="0.01"
                  class="w-full input"
                  required
                />
              </div>
            </div>

            <div class="mt-4">
              <label class="block text-sm font-medium mb-1">Executive Summary</label>
              <textarea
                v-model="form.executive_summary"
                rows="4"
                class="w-full input"
                required
              ></textarea>
            </div>
          </div>

          <!-- Requirements -->
          <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-medium mb-4">Requirements</h3>
            <div class="space-y-4">
              <div
                v-for="req in tender.requirements"
                :key="req.id"
                class="border dark:border-gray-700 p-4 rounded-lg"
              >
                <h4 class="font-medium">{{ req.description }}</h4>
                <div class="mt-2">
                  <label class="block text-sm font-medium mb-1">Response</label>
                  <textarea
                    v-model="form.requirements[req.id]"
                    rows="2"
                    class="w-full input"
                  ></textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Attachments -->
          <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-medium mb-4">Attachments</h3>
            <FileUpload
              v-model="form.attachments"
              :accepted-file-types="['application/pdf']"
              multiple
            />
          </div>

          <div class="flex justify-end gap-4">
            <button
              type="button"
              class="btn-secondary"
              @click="router.visit(route('tenders.show', tender.id))"
            >
              Cancel
            </button>
            <button type="submit" class="btn-primary">
              Create Bid
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
