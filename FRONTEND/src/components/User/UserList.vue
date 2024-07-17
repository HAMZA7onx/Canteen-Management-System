<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-100 to-blue-200 dark:from-gray-900 dark:to-blue-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <!-- Descriptive Section -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 mb-8 transform hover:scale-105 transition-all duration-300">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-blue-600 mb-4">Centre de Gestion des Utilisateurs</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-4">
          Bienvenue dans le centre névralgique de la gestion des utilisateurs. Ici, vous pouvez orchestrer l'ensemble de votre base d'utilisateurs, de la création de profils individuels à l'importation en masse d'équipes. Renforcez votre organisation avec une administration rationalisée des utilisateurs.
        </p>
        <div class="flex items-center text-sm text-blue-600 dark:text-blue-400">
          <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
          Gérez les utilisateurs avec précision et facilité
        </div>
      </div>

      <!-- User Actions -->
      <div class="mb-6 flex flex-wrap justify-between items-center">
        <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4 mb-4">
        <button
          class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full shadow-lg transform hover:scale-105 transition-all duration-300 text-sm sm:text-base"
          @click="openCreateUserModal"
        >
          <span class="mr-2">+</span> Créer un Utilisateur
        </button>
        <button
          class="w-full sm:w-auto bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full shadow-lg transform hover:scale-105 transition-all duration-300 text-sm sm:text-base"
          @click="openImportUsersModal"
        >
          <span class="mr-2">↑</span> Importer des Utilisateurs
        </button>
      </div>

        <div class="text-gray-600 dark:text-gray-300">
          Total des Utilisateurs: <span class="font-bold text-blue-600 dark:text-blue-400">{{ users.length }}</span>
        </div>
      </div>

      <!-- User List -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto">
          <loading-wheel v-if="isLoading" />
          <div v-else-if="error" class="p-4 text-red-600 dark:text-red-400">
            {{ error }}
            <button @click="loadUsers" class="ml-2 underline">Réessayer</button>
          </div>
          <table v-else class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700 hidden md:table-header-group">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Téléphone</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">matriculation</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Catégorie</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Détails</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                <!-- Desktop view -->
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 hidden md:table-cell">{{ user.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ user.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ user.phone_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ user.matriculation_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ user.category.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium hidden md:table-cell">
                  <button
                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors duration-300"
                    @click="openDetailsPopup(user)"
                  >
                    Détails
                  </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium hidden md:table-cell">
                  <button
                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 mr-3 transition-colors duration-300"
                    @click="openEditUserModal(user)"
                  >
                    <font-awesome-icon icon="edit" />
                  </button>
                  <button
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-300"
                    @click="deleteUser(user)"
                  >
                    <font-awesome-icon icon="trash" />
                  </button>
                </td>

                <!-- Mobile view -->
                <td class="px-6 py-4 md:hidden">
                  <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ user.email }}</span>
                    <button
                      class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors duration-300"
                      @click="toggleMobileActions(user)"
                    >
                      <font-awesome-icon icon="ellipsis-v" />
                    </button>
                  </div>
                  <div v-if="user.showMobileActions" class="mt-2 space-y-2">
                    <button
                      class="w-full text-left text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors duration-300 flex items-center"
                      @click="openDetailsPopup(user)"
                    >
                      <font-awesome-icon icon="info-circle" class="mr-2" />
                      Détails
                    </button>
                    <button
                      class="w-full text-left text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 transition-colors duration-300 flex items-center"
                      @click="openEditUserModal(user)"
                    >
                      <font-awesome-icon icon="edit" class="mr-2" />
                      Modifier
                    </button>
                    <button
                      class="w-full text-left text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-300 flex items-center"
                      @click="deleteUser(user)"
                    >
                      <font-awesome-icon icon="fa-trash" class="mr-2" />
                      Supprimer
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modals -->
      <Overlay v-if="showCreateUserModal">
        <Modal :show="showCreateUserModal" @close="closeCreateUserModal" title="Créer un Nouvel Utilisateur">
          <UserForm :user="{}" @create:user="createUser" />

        </Modal>
      </Overlay>

      <Overlay v-if="showEditUserModal">
        <Modal :show="showEditUserModal" @close="closeEditUserModal" title="Modifier le Profil Utilisateur">
          <UserForm :user="selectedUser" @update:user="updateUser" />

        </Modal>
      </Overlay>

      <Overlay v-if="showImportUsersModal">
        <Modal :show="showImportUsersModal" @close="closeImportUsersModal" title="Importation en Masse d'Utilisateurs">
          <form @submit.prevent="importUsers" class="space-y-4">
            <!-- New section for downloading template -->
            <div class="mb-4">
              <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Télécharger le Modèle</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Téléchargez le modèle Excel, remplissez-le avec les données des utilisateurs, puis importez-le.</p>
              <button
                @click.prevent="downloadTemplate"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
              >
                Télécharger le Modèle Excel
              </button>
            </div>

            <div>
              <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sélectionner une Catégorie</label>
              <select
                id="category"
                v-model="importCategoryId"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              >
                <option value="">Sélectionner une Catégorie</option>
                <option v-for="category in userCategories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>
            <div>
              <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Choisir un Fichier Excel</label>
              <input
                type="file"
                id="file"
                ref="fileInput"
                @change="handleFileChange"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                accept=".xlsx,.xls"
              >
            </div>
            <div>
              <button
                type="submit"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-300"
              >
                Importer les Utilisateurs
              </button>
            </div>
            <div v-if="importResult" class="mt-4">
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ importResult.message }}</p>
              <div v-if="importResult.skipped_rows && importResult.skipped_rows.length > 0" class="mt-2">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Lignes ignorées :</p>
                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                  <li v-for="(skipped, index) in importResult.skipped_rows" :key="index">
                    {{ skipped.row.name }} ({{ skipped.row.email }}) - {{ skipped.reason }}
                  </li>
                </ul>
              </div>
            </div>
          </form>
        </Modal>
      </Overlay>

      <!-- Delete Confirmation Modal -->
      <div class="fixed z-10 inset-0 overflow-y-auto" v-if="showDeleteConfirmation">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
          </div>
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
          <div
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-headline"
          >
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                    Confirmer la Suppression de l'Utilisateur
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      Êtes-vous sûr de vouloir supprimer l'utilisateur "{{ userToDelete?.name }}" ? Cette action ne peut pas être annulée.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-700 dark:hover:bg-red-800 transition-colors duration-300"
                @click="confirmDeleteUser"
              >
                Supprimer
              </button>
              <button
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors duration-300"
                @click="showDeleteConfirmation = false"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- User Details Popup -->
      <div v-if="showDetailsPopup" class="fixed inset-0 z-50 flex items-center justify-center">
        <Overlay @click="closeDetailsPopup" />
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full z-50">
          <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                  Détails de l'Utilisateur
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Nom :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedUser.name }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Email :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedUser.email }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Numéro de Téléphone :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedUser.phone_number }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Genre :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedUser.gender }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Catégorie :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedUser.category.name }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Créateur :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedUser.creator }}</p>
                  </div>
                </div>
                <div class="mt-4">
                  <p class="text-gray-500 dark:text-gray-400"><strong>Éditeurs :</strong></p>
                  <ul v-if="parsedEditors.length > 0" class="list-disc list-inside text-gray-900 dark:text-gray-100">
                    <li v-for="editor in parsedEditors" :key="editor">{{ editor }}</li>
                  </ul>
                  <p v-else class="text-gray-900 dark:text-gray-100">Aucun éditeur</p>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Créé le :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ formatDate(selectedUser.created_at) }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Mis à jour le :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">
                      <span v-if="isUpdatedAtSameAsCreatedAt(selectedUser)">
                        {{ formatDate(selectedUser.updated_at) }} (Pas de mises à jour)
                      </span>
                      <span v-else>
                        {{ formatDate(selectedUser.updated_at) }}
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-300"
              @click="closeDetailsPopup"
            >
              Fermer
            </button>
          </div>
        </div>
      </div>
    </div>
    <Toast :show="showToast" :message="toastMessage" />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import UserForm from '@/components/User/UserForm.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import LoadingWheel from '@/components/shared/LoadingWheel.vue';
import * as XLSX from 'xlsx';
import FileSaver from 'file-saver';
import Toast from '@/components/shared/Toast.vue';

export default {
  name: 'UserList',
  components: {
    UserForm,
    Modal,
    Overlay,
    LoadingWheel,
    Toast,
  },
  data() {
    return {
      showCreateUserModal: false,
      showEditUserModal: false,
      showDeleteConfirmation: false,
      showImportUsersModal: false,
      showDetailsPopup: false,
      selectedUser: null,
      userToDelete: null,
      importCategoryId: '',
      importFile: null,
      importResult: null,
      isLoading: true,
      error: null,
    };
  },
  computed: {
    ...mapGetters('user', ['users']),
    ...mapGetters('userCategory', ['userCategories']),
    parsedEditors() {
      if (this.selectedUser && this.selectedUser.editors) {
        try {
          const editorsArray = JSON.parse(this.selectedUser.editors);
          return Array.isArray(editorsArray) ? editorsArray : [];
        } catch (error) {
          console.error('Error parsing editors:', error);
          return [];
        }
      }
      return [];
    }
  },
  created() {
    this.loadUsers();
    this.fetchUserCategories();
  },
  methods: {
    ...mapActions('user', ['fetchUsers', 'createUser', 'updateUser', 'deleteUser', 'importUsers']),
    ...mapActions('userCategory', ['fetchUserCategories']),
    
    async loadUsers() {
      this.isLoading = true;
      this.error = null;
      try {
        await this.fetchUsers();
      } catch (error) {
        console.error('Error fetching users:', error);
        this.error = 'Failed to load users. Please try again.';
      } finally {
        this.isLoading = false;
      }
    },
    openCreateUserModal() {
      this.showCreateUserModal = true;
    },
    closeCreateUserModal() {
      this.showCreateUserModal = false;
    },
    openEditUserModal(user) {
      this.selectedUser = { ...user };
      this.showEditUserModal = true;
    },
    closeEditUserModal() {
      this.showEditUserModal = false;
      this.selectedUser = null;
    },
    updateUser(updatedUser) {
      this.$store
        .dispatch('user/updateUser', updatedUser)
        .then(() => {
          this.closeEditUserModal();
          this.loadUsers(); // Refresh the user list after update
        })
        .catch((error) => {
          console.error('Error updating user:', error);
        });
    },
    deleteUser(user) {
      this.userToDelete = user;
      this.showDeleteConfirmation = true;
    },
    confirmDeleteUser() {
      this.$store
        .dispatch('user/deleteUser', this.userToDelete.id)
        .then(() => {
          this.showDeleteConfirmation = false;
          this.userToDelete = null;
          this.loadUsers(); // Refresh the user list after deletion
          this.showSuccessToast('User deleted successfully!');
        })
        .catch((error) => {
          console.error('Error deleting user:', error);
          this.showDeleteConfirmation = false;
          this.userToDelete = null;
        });
    },
    openImportUsersModal() {
      this.showImportUsersModal = true;
    },
    closeImportUsersModal() {
      this.showImportUsersModal = false;
      this.importCategoryId = '';
      this.importFile = null;
      this.importResult = null;
      if (this.$refs.fileInput) {
        this.$refs.fileInput.value = '';
      }
    },
    handleFileChange(event) {
      this.importFile = event.target.files[0];
    },
    importUsers() {
      if (!this.importFile || !this.importCategoryId) {
        alert('Please select a file and a category');
        return;
      }

      const formData = new FormData();
      formData.append('file', this.importFile);
      formData.append('category_id', this.importCategoryId);

      this.$store.dispatch('user/importUsers', formData)
        .then((response) => {
          this.importResult = response;
          this.loadUsers(); // Refresh the user list after import
        })
        .catch((error) => {
          console.error('Error importing users:', error);
          this.importResult = {
            message: 'An error occurred while importing users. Please try again.',
            skipped_rows: [],
          };
        });
    },
    openDetailsPopup(user) {
      this.selectedUser = { ...user };
      this.showDetailsPopup = true;
    },
    closeDetailsPopup() {
      this.showDetailsPopup = false;
      this.selectedUser = null;
    },
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
    isUpdatedAtSameAsCreatedAt(user) {
      return new Date(user.created_at).getTime() === new Date(user.updated_at).getTime();
    },
    downloadTemplate() {
      const headers = ['name', 'email', 'phone_number', 'age', 'gender'];
      const ws = XLSX.utils.aoa_to_sheet([headers]);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Users');
      const excelBuffer = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
      const data = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8' });
      FileSaver.saveAs(data, 'user_import_template.xlsx');
    },
    toggleMobileActions(user) {
      user.showMobileActions = !user.showMobileActions;
      this.$forceUpdate();
    },
    createUser(userData) {
      this.$store.dispatch('user/createUser', userData)
        .then(() => {
          this.closeCreateUserModal();
          this.loadUsers();
          this.showSuccessToast('User created successfully!');
        })
        .catch((error) => {
          console.error('Error creating user:', error);
        });
    },
    updateUser(updatedUser) {
      this.$store.dispatch('user/updateUser', updatedUser)
        .then(() => {
          this.closeEditUserModal();
          this.loadUsers();
          this.showSuccessToast('User updated successfully!');
        })
        .catch((error) => {
          console.error('Error updating user:', error);
        });
    },
    showSuccessToast(message) {
      this.toastMessage = message;
      this.showToast = true;
      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    },
  },
};
</script>