<!-- src/components/Printer/AssignPosDevicesModal.vue -->
<template>
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
              Assign POS Devices to Printer
            </h3>
            <div class="mt-4">
              <h4 class="text-md font-medium text-gray-700 mb-2">Available POS Devices</h4>
              <multiselect
                v-model="selectedDevices"
                :options="assignablePosDevices"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                placeholder="Select POS devices"
                label="name"
                track-by="id"
                class="mb-4"
              ></multiselect>
              <button
                @click="assignDevices"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
                :disabled="isAssigning"
              >
                <span v-if="!isAssigning">Assign Selected Devices</span>
                <span v-else class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Assigning...
                </span>
              </button>
            </div>
            <div class="mt-6">
              <h4 class="text-md font-medium text-gray-700 mb-2">Assigned POS Devices</h4>
              <ul class="divide-y divide-gray-200">
                <li v-for="device in printerPosDevices" :key="device.id" class="py-2 flex justify-between items-center">
                  <span>{{ device.name }}</span>
                  <button
                    @click="unassignDevice(device.id)"
                    class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    :disabled="isUnassigning === device.id"
                  >
                    <span v-if="isUnassigning !== device.id">Unassign</span>
                    <svg v-else class="animate-spin h-4 w-4 text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                  </button>
                </li>
              </ul>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="$emit('close')"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { mapGetters, mapActions } from 'vuex';
  import Multiselect from 'vue-multiselect';
  
  export default {
    name: 'AssignPosDevicesModal',
    components: {
      Multiselect,
    },
    props: {
      printerId: {
        type: Number,
        required: true,
      },
    },
    data() {
      return {
        selectedDevices: [],
        isAssigning: false,
        isUnassigning: null,
      };
    },
    computed: {
      ...mapGetters('printer', ['assignablePosDevices', 'printerPosDevices']),
    },
    methods: {
      ...mapActions('printer', ['getAssignablePosDevices', 'assignPosDevices', 'unassignPosDevice']),
      async assignDevices() {
        if (this.selectedDevices.length === 0) return;
        this.isAssigning = true;
        try {
        const response = await this.assignPosDevices({
            printerId: this.printerId,
            deviceIds: this.selectedDevices.map(device => device.id),
        });
        this.selectedDevices = [];
        // You can use response.assigned_count here if you want to show it to the user
        this.$emit('show-toast', {
            message: `Successfully assigned ${response.assigned_count} device(s)`,
            type: 'success'
        });
        } catch (error) {
        console.error('Error assigning devices:', error);
        this.$emit('show-toast', {
            message: error.response?.data?.message || 'Error assigning devices',
            type: 'error'
        });
        } finally {
        this.isAssigning = false;
        }
     },

        async unassignDevice(deviceId) {
            this.isUnassigning = deviceId;
            try {
            await this.unassignPosDevice({
                printerId: this.printerId,
                deviceId: deviceId,
            });
            this.$emit('show-toast', {
                message: 'Device unassigned successfully',
                type: 'success'
            });
            } catch (error) {
            console.error('Error unassigning device:', error);
            this.$emit('show-toast', {
                message: 'Error unassigning device',
                type: 'error'
            });
            } finally {
            this.isUnassigning = null;
            }
        },
            },
            async created() {
            await this.getAssignablePosDevices(this.printerId);
            },
        };
  </script>
  
  <style src="vue-multiselect/dist/vue-multiselect.css"></style>
  