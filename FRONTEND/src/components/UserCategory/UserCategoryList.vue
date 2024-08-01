<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-gray-900 dark:to-indigo-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 mb-8 transform hover:scale-105 transition-all duration-300">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-600 mb-4">Centre de Gestion des Catégories d'Utilisateurs</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-4">
          Gérez et organisez efficacement vos catégories d'utilisateurs. Créez, modifiez et supprimez des catégories pour maintenir une base d'utilisateurs bien structurée.
        </p>
        <div class="flex items-center text-sm text-indigo-600 dark:text-indigo-400">
          <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
          Simplifiez votre processus de gestion des utilisateurs
        </div>
      </div>

      <div class="mb-6 space-y-4 sm:space-y-0 sm:flex sm:justify-between sm:items-center">

        <button
            v-if="$can('creer_categorie_de_collaborateur')"
            class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-500 to-pink-500 group-hover:from-purple-500 group-hover:to-pink-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800 w-full sm:w-auto"
            @click="openCreateModal"
          >
            <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
              Créer une Catégorie
            </span>
          </button>
        <div class="w-full sm:w-1/2 order-2 sm:order-1">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher une catégorie..."
              class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>


      <!-- Categories Table/List -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto">
          <loading-wheel v-if="isLoading" />
          <table v-show="!isLoading" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 hidden md:table">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dernière Mise à Jour</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Détails</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="category in paginatedCategories" :key="category.id" class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ category.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ category.description }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ formatDate(category.updated_at) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition-colors duration-300"
                    @click="openDetailsPopup(category)"
                  >
                    Voir les Détails
                  </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    v-if="$can('modifier_categorie_de_collaborateur')"
                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 mr-3 transition-colors duration-300"
                    @click="openEditModal(category)"
                  >
                    <font-awesome-icon icon="edit" />
                  </button>
                  <button
                    v-if="$can('supprimer_categorie_de_collaborateur')"
                    :class="[
                      'transition-colors duration-300',
                      category.is_assigned ? 'text-gray-400 cursor-not-allowed' : 'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200'
                    ]"
                    @click="deleteCategory(category)"
                    :disabled="category.is_assigned"
                  >
                    <font-awesome-icon icon="trash" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <ul v-show="!isLoading" class="divide-y divide-gray-200 dark:divide-gray-700 md:hidden">
            <li v-for="category in paginatedCategories" :key="category.id" class="py-4 px-4">
              <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ category.name }}</span>
                <button
                  class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200"
                  @click="toggleCategoryActions(category)"
                >
                  <font-awesome-icon icon="ellipsis-v" />
                </button>
              </div>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ category.description }}</p>
              <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Dernière mise à jour: {{ formatDate(category.updated_at) }}</p>
              <div v-if="category.showActions" class="mt-2 space-y-2">
                <button
                  class="w-full text-left text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition-colors duration-300 flex items-center"
                  @click="openDetailsPopup(category)"
                >
                  <font-awesome-icon icon="info-circle" class="mr-2" />
                  Voir les Détails
                </button>
                <button
                  v-if="$can('modifier_categorie_de_collaborateur')"
                  class="w-full text-left text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 transition-colors duration-300 flex items-center"
                  @click="openEditModal(category)"
                >
                  <font-awesome-icon icon="edit" class="mr-2" />
                  Modifier
                </button>
                <button
                  v-if="$can('supprimer_categorie_de_collaborateur')"
                  class="w-full text-left transition-colors duration-300 flex items-center"
                  :class="[
                    category.is_assigned ? 'text-gray-400 cursor-not-allowed' : 'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200'
                  ]"
                  @click="deleteCategory(category)"
                  :disabled="category.is_assigned"
                >
                  <font-awesome-icon icon="trash" class="mr-2" />
                  Supprimer
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex justify-between items-center">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50"
        >
          Précédent
        </button>
        <span class="dark:text-gray-100 ">Page {{ currentPage }} sur {{ totalPages }}</span>
        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50"
        >
          Suivant
        </button>
      </div>

      <!-- Create/Edit Modal -->
      <Overlay v-if="showModal">
        <div class="modal-container" @click.stop>
          <Modal :show="showModal" :title="isEditMode ? 'Modifier la Catégorie d\'Utilisateur' : 'Créer une Catégorie d\'Utilisateur'" @close="closeModal">
            <user-category-form
              :category="selectedCategory"
              @submit="handleSubmit"
            />
          </Modal>
        </div>
      </Overlay>

      <!-- Delete Confirmation Modal -->
      <Overlay v-if="showDeleteConfirmation">
        <div class="rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-auto my-auto fixed inset-0 flex items-center justify-center" @click.stop> 
          <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                    Supprimer la Catégorie d'Utilisateur
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      Êtes-vous sûr de vouloir supprimer {{ categoryToDelete?.name }} ? Cette action ne peut pas être annulée.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-700 dark:hover:bg-red-800 transition-colors duration-300"
                @click="confirmDelete"
              >
                Supprimer
              </button>
              <button
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors duration-300"
                @click="closeDeleteConfirmation"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </Overlay>

      <!-- Details Popup -->
      <Overlay v-if="showDetailsPopup">
        <div class="modal-container" @click.stop>
          <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                  <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Détails de la Catégorie
                  </h3>
                  <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Nom :</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">{{ selectedCategory.name }}</p>
                    </div>
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Description :</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">{{ selectedCategory.description }}</p>
                    </div>
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Créateur :</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">{{ selectedCategory.creator }}</p>
                    </div>
                  </div>
                  <div class="mt-4">
                    <p class="text-gray-500 dark:text-gray-400"><strong>Éditeurs :</strong></p>
                    <ul v-if="selectedCategory.editors && selectedCategory.editors.length > 0" class="list-disc list-inside text-gray-900 dark:text-gray-100">
                      <li v-for="editor in selectedCategory.editors" :key="editor">{{ editor }}</li>
                    </ul>
                    <p v-else class="text-gray-900 dark:text-gray-100">Aucun éditeur</p>
                  </div>
                  <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Créé le :</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">{{ formatDate(selectedCategory.created_at) }}</p>
                    </div>
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Mis à jour le :</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">
                        <span v-if="isUpdatedAtSameAsCreatedAt(selectedCategory)">
                          {{ formatDate(selectedCategory.updated_at) }} (Pas de mises à jour)
                        </span>
                        <span v-else>
                          {{ formatDate(selectedCategory.updated_at) }}
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
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-colors duration-300"
                @click="closeDetailsPopup"
              >
                Fermer
              </button>
            </div>
          </div>
        </div>
      </Overlay>
    </div>
    <Toast :show="showToast" :message="toastMessage" />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import permissionMixin from '@/mixins/permissionMixin';
import UserCategoryForm from '@/components/UserCategory/UserCategoryForm.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import LoadingWheel from '@/components/shared/LoadingWheel.vue';
import Toast from '@/components/shared/Toast.vue';

export default {
  mixins: [permissionMixin],
  components: {
    UserCategoryForm,
    Modal,
    Overlay,
    LoadingWheel,
    Toast,
  },
  data() {
    return {
      showModal: false,
      showDeleteConfirmation: false,
      showDetailsPopup: false,
      selectedCategory: null,
      categoryToDelete: null,
      isEditMode: false,
      isLoading: true,
      showToast: false,
      toastMessage: '',
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
    };
  },
  computed: {
    ...mapGetters('userCategory', ['userCategories']),
    filteredCategories() {
      return this.userCategories.filter(category =>
        category.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        (category.description && category.description.toLowerCase().includes(this.searchQuery.toLowerCase()))
      );
    },

    totalPages() {
      return Math.ceil(this.filteredCategories.length / this.itemsPerPage);
    },
    paginatedCategories() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredCategories.slice(start, end);
    },
  },
  watch: {
    searchQuery() {
      this.currentPage = 1;
    },
  },
  created() {
    this.loadUserCategories();
  },
  methods: {
    ...mapActions('userCategory', [
      'fetchUserCategories',
      'createUserCategory',
      'updateUserCategory',
      'deleteUserCategory',
    ]),
    async loadUserCategories() {
      this.isLoading = true;
      try {
        await this.fetchUserCategories();
      } catch (error) {
        console.error('Error fetching user categories:', error);
      } finally {
        this.isLoading = false;
      }
    },
    openCreateModal() {
      if (this.$can('creer_categorie_de_collaborateur')) {
        this.selectedCategory = {};
        this.isEditMode = false;
        this.showModal = true;
      }
    },
    openEditModal(category) {
      if (this.$can('modifier_categorie_de_collaborateur')) {
        this.selectedCategory = { ...category };
        this.isEditMode = true;
        this.showModal = true;
      }
    },
    closeModal() {
      this.showModal = false;
      this.selectedCategory = null;
      this.isEditMode = false;
    },
    showSuccessToast(message) {
      this.toastMessage = message;
      this.showToast = true;
      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    },
    handleSubmit(category) {
      const action = this.isEditMode ? this.updateUserCategory : this.createUserCategory;
      action(category)
        .then(() => {
          this.closeModal();
          this.loadUserCategories();
          this.showSuccessToast('Category handled successfully!');
        })
        .catch((error) => {
          console.error(`Error ${this.isEditMode ? 'updating' : 'creating'} user category:`, error);
        });
    },
    deleteCategory(category) {
      if (this.$can('supprimer_categorie_de_collaborateur') && !category.is_assigned) {
        this.categoryToDelete = category;
        this.showDeleteConfirmation = true;
      }
    },
    closeDeleteConfirmation() {
      this.showDeleteConfirmation = false;
      this.categoryToDelete = null;
    },
    confirmDelete() {
      this.deleteUserCategory(this.categoryToDelete.id)
        .then(() => {
          this.closeDeleteConfirmation();
          this.loadUserCategories();
          this.showSuccessToast('Category deleted successfully!');
        })
        .catch((error) => {
          console.error('Error deleting user category:', error);
          this.closeDeleteConfirmation();
        });
    },
    openDetailsPopup(category) {
      this.selectedCategory = { ...category };
      this.showDetailsPopup = true;
    },
    closeDetailsPopup() {
      this.showDetailsPopup = false;
      this.selectedCategory = null;
    },
    formatDate(date) {
      return new Date(date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    isUpdatedAtSameAsCreatedAt(category) {
      return new Date(category.created_at).getTime() === new Date(category.updated_at).getTime();
    },
    toggleCategoryActions(category) {
      category.showActions = !category.showActions;
      this.$forceUpdate();
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
  },
};
</script>
