<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-100 to-blue-200 dark:from-gray-900 dark:to-blue-900 py-6 sm:py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <!-- Section descriptive -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 sm:p-6 mb-6 sm:mb-8 transform hover:scale-105 transition-all duration-300">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-blue-600 mb-3 sm:mb-4">Centre de Gestion des Appareils POS</h1>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mb-3 sm:mb-4">
          Bienvenue dans le centre névralgique de la gestion des appareils POS. Ici, vous pouvez orchestrer l'ensemble de votre réseau d'appareils POS, de l'ajout d'appareils individuels à la gestion de leurs statuts. Donnez à votre organisation les moyens d'une administration rationalisée des appareils POS.
        </p>
        <div class="flex items-center text-xs sm:text-sm text-blue-600 dark:text-blue-400">
          <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
          </svg>
          Gérez les appareils POS avec précision et facilité
        </div>
      </div>

      <!-- Actions pour les appareils POS -->
      <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row justify-between items-center">
        <div class="w-full sm:w-auto mb-4 sm:mb-0">
          <button
            v-if="$can('creer_POS')"
            class="relative inline-flex items-center justify-center p-0.5 mb-4 sm:mb-0 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800 w-full sm:w-auto"
            @click="openAddPosDeviceModal"
          >
            <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 flex items-center">
              <span class="mr-2">+</span> Ajouter un Appareil POS
            </span>
          </button>
        </div>
        <div class="text-sm sm:text-base text-gray-600 dark:text-gray-300">
          Total des Appareils POS : <span class="font-bold text-blue-600 dark:text-blue-400">{{ posDevices.length }}</span>
        </div>
      </div>

      <!-- Liste des Appareils POS -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transition-colors duration-300">
    <loading-wheel v-if="isLoading" />
    <div v-else>
      <div v-if="error" class="p-4 text-red-600 dark:text-red-400 text-sm sm:text-base">
        {{ error }}
        <button @click="loadPosDevices" class="ml-2 underline">Réessayer</button>
      </div>
      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
              <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Adresse IP</th>
              <th scope="col" class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
              <th scope="col" class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Imprimante</th>
              <th scope="col" class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              <th scope="col" class="sm:hidden px-4 py-3"></th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <template v-for="device in posDevices" :key="device.id">
              <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100">{{ device.name }}</td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100">{{ device.ip_address }}</td>
                <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                  <span :class="{'text-green-600 dark:text-green-400': device.status === 'allowed', 'text-red-600 dark:text-red-400': device.status === 'unauthorized'}">
                    {{ device.status === 'allowed' ? 'Autorisé' : 'Non autorisé' }}
                  </span>
                </td>
                <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                  <span :class="{'text-green-600 dark:text-green-400': device.printer === 'active', 'text-red-600 dark:text-red-400': device.printer === 'inactive'}">
                    {{ device.printer === 'active' ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                  <button
                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 mr-3 transition-colors duration-300"
                    @click="openDetailsPopup(device)"
                  >
                    <font-awesome-icon icon="info-circle" />
                  </button>
                  <button
                    v-if="$can('modifier_POS')"
                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 mr-3 transition-colors duration-300"
                    @click="openEditPosDeviceModal(device)"
                  >
                    <font-awesome-icon icon="edit" />
                  </button>
                  <button
                    v-if="$can('supprimer_POS')"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-300"
                    @click="deletePosDevice(device)"
                  >
                    <font-awesome-icon icon="trash" />
                  </button>
                </td>
                <td class="sm:hidden px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button @click="toggleDeviceActions(device)" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <font-awesome-icon icon="ellipsis-v" />
                  </button>
                </td>
              </tr>
              <div v-if="device.showActions" class="sm:hidden bg-gray-50 dark:bg-gray-700">
                <td colspan="4" class="px-4 py-4">
                  <div class="flex flex-col justify-around">
                    <button
                      @click="openDetailsPopup(device)"
                      class="flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors duration-300"
                    >
                      <font-awesome-icon icon="info-circle" class="mr-2" />
                      Détails
                    </button>
                    <button
                      v-if="$can('modifier_POS')"
                      @click="openEditPosDeviceModal(device)"
                      class="flex items-center text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 transition-colors duration-300"
                    >
                      <font-awesome-icon icon="edit" class="mr-2" />
                      Modifier
                    </button>
                    <button
                      v-if="$can('supprimer_POS')"
                      @click="deletePosDevice(device)"
                      class="flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-300"
                    >
                      <font-awesome-icon icon="trash" class="mr-2" />
                      Supprimer
                    </button>
                  </div>
                </td>
              </div>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>

      <!-- Modals -->
      <Overlay v-if="showAddPosDeviceModal">
        <Modal :show="showAddPosDeviceModal" @close="closeAddPosDeviceModal" title="Ajouter un Nouvel Appareil POS">
          <PosDeviceForm :device="{}" @update:device="createPosDevice" />
        </Modal>
      </Overlay>

      <Overlay v-if="showEditPosDeviceModal">
        <Modal :show="showEditPosDeviceModal" @close="closeEditPosDeviceModal" title="Modifier l'Appareil POS">
          <PosDeviceForm :device="selectedDevice" @update:device="updatePosDevice" />
        </Modal>
      </Overlay>

      <!-- Modal de Confirmation de Suppression -->
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
                    Confirmer la Suppression de l'Appareil POS
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      Êtes-vous sûr de vouloir supprimer l'appareil POS avec l'IP "{{ deviceToDelete?.ip_address }}" ? Cette action ne peut pas être annulée.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-700 dark:hover:bg-red-800 transition-colors duration-300"
                @click="confirmDeletePosDevice"
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

      <!-- Popup de Détails de l'Appareil POS -->
      <div v-if="showDetailsPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <Overlay @click="closeDetailsPopup" />
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full z-50">
          <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                  Détails de l'Appareil POS
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Nom:</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedDevice.name }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Adresse IP :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedDevice.ip_address }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Statut :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedDevice.status === 'allowed' ? 'Autorisé' : 'Non autorisé' }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Printer Status:</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedDevice.printer === 'active' ? 'Active' : 'Inactive' }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Créateur :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ selectedDevice.creator }}</p>
                  </div>
                </div>
                <div class="mt-4">
                  <p class="text-gray-500 dark:text-gray-400"><strong>Éditeurs :</strong></p>
                  <ul v-if="parsedEditors.length > 0" class="list-disc list-inside text-gray-900 dark:text-gray-100">
                    <li v-for="editor in parsedEditors" :key="editor">{{ editor }}</li>
                  </ul>
                  <p v-else class="text-gray-900 dark:text-gray-100">Aucun éditeur</p>
                </div>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Créé le :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">{{ formatDate(selectedDevice.created_at) }}</p>
                  </div>
                  <div>
                    <p class="text-gray-500 dark:text-gray-400"><strong>Mis à jour le :</strong></p>
                    <p class="text-gray-900 dark:text-gray-100">
                      <span v-if="isUpdatedAtSameAsCreatedAt(selectedDevice)">
                        {{ formatDate(selectedDevice.updated_at) }} (Pas de mises à jour)
                      </span>
                      <span v-else>
                        {{ formatDate(selectedDevice.updated_at) }}
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
import PosDeviceForm from '@/components/POS/PosDeviceForm.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import LoadingWheel from '@/components/shared/LoadingWheel.vue';
import Toast from '@/components/shared/Toast.vue';
import permissionMixin from '@/mixins/permissionMixin';

export default {
  mixins: [permissionMixin],
  name: 'PosDeviceList',
  components: {
    PosDeviceForm,
    Modal,
    Overlay,
    LoadingWheel,
    Toast,
  },
  data() {
    return {
      showAddPosDeviceModal: false,
      showEditPosDeviceModal: false,
      showDeleteConfirmation: false,
      showDetailsPopup: false,
      selectedDevice: null,
      deviceToDelete: null,
      isLoading: true,
      error: null,
      showToast: false,
      toastMessage: '',
    };
  },
  computed: {
    ...mapGetters('posDevice', ['posDevices']),
    parsedEditors() {
      if (this.selectedDevice && this.selectedDevice.editors) {
        return Array.isArray(this.selectedDevice.editors) ? this.selectedDevice.editors : [];
      }
      return [];
    }
  },
  created() {
    this.loadPosDevices();
  },
  methods: {
    ...mapActions('posDevice', ['fetchPosDevices', 'createPosDevice', 'updatePosDevice', 'deletePosDevice']),
    toggleDeviceActions(device) {
      device.showActions = !device.showActions;
      this.$forceUpdate();
    },
    async loadPosDevices() {
      this.isLoading = true;
      this.error = null;
      try {
        await this.fetchPosDevices();
      } catch (error) {
        console.error('Error fetching POS devices:', error);
        this.error = 'Failed to load POS devices. Please try again.';
      } finally {
        this.isLoading = false;
      }
    },
    openAddPosDeviceModal() {
      this.showAddPosDeviceModal = true;
    },
    closeAddPosDeviceModal() {
      this.showAddPosDeviceModal = false;
    },
    openEditPosDeviceModal(device) {
      this.selectedDevice = { ...device };
      this.showEditPosDeviceModal = true;
    },
    closeEditPosDeviceModal() {
      this.showEditPosDeviceModal = false;
      this.selectedDevice = null;
    },
    createPosDevice(device) {
      this.$store.dispatch('posDevice/createPosDevice', device)
        .then(() => {
          this.closeAddPosDeviceModal();
          this.loadPosDevices();
          this.showSuccessToast('Device created successfully!');
        })
        .catch((error) => {
          console.error('Error creating POS device:', error);
        });
    },
    updatePosDevice(updatedDevice) {
      const deviceToUpdate = {
        ...updatedDevice,
        id: this.selectedDevice.id
      };

      this.$store.dispatch('posDevice/updatePosDevice', deviceToUpdate)
        .then(() => {
          this.closeEditPosDeviceModal();
          this.loadPosDevices();
          this.showSuccessToast('Device updated successfully!');
        })
        .catch((error) => {
          console.error('Error updating POS device:', error);
        });
    },
    deletePosDevice(device) {
      this.deviceToDelete = device;
      this.showDeleteConfirmation = true;
    },
    confirmDeletePosDevice() {
      this.$store.dispatch('posDevice/deletePosDevice', this.deviceToDelete.id)
        .then(() => {
          this.showDeleteConfirmation = false;
          this.deviceToDelete = null;
          this.loadPosDevices();
          this.showSuccessToast('Device created successfully!');
        })
        .catch((error) => {
          console.error('Error deleting POS device:', error);
          this.showDeleteConfirmation = false;
          this.deviceToDelete = null;
        });
    },
    openDetailsPopup(device) {
      this.selectedDevice = { ...device };
      this.showDetailsPopup = true;
    },
    closeDetailsPopup() {
      this.showDetailsPopup = false;
      this.selectedDevice = null;
    },
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
    isUpdatedAtSameAsCreatedAt(device) {
      return new Date(device.created_at).getTime() === new Date(device.updated_at).getTime();
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
