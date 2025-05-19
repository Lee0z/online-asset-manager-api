<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  asset: Object,
});
const emit = defineEmits(['close', 'save', 'delete']);

const form = useForm({
  asset_name: '',
  kst_symbol: '',
  initial_value: '',
  current_value: '',
  acquisition_document_type: '',
  depreciation_rate: '',
  status: '',
  acquisition_date: '',
  commissioning_date: '',
  liquidation_date: '',
  liquidation_reason: '',
});

const toast = ref('');

watch(() => props.asset, (val) => {
  if (val) {
    form.asset_name = val.asset_name || '';
    form.kst_symbol = val.kst_symbol || '';
    form.initial_value = val.initial_value || '';
    form.current_value = val.current_value || '';
    form.acquisition_document_type = val.acquisition_document_type || '';
    form.depreciation_rate = val.depreciation_rate || '';
    form.status = val.status || '';
    form.acquisition_date = val.acquisition_date || '';
    form.commissioning_date = val.commissioning_date || '';
    form.liquidation_date = val.liquidation_date || '';
    form.liquidation_reason = val.liquidation_reason || '';
  } else {
    form.reset();
  }
}, { immediate: true });

function submit() {
  emit('save', { ...form, id: props.asset?.id });
  toast.value = 'Środek trwały zapisany!';
  setTimeout(() => {
    toast.value = '';
    emit('close');
  }, 1500);
}

function handleDelete() {
  if (confirm('Czy jesteś pewny?')) {
    emit('delete', props.asset?.id);
  }
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" @mousedown.self="$emit('close')">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-xl relative border border-gray-200 dark:border-gray-700" @mousedown.stop>
      <button class="absolute top-3 right-4 text-2xl text-gray-400 hover:text-red-500 transition-colors" @click="$emit('close')">&times;</button>
      <h3 class="text-2xl font-bold mb-6 text-center text-gray-700 dark:text-gray-100">
        {{ props.asset ? 'Edytuj środek trwały' : 'Dodaj środek trwały' }}
      </h3>
      <form @submit.prevent="submit">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Nazwa środka trwałego</label>
            <input v-model="form.asset_name" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl py-2 px-3" required />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Symbol KST</label>
            <input v-model="form.kst_symbol" maxlength="3" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl py-2 px-3" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Stawka amortyzacji (%)</label>
            <input v-model="form.depreciation_rate" type="number" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl py-2 px-3" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Wartość początkowa</label>
            <input v-model="form.initial_value" type="number" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl py-2 px-3" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Wartość bieżąca</label>
            <input v-model="form.current_value" type="number" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl py-2 px-3" />
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Status</label>
            <select v-model="form.status" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white rounded-xl py-2 px-3">
              <option value="">Wybierz status</option>
              <option value="nabyty">Nabyty</option>
              <option value="w użyciu">W użyciu</option>
              <option value="zlikwidowany">Zlikwidowany</option>
            </select>
          </div>
          <div v-if="form.status === 'nabyty' || form.status === 'w użyciu'" class="col-span-1">
            <label class="block text-sm font-medium mb-1">Data nabycia</label>
            <input v-model="form.acquisition_date" type="date" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl py-2 px-3" />
          </div>
          <div v-if="form.status === 'w użyciu'" class="col-span-1">
            <label class="block text-sm font-medium mb-1">Data przyjęcia do użytkowania</label>
            <input v-model="form.commissioning_date" type="date" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl py-2 px-3" />
          </div>
          <div v-if="form.status === 'zlikwidowany'" class="col-span-1">
            <label class="block text-sm font-medium mb-1">Data likwidacji</label>
            <input v-model="form.liquidation_date" type="date" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl py-2 px-3" />
          </div>
          <div v-if="form.status === 'zlikwidowany'" class="col-span-1">
            <label class="block text-sm font-medium mb-1">Powód likwidacji</label>
            <input v-model="form.liquidation_reason" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl py-2 px-3" />
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Typ dokumentu nabycia</label>
            <select v-model="form.acquisition_document_type" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 focus:border-gray-500 focus:ring-2 focus:ring-gray-300 text-gray-900 dark:text-white rounded-xl py-2 px-3">
              <option value="">Wybierz dokument</option>
              <option value="faktura">Faktura</option>
              <option value="umowa kupna-sprzedaży">Umowa kupna-sprzedaży</option>
              <option value="darowizna">Darowizna</option>
              <option value="akt własności">Akt własności</option>
              <option value="inne">Inne</option>
            </select>
          </div>
        </div>
        <div class="mt-6 flex gap-3">
          <button type="button" class="px-4 py-2 rounded bg-gray-300 text-gray-700 hover:bg-gray-400 transition-colors" @click="$emit('close')">Anuluj</button>
          <button v-if="props.asset" type="button" class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600 transition-colors" @click="handleDelete">Usuń</button>
          <button type="submit" class="px-4 py-2 rounded bg-gray-700 text-white font-semibold shadow hover:bg-gray-800 transition-colors">
            Zapisz
          </button>
        </div>
      </form>
      <div v-if="toast" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
        {{ toast }}
      </div>
    </div>
  </div>
</template>
