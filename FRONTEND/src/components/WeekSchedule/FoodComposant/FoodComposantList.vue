<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold">Food Composants</h2>
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="showCreateModal = true"
      >
        Create Food Composant
      </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="w-full table-auto">
        <thead>
          <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th class="py-3 px-6 text-left">Name</th>
            <th class="py-3 px-6 text-left">Description</th>
            <th class="py-3 px-6 text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr
            v-for="foodComposant in foodComposants"
            :key="foodComposant.id"
            class="border-b border-gray-200 hover:bg-gray-100"
          >
            <td class="py-3 px-6 text-left whitespace-nowrap">
              {{ foodComposant.name }}
            </td>
            <td class="py-3 px-6 text-left">
              {{ foodComposant.description }}
            </td>
            <td class="py-3 px-6 text-center">
              <button
  class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
  @click="showEditModal(foodComposant)"
>
  Edit
</button>

              <button
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                @click="showDeleteConfirmation(foodComposant)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create Food Composant Modal -->
    <overlay v-if="showCreateModal" @close="showCreateModal = false">
      <modal :show="showCreateModal" title="Create Food Composant" @close="showCreateModal = false">
        <food-composant-form
          :foodComposant="{ name: '', description: '' }"
          @create="createFoodComposant"
          @close="showCreateModal = false"
        />
      </modal>
    </overlay>

    <!-- Edit Food Composant Modal -->
    <overlay v-if="showEditModal" @close="showEditModal = false">
      <modal :show="showEditModal" title="Edit Food Composant" @close="showEditModal = false">
        <food-composant-form
          :foodComposant="selectedFoodComposant"
          @update="updateFoodComposant"
          @close="showEditModal = false"
        />
      </modal>
    </overlay>

    <!-- Delete Confirmation Modal -->
    <overlay v-if="showDeleteConfirmation" @close="showDeleteConfirmation = false">
      <modal
        :show="showDeleteConfirmation"
        title="Delete Food Composant"
        @close="showDeleteConfirmation = false"
      >
        <div>
          <p>
            Are you sure you want to delete the food composant
            "{{ selectedFoodComposant.name }}"? This action cannot be undone.
          </p>
        </div>
        <div class="mt-4 flex justify-end">
          <button
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"
            @click="deleteFoodComposant"
          >
            Delete
          </button>
          <button
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            @click="showDeleteConfirmation = false"
          >
            Cancel
          </button>
        </div>
      </modal>
    </overlay>
  </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex'
import FoodComposantForm from './FoodComposantForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'

export default {
  components: {
    FoodComposantForm,
    Modal,
    Overlay
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      showDeleteConfirmation: false,
      selectedFoodComposant: null
    }
  },
  computed: {
    ...mapGetters('foodComposant', ['foodComposants'])
  },
  created() {
    this.$nextTick(() => {
      this.fetchFoodComposants()
    })
  },
  methods: {
  ...mapActions('foodComposant', [
    'fetchFoodComposants',
    'createFoodComposant',
    'updateFoodComposant',
    'deleteFoodComposant'
  ]),
  showEditModal(foodComposant) {
    this.selectedFoodComposant = { ...foodComposant };
    this.showEditModal = true;
  },
  showDeleteConfirmation(foodComposant) {
    this.selectedFoodComposant = { ...foodComposant };
    this.showDeleteConfirmation = true;
  }
}

}
</script>
