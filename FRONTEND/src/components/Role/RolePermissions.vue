<template>
  <div class="mb-8">
    <div class="mb-8 bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
      <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Fonctionnalités</h3>
      <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
        <li>• Afficher les permissions actuellement attribuées au rôle</li>
        <li>• Ajouter de nouvelles permissions au rôle</li>
        <li>• Supprimer des permissions existantes du rôle</li>
        <li>• Mise à jour en temps réel des permissions affichées</li>
      </ul>
    </div>

    <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Autorisations attribuées</h3>

    <div v-if="isLoadingPermissions" class="flex justify-center items-center h-32">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>

    <div v-else class="mb-8 max-h-96 overflow-auto pr-2">
      <div v-for="(permissions, group) in groupedAssignedPermissions" :key="group" class="mb-4">
        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ formatGroupName(group) }}</h4>
        <transition-group name="list" tag="ul" class="space-y-2">
          <li
            v-for="permission in permissions"
            :key="permission.id"
            class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-2 transition-all duration-300 hover:shadow-md hover:scale-102"
          >
            <span class="text-gray-800 dark:text-gray-200 text-sm">{{ permission.name }}</span>
            <button
              @click="removePermission(permission)"
              class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 focus:outline-none transition-all duration-300 transform hover:rotate-12"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </li>
        </transition-group>
      </div>
    </div>

    <div class="flex flex-col space-y-4">
      <div class="relative">
        <multiselect
          v-model="selectedPermissions"
          :options="groupedAvailablePermissions"
          :multiple="true"
          :close-on-select="false"
          :clear-on-select="false"
          :preserve-search="true"
          placeholder="Sélectionnez des autorisations"
          label="name"
          track-by="id"
          :preselect-first="false"
          :class="['custom-multiselect', { 'dark-mode': isDarkMode }]"
        >
          <template #option="{ option }">
            <div class="flex items-center">
              <span class="mr-2">{{ option.name }}</span>
              <span class="text-xs text-gray-500 dark:text-gray-400">({{ option.group }})</span>
            </div>
          </template>
        </multiselect>
      </div>

      <button
        @click="assignPermissions"
        :disabled="selectedPermissions.length === 0 || isAssigning"
        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <span v-if="!isAssigning">Attribuer les autorisations</span>
        <span v-else class="flex items-center justify-center">
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Assigning...
        </span>
      </button>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import Multiselect from 'vue-multiselect';

export default {
  name: 'RolePermissions',
  components: {
    Multiselect,
  },
  props: {
    role: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      selectedPermissions: [],
      fetchedAssignedPermissions: [],
      isAssigning: false,
      isLoadingPermissions: true,
    };
  },
  computed: {
    ...mapGetters('role', ['assignedPermissions', 'availablePermissions']),
    groupedAssignedPermissions() {
      return this.groupPermissions(this.fetchedAssignedPermissions);
    },
    groupedAvailablePermissions() {
      return this.availablePermissions.filter(permission =>
        permission.name !== 'SUPER_ADMIN_PERMISSION' &&
        !this.fetchedAssignedPermissions.some(assigned => assigned.id === permission.id)
      ).map(permission => ({
        ...permission,
        group: this.getPermissionGroup(permission.name),
      }));
    },
    isDarkMode() {
    return document.documentElement.classList.contains('dark');
  },
  },
  mounted() {
    this.fetchRolePermissions();
    this.fetchAvailablePermissions();
  },
  methods: {
    ...mapActions('role', ['fetchRolePermissions', 'assignPermission', 'removePermission', 'fetchAvailablePermissions']),
    ...mapActions('auth', ['refreshPermissions']),
    async fetchRolePermissions() {
      this.isLoadingPermissions = true;
      try {
        const response = await this.$store.dispatch('role/fetchRolePermissions', this.role.id);
        this.fetchedAssignedPermissions = response;
      } catch (error) {
        console.error('Error fetching role permissions:', error);
      } finally {
        this.isLoadingPermissions = false;
      }
    },
    async fetchAvailablePermissions() {
      try {
        await this.$store.dispatch('role/fetchAvailablePermissions');
      } catch (error) {
        console.error('Error fetching available permissions:', error);
      }
    },
    async assignPermissions() {
      if (this.selectedPermissions.length > 0) {
        this.isAssigning = true;
        try {
          const permissionIds = this.selectedPermissions.map(permission => permission.id);
          await this.$store.dispatch('role/assignPermissions', {
            roleId: this.role.id,
            permissionIds: permissionIds,
          });
          this.selectedPermissions = [];
          await this.fetchRolePermissions();
          await this.$store.dispatch('auth/refreshPermissions');
        } catch (error) {
          console.error('Error assigning permissions:', error);
        } finally {
          this.isAssigning = false;
        }
      }
    },
    async removePermission(permission) {
      try {
        await this.$store.dispatch('role/removePermission', {
          roleId: this.role.id,
          permissionId: permission.id,
        });
        await this.fetchRolePermissions();
        await this.$store.dispatch('auth/refreshPermissions');
      } catch (error) {
        console.error('Error removing permission:', error);
      }
    },
    getPermissionGroup(permissionName) {
      const groups = {
        collaborateurs: ['voir_collaborateurs', 'importer_collaborateurs', 'creer_collaborateurs', 'modifier_collaborateurs', 'supprimer_collaborateurs'],
        roles: ['voir_roles', 'creer_roles', 'modifier_roles', 'supprimer_roles', 'assigner_permission_a_roles', 'desassigner_permission_des_roles'],
        categorie_collaborateur: ['voir_categorie_de_collaborateur', 'creer_categorie_de_collaborateur', 'modifier_categorie_de_collaborateur', 'supprimer_categorie_de_collaborateur'],
        badges_collaborateurs: ['voir_badges_collaborateurs', 'importer_badges_collaborateurs', 'gerer_badges_collaborateurs'],
        badges_administrateurs: ['voir_badges_administrateurs', 'importer_badges_administrateurs', 'gerer_badges_administrateurs'],
        composants_menus: ['voir_composants_menus', 'creer_composants_menus', 'modifier_composants_menus', 'supprimer_composants_menus'],
        categories_menus: ['voir_categories_menus', 'creer_categories_menus', 'modifier_categories_menus', 'supprimer_categories_menus', 'assigner_composants_menus', 'desassigner_composants_menus'],
        repas: ['voir_repas', 'creer_repas', 'modifier_repas', 'supprimer_repas', 'assigner_categories_menus', 'desassigner_categories_menus'],
        profils_repas: ['voir_profils_repas', 'creer_profils_repas', 'modifier_profils_repas', 'supprimer_profils_repas', 'assigner_repas', 'desassigner_repas'],
        enregistrements_repas: ['voir_enregistrements_repas'],
        POS: ['voir_POS', 'creer_POS', 'modifier_POS', 'supprimer_POS', 'ouvrir_interface_de_pointage_sur_POS'],
        subscribtions: ['gerer_subscribtions_des_admin'],
      };

      for (const [group, permissions] of Object.entries(groups)) {
        if (permissions.includes(permissionName)) {
          return group;
        }
      }
      return 'other';
    },
    groupPermissions(permissions) {
      const grouped = {};
      for (const permission of permissions) {
        const group = this.getPermissionGroup(permission.name);
        if (!grouped[group]) {
          grouped[group] = [];
        }
        grouped[group].push(permission);
      }
      return grouped;
    },
    formatGroupName(group) {
      return group.charAt(0).toUpperCase() + group.slice(1).replace(/_/g, ' ');
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style scoped>
.list-enter-active, .list-leave-active {
  transition: all 0.5s ease;
}
.list-enter-from, .list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}



/* .custom-multiselect {
  @apply bg-white border border-gray-300 rounded-xl shadow-sm transition-all duration-300 text-gray-800;
}

.custom-multiselect.dark-mode {
  @apply bg-gray-800 border-gray-600 text-gray-200;
}

.custom-multiselect .multiselect__tags {
  @apply bg-transparent border-none min-h-[42px] py-1 px-3;
}

.custom-multiselect .multiselect__tag {
  @apply bg-indigo-500 text-white py-1 px-2 rounded-md text-sm mr-1 mb-1;
}

.custom-multiselect.dark-mode .multiselect__tag {
  @apply bg-indigo-600;
}

.custom-multiselect .multiselect__tag-icon:after {
  @apply text-indigo-200;
}

.custom-multiselect.dark-mode .multiselect__tag-icon:after {
  @apply text-indigo-300;
}

.custom-multiselect .multiselect__tag-icon:focus,
.custom-multiselect .multiselect__tag-icon:hover {
  @apply bg-indigo-600;
}

.custom-multiselect.dark-mode .multiselect__tag-icon:focus,
.custom-multiselect.dark-mode .multiselect__tag-icon:hover {
  @apply bg-indigo-700;
}

.custom-multiselect .multiselect__input,
.custom-multiselect .multiselect__single {
  @apply bg-transparent text-gray-800 text-sm py-1 px-0;
}

.custom-multiselect.dark-mode .multiselect__input,
.custom-multiselect.dark-mode .multiselect__single {
  @apply text-gray-200;
}

.custom-multiselect .multiselect__input::placeholder {
  @apply text-gray-500;
}

.custom-multiselect.dark-mode .multiselect__input::placeholder {
  @apply text-gray-400;
}

.custom-multiselect .multiselect__content-wrapper {
  @apply border border-gray-300 rounded-b-xl bg-white;
}

.custom-multiselect.dark-mode .multiselect__content-wrapper {
  @apply border-gray-600 bg-gray-800;
}

.custom-multiselect .multiselect__option {
  @apply text-sm py-2 px-3 text-gray-800;
}

.custom-multiselect.dark-mode .multiselect__option {
  @apply text-gray-200;
}

.custom-multiselect .multiselect__option--highlight {
  @apply bg-indigo-500 text-white;
}

.custom-multiselect.dark-mode .multiselect__option--highlight {
  @apply bg-indigo-600 text-white;
}

.custom-multiselect .multiselect__option--selected {
  @apply bg-indigo-100 text-indigo-800 font-bold;
}

.custom-multiselect.dark-mode .multiselect__option--selected {
  @apply bg-indigo-900 text-indigo-200 font-bold;
}

.custom-multiselect .multiselect__option--selected.multiselect__option--highlight {
  @apply bg-red-500 text-white;
}

.custom-multiselect.dark-mode .multiselect__option--selected.multiselect__option--highlight {
  @apply bg-red-600 text-white;
}

.custom-multiselect .multiselect__placeholder {
  @apply text-gray-500 text-sm py-1 px-0;
}

.custom-multiselect.dark-mode .multiselect__placeholder {
  @apply text-gray-400;
} */

/* Custom scrollbar styles */
.overflow-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
  border: transparent;
}
</style>
