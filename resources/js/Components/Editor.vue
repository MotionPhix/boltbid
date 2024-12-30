<template>
  <div class="rich-text-editor">
    <editor-content :editor="editor" class="prose dark:prose-invert max-w-none" />

    <div v-if="!readonly" class="border-t mt-2 pt-2 flex gap-2">
      <button
        v-for="item in toolbarItems"
        :key="item.name"
        @click="item.action"
        class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
        :class="{ 'bg-gray-100 dark:bg-gray-700': item.isActive?.() }"
      >
        <component :is="item.icon" class="w-4 h-4" />
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onBeforeUnmount } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import {
  Bold, Italic, List, ListOrdered,
  Heading1, Heading2, Quote
} from 'lucide-vue-next'

const props = withDefaults(defineProps<{
  modelValue?: string
  readonly?: boolean
}>(), {
  modelValue: '',
  readonly: false
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const editor = useEditor({
  content: props.modelValue,
  editable: !props.readonly,
  extensions: [StarterKit],
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  }
})

const toolbarItems = [
  {
    name: 'bold',
    icon: Bold,
    action: () => editor.value?.chain().focus().toggleBold().run(),
    isActive: () => editor.value?.isActive('bold')
  },
  {
    name: 'italic',
    icon: Italic,
    action: () => editor.value?.chain().focus().toggleItalic().run(),
    isActive: () => editor.value?.isActive('italic')
  },
  {
    name: 'h1',
    icon: Heading1,
    action: () => editor.value?.chain().focus().toggleHeading({ level: 1 }).run(),
    isActive: () => editor.value?.isActive('heading', { level: 1 })
  },
  {
    name: 'h2',
    icon: Heading2,
    action: () => editor.value?.chain().focus().toggleHeading({ level: 2 }).run(),
    isActive: () => editor.value?.isActive('heading', { level: 2 })
  },
  {
    name: 'bullet-list',
    icon: List,
    action: () => editor.value?.chain().focus().toggleBulletList().run(),
    isActive: () => editor.value?.isActive('bulletList')
  },
  {
    name: 'ordered-list',
    icon: ListOrdered,
    action: () => editor.value?.chain().focus().toggleOrderedList().run(),
    isActive: () => editor.value?.isActive('orderedList')
  },
  {
    name: 'blockquote',
    icon: Quote,
    action: () => editor.value?.chain().focus().toggleBlockquote().run(),
    isActive: () => editor.value?.isActive('blockquote')
  }
]

onBeforeUnmount(() => {
  editor.value?.destroy()
})
</script>

<style>
.rich-text-editor :deep(.ProseMirror) {
  @apply min-h-[200px] outline-none;
}

.rich-text-editor :deep(.ProseMirror p.is-editor-empty:first-child::before) {
  content: attr(data-placeholder);
  @apply text-gray-400 float-left h-0 pointer-events-none;
}
</style>
