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

      <!-- Search and Navigation -->
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
        <div class="w-full sm:w-1/2 relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Rechercher un utilisateur..."
            class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          />
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
        <div class="flex space-x-2">
          <button
            @click="currentPage = 'list'"
            :class="['px-4 py-2 rounded-md transition-colors duration-300', currentPage === 'list' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300']"
          >
            Liste
          </button>
          <button
            @click="currentPage = 'grid'"
            :class="['px-4 py-2 rounded-md transition-colors duration-300', currentPage === 'grid' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300']"
          >
            Grille
          </button>
        </div>
      </div>

      <!-- User Actions -->
      <div class="">
        <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4 mb-4">
          <button
            v-if="$can('creer_collaborateurs')"
            class="relative inline-flex items-center justify-center p-0.5 mb-4 sm:mb-0 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 w-full sm:w-auto"
            @click="openCreateUserModal"
          >
            <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
              Créer un Utilisateur
            </span>
          </button>

          <button
            v-if="$can('importer_collaborateurs')"
            class="relative inline-flex items-center justify-center p-0.5 mb-4 sm:mb-0 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800 w-full sm:w-auto"
            @click="openImportUsersModal"
          >
            <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
              <span class="mr-2">↑</span> Importer des Utilisateurs
            </span>
          </button>

      </div>
      <div class="text-gray-600 dark:text-gray-300">
        Total des Utilisateurs: <span class="font-bold text-blue-600 dark:text-blue-400">{{ sortedUsers.length }}</span>
      </div>
      </div>

      <!-- User List -->
      <div v-if="currentPage === 'list'" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto">
          <loading-wheel v-if="isLoading" />
          <div v-else-if="error" class="p-4 text-red-600 dark:text-red-400">
            {{ error }}
            <button @click="loadUsers" class="ml-2 underline">Réessayer</button>
          </div>
          <div v-else-if="paginatedUsers.length === 0" class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucun utilisateur</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par créer un nouvel utilisateur.</p>
            <div class="mt-6">
              <button
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                @click="openCreateUserModal"
              >
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Créer un utilisateur
              </button>
            </div>
          </div>
          <table v-else class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700 hidden md:table-header-group">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Téléphone</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">matriculation</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Catégorie</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dernière Mise à Jour</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Détails</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="user in paginatedUsers" :key="user.id + '_' + currentPageIndex">
                <!-- Desktop view -->
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 hidden md:table-cell">{{ user.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ user.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ user.phone_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ user.matriculation_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ user.category.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">
                  {{ formatDate(Math.max(new Date(user.created_at), new Date(user.updated_at))) }}
                </td>

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
                    v-if="$can('modifier_collaborateurs')"
                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 mr-3 transition-colors duration-300"
                    @click="openEditUserModal(user)"
                  >
                    <font-awesome-icon icon="edit" />
                  </button>
                  <button
                    v-if="$can('supprimer_collaborateurs')"
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
                    <p class="text-sm text-gray-500 dark:text-gray-400">Dernière mise à jour: {{ formatDate(user.updated_at) }}</p>
                    <button
                      class="w-full text-left text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors duration-300 flex items-center"
                      @click="openDetailsPopup(user)"
                    >
                      <font-awesome-icon icon="info-circle" class="mr-2" />
                      Détails
                    </button>
                    <button
                    v-if="$can('modifier_collaborateurs')"
                      class="w-full text-left text-yellow-600 hover:text-yellow-900                       dark:text-yellow-400 dark:hover:text-yellow-200 transition-colors duration-300 flex items-center"
                      @click="openEditUserModal(user)"
                    >
                      <font-awesome-icon icon="edit" class="mr-2" />
                      Modifier
                    </button>
                    <button
                    v-if="$can('supprimer_collaborateurs')"
                      class="w-full text-left text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-300 flex items-center"
                      @click="deleteUser(user)"
                    >
                      <font-awesome-icon icon="trash" class="mr-2" />
                      Supprimer
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button @click="prevPage" :disabled="currentPageIndex === 0" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
              Previous
            </button>
            <button @click="nextPage" :disabled="currentPageIndex === totalPages - 1" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
              Next
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                Showing
                <span class="font-medium">{{ paginationStart + 1 }}</span>
                to
                <span class="font-medium">{{ paginationEnd }}</span>
                of
                <span class="font-medium">{{ sortedUsers.length }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button @click="prevPage" :disabled="currentPageIndex === 0" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  <span class="sr-only">Previous</span>
                  <!-- Heroicon name: solid/chevron-left -->
                  <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
                <button v-for="page in visiblePageNumbers" :key="page" @click="goToPage(page - 1)" :class="['relative inline-flex items-center px-4 py-2 border text-sm font-medium', currentPageIndex === page - 1 ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 dark:bg-indigo-900 dark:border-indigo-500 dark:text-indigo-200' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600']">
                  {{ page }}
                </button>
                <button @click="nextPage" :disabled="currentPageIndex === totalPages - 1" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  <span class="sr-only">Next</span>
                  <!-- Heroicon name: solid/chevron-right -->
                  <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <!-- User Grid -->
      <div v-else-if="currentPage === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div v-for="user in paginatedUsers" :key="user.id" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 transition-colors duration-300">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ user.name }}</h3>
          <p class="text-sm text-gray-600 dark:text-gray-400">{{ user.email }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">{{ user.phone_number }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">{{ user.category.name }}</p>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Dernière mise à jour: {{ formatDate(user.updated_at) }}</p>
          <div class="mt-4 flex justify-between">
            <button
              @click="openDetailsPopup(user)"
              class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
            >
              Détails
            </button>
            <button
              @click="openEditUserModal(user)"
              class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-200"
            >
              <font-awesome-icon icon="edit" />
            </button>
            <button
              @click="deleteUser(user)"
              class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200"
            >
              <font-awesome-icon icon="trash" />
            </button>
          </div>
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
          class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-300"
          :disabled="isImporting"
        >
          <svg v-if="isImporting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ isImporting ? 'Importation en cours...' : 'Importer les Utilisateurs' }}
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
import permissionMixin from '@/mixins/permissionMixin';
import UserForm from '@/components/User/UserForm.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import LoadingWheel from '@/components/shared/LoadingWheel.vue';
import * as XLSX from 'xlsx';
import FileSaver from 'file-saver';
import Toast from '@/components/shared/Toast.vue';

export default {
  name: 'UserList',
  mixins: [permissionMixin],
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
      isImporting: false,
      isLoading: true,
      error: null,
      searchQuery: '',
      currentPage: 'list',
      currentPageIndex: 0,
      itemsPerPage: 10,
      showToast: false,
      toastMessage: '',
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
    },
    filteredUsers() {
      return this.users.filter(user =>
        user.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        user.email.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        user.phone_number.includes(this.searchQuery) ||
        user.category.name.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    sortedUsers() {
      return [...this.filteredUsers].sort((a, b) => {
        const dateA = new Date(Math.max(new Date(a.created_at), new Date(a.updated_at)));
        const dateB = new Date(Math.max(new Date(b.created_at), new Date(b.updated_at)));
        return dateB - dateA;
      });
    },
    paginatedUsers() {
      const start = this.currentPageIndex * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredUsers.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.sortedUsers.length / this.itemsPerPage);
    },
    visiblePageNumbers() {
      const pageNumbers = [];
      const totalPages = this.totalPages;
      const currentPage = this.currentPageIndex + 1;

      if (totalPages <= 7) {
        for (let i = 1; i <= totalPages; i++) {
          pageNumbers.push(i);
        }
      } else {
        if (currentPage <= 4) {
          for (let i = 1; i <= 5; i++) {
            pageNumbers.push(i);
          }
          pageNumbers.push('...');
          pageNumbers.push(totalPages);
        } else if (currentPage >= totalPages - 3) {
          pageNumbers.push(1);
          pageNumbers.push('...');
          for (let i = totalPages - 4; i <= totalPages; i++) {
            pageNumbers.push(i);
          }
        } else {
          pageNumbers.push(1);
          pageNumbers.push('...');
          for (let i = currentPage - 1; i <= currentPage + 1; i++) {
            pageNumbers.push(i);
          }
          pageNumbers.push('...');
          pageNumbers.push(totalPages);
        }
      }

      return pageNumbers;
    },
    paginationStart() {
      return this.currentPageIndex * this.itemsPerPage;
    },
    paginationEnd() {
      const end = (this.currentPageIndex + 1) * this.itemsPerPage;
      return end > this.sortedUsers.length ? this.sortedUsers.length : end;
    },
  },
  created() {
    console.log('Component created');
    this.loadUsers();
    this.fetchUserCategories();
  },
  methods: {
    ...mapActions('user', ['fetchUsers', 'createUser', 'updateUser', 'deleteUser', 'importUsers']),
    ...mapActions('userCategory', ['fetchUserCategories']),
    
    async loadUsers() {
    if (this.$can('voir_collaborateurs')) {
      this.isLoading = true;
      this.error = null;
      try {
        await this.fetchUsers();
      } catch (error) {
        this.error = 'Failed to load users. Please try again.';
      } finally {
        this.isLoading = false;
      }
    } else {
      this.error = 'You do not have permission to view users.';
    }
  },

    openCreateUserModal() {
      if (this.$can('creer_collaborateurs')) {
        this.showCreateUserModal = true;
      }
    },
    closeCreateUserModal() {
      this.showCreateUserModal = false;
    },
    openEditUserModal(user) {
      if (this.$can('modifier_collaborateurs')) {
        this.selectedUser = { ...user };
        this.showEditUserModal = true;
      }
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
          this.loadUsers();
          this.currentPageIndex = 0;
          this.showSuccessToast('User updated successfully!');
        })
        .catch((error) => {
          console.error('Error updating user:', error);
        });
    },
    deleteUser(user) {
      if (this.$can('supprimer_collaborateurs')) {
        this.userToDelete = user;
        this.showDeleteConfirmation = true;
      }
    },

    confirmDeleteUser() {
      this.$store
        .dispatch('user/deleteUser', this.userToDelete.id)
        .then(() => {
          this.showDeleteConfirmation = false;
          this.userToDelete = null;
          this.loadUsers();
          this.showSuccessToast('User deleted successfully!');
        })
        .catch((error) => {
          console.error('Error deleting user:', error);
          this.showDeleteConfirmation = false;
          this.userToDelete = null;
        });
    },
    openImportUsersModal() {
      if (this.$can('importer_collaborateurs')) {
        this.showImportUsersModal = true;
      }
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

      this.isImporting = true;

      this.$store.dispatch('user/importUsers', formData)
        .then((response) => {
          this.importResult = response;
          this.loadUsers();
          this.showSuccessToast('Users imported successfully!');
        })
        .catch((error) => {
          console.error('Error importing users:', error);
          this.importResult = {
            message: 'An error occurred while importing users. Please try again.',
            skipped_rows: [],
          };
        })
        .finally(() => {
          this.isImporting = false;
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
    showSuccessToast(message) {
      this.toastMessage = message;
      this.showToast = true;
      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    },
    prevPage() {
      if (this.currentPageIndex > 0) {
        this.currentPageIndex--;
      }
    },
    nextPage() {
      if (this.currentPageIndex < this.totalPages - 1) {
        this.currentPageIndex++;
      }
    },
    goToPage(pageIndex) {
      this.currentPageIndex = pageIndex;
    },
  },
};
</script>
