<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-medium">{{ section.title }}</h3>
      <div class="flex items-center gap-2">
        <button
          v-if="!readonly"
          @click="$emit('save')"
          class="btn-primary text-sm"
        >
          Save Changes
        </button>
      </div>
    </div>

    <RichTextEditor
      v-model="content"
      :readonly="readonly"
      class="min-h-[300px] border dark:border-gray-700 rounded-lg"
    />

    <div v-if="section.requirements?.length" class="mt-6">
      <h4 class="font-medium mb-3">Requirements</h4>
      <div class="space-y-4">
        <div
          v-for="req in section.requirements"
          :key="req.id"
          class="p-4 border dark:border-gray-700 rounded-lg"
        >
          <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ req.description }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import RichTextEditor from '@/components/editor/RichTextEditor.vue'
import type { BidSection } from '@/types'

const props = defineProps<{
  section: BidSection
  readonly?: boolean
}>()

const content = ref(props.section.content)

watch(() => props.section.content, (newContent) => {
  content.value = newContent
})

defineEmits<{
  save: []
  update: [content: string]
}>()
</script>
