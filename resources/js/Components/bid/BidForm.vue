<template>
  <div class="space-y-8">
    <!-- Basic Info -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
      <h3 class="text-lg font-medium mb-4">Basic Information</h3>
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Title</label>
          <input v-model="form.title" type="text" class="input-field" required />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Executive Summary</label>
          <RichTextEditor v-model="form.executiveSummary" />
        </div>
      </div>
    </div>

    <!-- AI Generation -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium">Content Generation</h3>
        <button
          @click="generateContent"
          :disabled="generating"
          class="btn-primary"
        >
          {{ generating ? 'Generating...' : 'Generate Content' }}
        </button>
      </div>

      <div v-if="form.sections.length" class="space-y-6">
        <BidSectionEditor
          v-for="section in form.sections"
          :key="section.id"
          :section="section"
          @update="updateSection(section.id, $event)"
        />
      </div>
    </div>

    <!-- Requirements -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
      <h3 class="text-lg font-medium mb-4">Requirements</h3>
      <div class="space-y-4">
        <div
          v-for="req in tender.requirements"
          :key="req.id"
          class="p-4 border dark:border-gray-700 rounded-lg"
        >
          <p class="font-medium mb-2">{{ req.description }}</p>
          <RichTextEditor
            v-model="form.requirements[req.id]"
            placeholder="Enter your response..."
          />
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-4">
      <button @click="saveDraft" class="btn-secondary">Save Draft</button>
      <button @click="submitBid" class="btn-primary">Submit Bid</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import RichTextEditor from '@/components/editor/RichTextEditor.vue'
import BidSectionEditor from './BidSectionEditor.vue'
import { generateBidContent } from '@/services/aiWriter'
import type { Tender, BidSection } from '@/types'

const props = defineProps<{
  tender: Tender
}>()

const router = useRouter()
const generating = ref(false)

const form = ref({
  title: '',
  executiveSummary: '',
  sections: [] as BidSection[],
  requirements: {} as Record<number, string>
})

async function generateContent() {
  generating.value = true
  try {
    const sections = await generateBidContent(
      props.tender.documents[0],
      props.tender.requirements
    )
    form.value.sections = sections
  } catch (error) {
    console.error('Content generation failed:', error)
  } finally {
    generating.value = false
  }
}

async function saveDraft() {
  // Save bid as draft
  await saveBid('draft')
  router.push(`/bids/${bid.id}`)
}

async function submitBid() {
  // Submit final bid
  await saveBid('submitted')
  router.push(`/bids/${bid.id}`)
}

async function saveBid(status: 'draft' | 'submitted') {
  // Implementation
}

function updateSection(id: number, content: string) {
  const section = form.value.sections.find(s => s.id === id)
  if (section) {
    section.content = content
  }
}
</script>
