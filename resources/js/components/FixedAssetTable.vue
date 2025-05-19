<script setup lang="ts">
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AssetModal from './AssetModal.vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import timesNormal from '@/lib/fonts/times-normal.js';
import timesBold from '@/lib/fonts/times-bold.js';

const props = defineProps({
  assets: Array,
});

const showModal = ref(false);
const editAsset = ref<Record<string, any> | undefined>(undefined);

const showReportModal = ref(false);

function openReportModal() {
  showReportModal.value = true;
}
function closeReportModal() {
  showReportModal.value = false;
}
function generateReport() {
  const page: any = usePage();
  const company = page.props.company || {};
  const user = page.props.auth?.user || {};
  const doc = new jsPDF({
    orientation: 'p',
    unit: 'mm',
    format: 'a4',
  });

  doc.addFileToVFS('TimesNewRoman.ttf', timesNormal);
  doc.addFont('TimesNewRoman.ttf', 'TimesNewRoman', 'normal');
  doc.addFileToVFS('TimesNewRoman-Bold.ttf', timesBold);
  doc.addFont('TimesNewRoman-Bold.ttf', 'TimesNewRoman', 'bold');

  doc.setFont('TimesNewRoman', 'bold');
  doc.setFontSize(16);
  doc.text('Raport środków trwałych', 14, 18);
  doc.setFont('TimesNewRoman', 'normal');
  doc.setFontSize(12);
  let y = 28;
  if (company) {
    if (company.company_name) doc.text(`Firma: ${company.company_name}`, 14, y);
    if (company.company_tax_number) doc.text(`NIP: ${company.company_tax_number}`, 14, y += 8);
    let address = '';
    if (company.company_street) address += company.company_street;
    if (company.company_street_number) address += ' ' + company.company_street_number;
    if (address) doc.text(`Adres: ${address}`, 14, y += 8);
    let cityLine = '';
    if (company.company_zip_code) cityLine += company.company_zip_code + ' ';
    if (company.company_city) cityLine += company.company_city;
    if (cityLine) doc.text(cityLine, 14, y += 8);
    if (company.company_state) doc.text(`Województwo: ${company.company_state}`, 14, y += 8);
    if (company.company_country) doc.text(`Kraj: ${company.company_country}`, 14, y += 8);
    y += 8;
  }

  const assetRows = props.assets.map((asset: any) => [
    asset.asset_name,
    asset.kst_symbol,
    asset.current_value,
    asset.status,
    asset.user?.name || '',
    asset.status === 'zlikwidowany' ? asset.liquidation_date : asset.status === 'nabyty' ? asset.acquisition_date : asset.status === 'w użyciu' ? asset.commissioning_date : ''
  ]);
  autoTable(doc, {
    head: [[
      'Nazwa', 'KST', 'Wartość', 'Status', 'Użytkownik', 'Data'
    ]],
    body: assetRows,
    startY: y,
    styles: { font: 'TimesNewRoman', fontSize: 10 },
    headStyles: { fillColor: [55, 65, 81], font: 'TimesNewRoman', fontStyle: 'bold' },
    margin: { left: 14, right: 14 },
  });

  const watermark = `Utworzono przez: ${user.name || ''}`;
  doc.setFont('TimesNewRoman', 'normal');
  doc.setFontSize(10);
  doc.setTextColor(150);
  doc.text(watermark, 105, doc.internal.pageSize.getHeight() - 10, { align: 'center' });

  doc.save('raport_srodkow_trwalych.pdf');
  closeReportModal();
}

const sortKey = ref('');
const sortAsc = ref(true);

function setSort(key: string) {
  if (sortKey.value === key) {
    sortAsc.value = !sortAsc.value;
  } else {
    sortKey.value = key;
    sortAsc.value = true;
  }
}

const sortedAssets = computed(() => {
  if (!props.assets) return [];
  const arr = [...props.assets];
  if (!sortKey.value) return arr;
  return arr.sort((a: any, b: any) => {
    let valA, valB;
    if (sortKey.value === 'user') {
      valA = a.user?.name || '';
      valB = b.user?.name || '';
    } else if (sortKey.value === 'date') {
      const getDate = (asset: any) => {
        if (asset.status === 'zlikwidowany') return asset.liquidation_date || '';
        if (asset.status === 'nabyty') return asset.acquisition_date || '';
        if (asset.status === 'w użyciu') return asset.commissioning_date || '';
        return '';
      };
      valA = getDate(a);
      valB = getDate(b);
    } else {
      valA = a[sortKey.value];
      valB = b[sortKey.value];
    }
    if (valA == null) valA = '';
    if (valB == null) valB = '';
    if (typeof valA === 'number' && typeof valB === 'number') {
      return sortAsc.value ? valA - valB : valB - valA;
    }
    return sortAsc.value
      ? String(valA).localeCompare(String(valB), 'pl')
      : String(valB).localeCompare(String(valA), 'pl');
  });
});

function openModal(asset: any = undefined) {
  editAsset.value = asset;
  showModal.value = true;
}
function closeModal() {
  editAsset.value = undefined;
  showModal.value = false;
}
function saveAsset(asset: any) {
  const page: any = usePage();
  const userId = page.props.auth?.user?.id;
  const assetWithUser = { ...asset, user_id: userId };
  if (asset.id) {
    router.put(`/fixed-assets/${asset.id}`, assetWithUser, {
      onSuccess: closeModal,
    });
  } else {
    if (userId) {
      router.post('/fixed-assets', assetWithUser, {
        onSuccess: closeModal,
      });
    } else {
      closeModal();
    }
  }
}
function deleteAsset(id: number) {
  if (confirm('Delete this asset?')) {
    router.delete(`/fixed-assets/${id}`);
  }
}
</script>

<template>
  <div>
    <h2 class="text-xl font-bold mb-4 text-center">Środki trwałe</h2>
    <div class="flex justify-center gap-4 mb-2">
      <button class="px-4 py-2 rounded-xl bg-gray-700 text-white font-semibold shadow hover:bg-gray-800 transition-colors" @click="openModal()">
        Dodaj środek trwały
      </button>
      <button class="px-4 py-2 rounded-xl bg-gray-700 text-white font-semibold shadow hover:bg-gray-800 transition-colors" @click="openReportModal">
        Generuj raport
      </button>
    </div>
    <table class="table-auto w-full mb-4 border rounded-xl overflow-hidden shadow-sm bg-white dark:bg-gray-900">
      <thead class="bg-gray-100 dark:bg-gray-800">
        <tr>
          <th class="text-left px-4 py-2 cursor-pointer select-none" @click="setSort('asset_name')">
            Nazwa
            <span v-if="sortKey === 'asset_name'">{{ sortAsc ? '▲' : '▼' }}</span>
          </th>
          <th class="text-left px-4 py-2 cursor-pointer select-none" @click="setSort('kst_symbol')">
            KST
            <span v-if="sortKey === 'kst_symbol'">{{ sortAsc ? '▲' : '▼' }}</span>
          </th>
          <th class="text-left px-4 py-2 cursor-pointer select-none" @click="setSort('current_value')">
            Wartość
            <span v-if="sortKey === 'current_value'">{{ sortAsc ? '▲' : '▼' }}</span>
          </th>
          <th class="text-left px-4 py-2 cursor-pointer select-none" @click="setSort('status')">
            Status
            <span v-if="sortKey === 'status'">{{ sortAsc ? '▲' : '▼' }}</span>
          </th>
          <th class="text-left px-4 py-2 cursor-pointer select-none" @click="setSort('user')">
            Użytkownik
            <span v-if="sortKey === 'user'">{{ sortAsc ? '▲' : '▼' }}</span>
          </th>
          <th class="text-left px-4 py-2 cursor-pointer select-none" @click="setSort('date')">
            Data
            <span v-if="sortKey === 'date'">{{ sortAsc ? '▲' : '▼' }}</span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="asset in sortedAssets" :key="(asset as any).id" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" @click="openModal(asset)">
          <td class="px-4 py-2">{{ (asset as any).asset_name }}</td>
          <td class="px-4 py-2">{{ (asset as any).kst_symbol }}</td>
          <td class="px-4 py-2">{{ (asset as any).current_value }}</td>
          <td class="px-4 py-2">{{ (asset as any).status }}</td>
          <td class="px-4 py-2">{{ (asset as any).user?.name }}</td>
          <td class="px-4 py-2">
            <template v-if="(asset as any).status === 'zlikwidowany'">
              {{ (asset as any).liquidation_date }}
            </template>
            <template v-else-if="(asset as any).status === 'nabyty'">
              {{ (asset as any).acquisition_date }}
            </template>
            <template v-else-if="(asset as any).status === 'w użyciu'">
              {{ (asset as any).commissioning_date }}
            </template>
          </td>
        </tr>
      </tbody>
    </table>
    <AssetModal v-if="showModal" :asset="editAsset" @close="closeModal" @save="saveAsset" />
    <div v-if="showReportModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" @mousedown.self="closeReportModal">
      <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md relative border border-gray-200 dark:border-gray-700" @mousedown.stop>
        <h3 class="text-xl font-bold mb-4 text-center text-gray-700 dark:text-gray-100">Generuj raport PDF</h3>
        <p class="mb-6 text-center">Wygeneruj raport PDF ze wszystkimi środkami trwałymi.</p>
        <div class="flex justify-center gap-3">
          <button class="px-4 py-2 rounded-xl bg-gray-300 text-gray-700 font-semibold hover:bg-gray-400 transition-colors" @click="closeReportModal">Anuluj</button>
          <button class="px-4 py-2 rounded-xl bg-gray-700 text-white font-semibold shadow hover:bg-yellow-800 transition-colors" @click="generateReport">Generuj PDF</button>
        </div>
      </div>
    </div>
  </div>
</template>
