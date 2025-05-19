<script setup lang="ts">
import { ref } from 'vue';
import { usePage, router, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface UserTableRow {
  id: number;
  name: string;
  email: string;
  is_admin: boolean;
}

const page = usePage();
const users = ref<UserTableRow[]>(Array.isArray(page.props.users) ? page.props.users : []);

const breadcrumbs: BreadcrumbItemType[] = [
  {
    title: 'Użytkownicy',
    href: '/users',
  },
];

const showAddUserModal = ref(false);
const newUser = ref({ name: '', email: '', password: '', is_admin: false });
const addUserError = ref('');

function openAddUserModal() {
  newUser.value = { name: '', email: '', password: '', is_admin: false };
  addUserError.value = '';
  showAddUserModal.value = true;
}
function closeAddUserModal() {
  showAddUserModal.value = false;
}
async function addUser() {
  addUserError.value = '';
  if (!newUser.value.name || !newUser.value.email || !newUser.value.password) {
    addUserError.value = 'Wszystkie pola są wymagane';
    return;
  }
  try {
    await router.post('/users', newUser.value, {
      onSuccess: () => {
        closeAddUserModal();
      },
      onError: (errors) => {
        addUserError.value = errors?.email || 'Błąd dodawania użytkownika';
      },
      preserveScroll: true,
    });
  } catch (e) {
    addUserError.value = 'Błąd dodawania użytkownika';
  }
}

function deleteUser(id: number) {
  if (confirm('Czy na pewno chcesz usunąć tego użytkownika?')) {
    router.delete(`/users/${id}`);
  }
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Użytkownicy" />
    <div class="max-w-4xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6 text-center">Użytkownicy</h1>
      <div class="flex justify-end mb-4">
        <button class="px-4 py-2 rounded-xl bg-gray-700 justify-center text-white font-semibold shadow hover:bg-gray-800 transition-colors" @click="openAddUserModal">
          Dodaj użytkownika
        </button>
      </div>
      <table class="table-auto w-full mb-4 border rounded-xl overflow-hidden shadow-sm bg-white dark:bg-gray-900">
        <thead class="bg-gray-100 dark:bg-gray-800">
          <tr>
            <th class="px-4 py-2 text-left">ID</th>
            <th class="px-4 py-2 text-left">Imię i nazwisko</th>
            <th class="px-4 py-2 text-left">Email</th>
            <th class="px-4 py-2 text-left">Rola</th>
            <th class="px-4 py-2 text-left">Akcje</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <td class="px-4 py-2">{{ user.id }}</td>
            <td class="px-4 py-2">{{ user.name }}</td>
            <td class="px-4 py-2">{{ user.email }}</td>
            <td class="px-4 py-2">{{ user.is_admin ? 'Administrator' : 'Użytkownik' }}</td>
            <td class="px-4 py-2">
              <button class="px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600" @click.stop="deleteUser(user.id)">Usuń</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="showAddUserModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md relative border border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold mb-4 text-center">Dodaj użytkownika</h2>
          <div class="mb-3">
            <label class="block mb-1 font-medium">Login</label>
            <input v-model="newUser.name" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 rounded-xl py-2 px-3" />
          </div>
          <div class="mb-3">
            <label class="block mb-1 font-medium">Email</label>
            <input v-model="newUser.email" type="email" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 rounded-xl py-2 px-3" />
          </div>
          <div class="mb-3">
            <label class="block mb-1 font-medium">Hasło</label>
            <input v-model="newUser.password" type="password" class="input input-bordered w-full bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-700 rounded-xl py-2 px-3" />
          </div>
          <div class="mb-4 flex items-center gap-2">
            <input v-model="newUser.is_admin" type="checkbox" id="is_admin" />
            <label for="is_admin">Administrator</label>
          </div>
          <div v-if="addUserError" class="text-red-500 mb-2 text-center">{{ addUserError }}</div>
          <div class="flex justify-end gap-3">
            <button class="px-4 py-2 rounded bg-gray-300 text-gray-700 hover:bg-gray-400 transition-colors" @click="closeAddUserModal">Anuluj</button>
            <button class="px-4 py-2 rounded bg-gray-700 text-white font-semibold shadow hover:bg-gray-800 transition-colors" @click="addUser">Dodaj</button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
