<script setup lang="ts">
import { ref, computed } from 'vue'
import {Link, router} from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SearchInput from '@/Components/SearchInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TenderCard from '@/Components/Tenders/TenderCard.vue'
import type { Tender } from '@/types'

const props = defineProps<{
  tenders: Tender[]
}>()

const search = ref('')
const status = ref('all')

const statusOptions = [
  { value: 'all', label: 'All Status' },
  { value: 'new', label: 'New' },
  { value: 'analyzing', label: 'Analyzing' },
  { value: 'bidding', label: 'Bidding' },
  { value: 'submitted', label: 'Submitted' }
]

const filteredTenders = computed(() => {
  return props.tenders.filter(tender => {
    const matchesSearch = tender.title.toLowerCase().includes(search.value.toLowerCase()) ||
      tender.client.toLowerCase().includes(search.value.toLowerCase())
    const matchesStatus = status.value === 'all' || tender.status === status.value
    return matchesSearch && matchesStatus
  })
})

function navigateToTender(tender: Tender) {
  router.visit(route('tenders.show', tender.id))
}
</script>

<template>
  <AppLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Tenders
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between mb-6">
          <div class="flex gap-4">
            <SearchInput v-model="search" placeholder="Search tenders..." />
            <SelectInput v-model="status" :options="statusOptions" />
          </div>
          <Link
            :href="route('tenders.create')"
            class="btn-primary"
          >
            New Tender
          </Link>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <TenderCard
            v-for="tender in filteredTenders"
            :key="tender.id"
            :tender="tender"
            @click="navigateToTender(tender)"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
