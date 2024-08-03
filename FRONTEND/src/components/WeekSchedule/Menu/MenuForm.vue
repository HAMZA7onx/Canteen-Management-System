<template>
    <form @submit.prevent="submitForm" class="bg-white dark:bg-gray-800 rounded-2xl p-6 space-y-6">
      <div class="relative">
        <input
          id="name"
          v-model="menu.name"
          type="text"
          required
          class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-emerald-500 dark:focus:border-emerald-400 focus:outline-none transition duration-300 placeholder-transparent"
          placeholder="Name"
        />
        <label
          for="name"
          class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-emerald-500 dark:peer-focus:text-emerald-400"
        >
          Nom
        </label>
      </div>

      <div class="relative">
        <textarea
          id="description"
          v-model="menu.description"
          rows="4"
          class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-emerald-500 dark:focus:border-emerald-400 focus:outline-none transition duration-300 placeholder-transparent resize-none"
          placeholder="Description"
        ></textarea>
        <label
          for="description"
          class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-emerald-500 dark:peer-focus:text-emerald-400"
        >
          Description
        </label>
      </div>

      <div class="relative">
        <input
          type="file"
          ref="fileInput"
          @change="handleFileUpload"
          accept="image/*"
          class="hidden"
        />
        <button
          type="button"
          @click="$refs.fileInput.click()"
          class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-emerald-500 dark:hover:border-emerald-400 focus:outline-none transition duration-300"
        >
          <span v-if="!imagePreview">Select Image</span>
          <span v-else>Change Image</span>
        </button>
        <img
          v-if="imagePreview"
          :src="imagePreview"
          alt="Menu preview"
          class="mt-4 w-full h-48 object-cover rounded-lg"
        />
        <p v-if="imageSizeWarning" class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
          {{ imageSizeWarning }}
        </p>
      </div>


      <div class="flex justify-end space-x-4">
        <button
          type="button"
          @click="$emit('close')"
          class="px-6 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-300"
        >
          Annuler
        </button>
        <button
          type="submit"
          class="px-6 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-cyan-600 dark:from-emerald-400 dark:to-cyan-500 text-white hover:from-emerald-600 hover:to-cyan-700 dark:hover:from-emerald-500 dark:hover:to-cyan-600 transition duration-300"
        >
          {{ isEditMode ? 'Modifier' : 'Créer' }}
        </button>
      </div>
    </form>
</template>

<script>
import { API_URL } from '@/config/config.js';


export default {
  props: {
    menu: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      isEditMode: false,
      imageFile: null,
      imagePreview: null,
      imageSizeWarning: '',
    }
  },
  created() {
    this.isEditMode = !!this.menu.id
    if (this.menu.image) {
      this.imagePreview = `${API_URL.replace('/api', '')}/storage/${this.menu.image}`
    }
  },

  methods: {
    handleFileUpload(event) {
      const file = event.target.files[0]
      if (file) {
        this.imageFile = file
        this.imagePreview = URL.createObjectURL(file)
        
        // Check file size
        const fileSizeInMB = file.size / (1024 * 1024)
        if (fileSizeInMB > 3) {
          this.imageSizeWarning = 'Warning: Image size exceeds 3MB. Please use an image under 2MB for optimal performance.'
        } else {
          this.imageSizeWarning = ''
        }
      }
    },

    submitForm() {
      const formData = new FormData()
      formData.append('name', this.menu.name)
      formData.append('description', this.menu.description)
      if (this.imageFile) {
        formData.append('image', this.imageFile)
      }

      this.$emit(this.isEditMode ? 'update' : 'create', formData)
      this.$emit('close')
    }
  }
}
</script>
