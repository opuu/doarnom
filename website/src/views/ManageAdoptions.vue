<template>
  <section class="manage-adoptions">
    <h1>Adoption Requests</h1>
    <div class="table-toolbar">
      <InputText
        v-model="globalFilter"
        placeholder="Search by pet, user or status"
        class="p-input-icon-left"
      >
        <i class="pi pi-search" slot="icon" />
      </InputText>
    </div>

    <DataTable
      :value="adoptions"
      :paginator="true"
      :rows="10"
      :filters="tableFilters"
      :global-filter-fields="['pet.name', 'user.name', 'status']"
      dataKey="id"
      responsiveLayout="scroll"
    >
      <Column field="id" header="ID" style="width: 4rem" />
      <Column header="Pet" sortable field="pet_id" />
      <Column field="adoption_date" header="Date" sortable />
      <Column field="status" header="Status" sortable />
      <Column field="message" header="Phone" />
      <Column header="Actions" style="width: 12rem">
        <template #body="slotProps">
          <Button
            icon="pi pi-pencil"
            class="p-button-text"
            @click="openEditDialog(slotProps.data)"
          />
          <Button
            icon="pi pi-trash"
            class="p-button-text p-button-danger"
            @click="confirmDelete(slotProps.data)"
          />
        </template>
      </Column>
    </DataTable>

    <!-- Edit Adoption Dialog -->
    <Dialog
      header="Update Request"
      v-model:visible="editDialog"
      modal
      class="p-fluid"
      :style="{ width: '400px' }"
    >
      <div class="p-field">
        <label for="status">Status</label>
        <Dropdown
          id="status"
          v-model="current.status"
          :options="statusOptions"
          optionLabel="label"
          optionValue="value"
        />
      </div>
      <div class="p-field">
        <label for="date">Date</label>
        <Calendar
          id="date"
          v-model="current.adoption_date"
          dateFormat="yy-mm-dd"
        />
      </div>
      <template #footer>
        <Button
          label="Cancel"
          icon="pi pi-times"
          @click="editDialog = false"
          class="p-button-text"
        />
        <Button label="Save" icon="pi pi-check" @click="saveEdit" />
      </template>
    </Dialog>

    <!-- Delete Confirmation -->
    <Dialog
      header="Confirm"
      v-model:visible="deleteDialog"
      modal
      footerClass="p-d-flex p-jc-end"
      :style="{ width: '350px' }"
    >
      <p>
        Delete adoption request for <strong>{{ current.pet?.name }}</strong
        >?
      </p>
      <template #footer>
        <Button
          label="No"
          icon="pi pi-times"
          class="p-button-text"
          @click="deleteDialog = false"
        />
        <Button
          label="Yes"
          icon="pi pi-check"
          class="p-button-danger"
          @click="deleteAdoptionConfirmed"
        />
      </template>
    </Dialog>

    <Toast />
  </section>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from "vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";

import {
  listAdoptions,
  updateAdoption,
  deleteAdoption,
} from "@/services/adoptionService";

const toast = useToast();

const adoptions = ref([]);
const globalFilter = ref("");
const tableFilters = reactive({
  global: { value: null, matchMode: "contains" },
});

const statusOptions = [
  { label: "Submitted", value: "submitted" },
  { label: "Scheduled", value: "scheduled" },
  { label: "Approved", value: "approved" },
  { label: "Rejected", value: "rejected" },
];

const editDialog = ref(false);
const deleteDialog = ref(false);
const current = reactive({
  id: null,
  pet: null,
  user: null,
  adoption_date: null,
  status: null,
  message: "",
});

function fetchData() {
  listAdoptions()
    .then((data) => (adoptions.value = data))
    .catch((err) =>
      toast.add({ severity: "error", summary: "Error", detail: err.message })
    );
}

function openEditDialog(row) {
  Object.assign(current, {
    id: row.id,
    pet: row.pet,
    user: row.user,
    adoption_date: row.adoption_date,
    status: row.status,
    message: row.message,
  });
  editDialog.value = true;
}

function saveEdit() {
  updateAdoption(current.id, {
    status: current.status,
    adoption_date: new Date(current.adoption_date).toISOString().slice(0, 10),
  })
    .then(() => {
      toast.add({ severity: "success", summary: "Saved" });
      editDialog.value = false;
      fetchData();
    })
    .catch((err) =>
      toast.add({ severity: "error", summary: "Error", detail: err.message })
    );
}

function confirmDelete(row) {
  Object.assign(current, row);
  deleteDialog.value = true;
}

function deleteAdoptionConfirmed() {
  deleteAdoption(current.id)
    .then(() => {
      toast.add({ severity: "info", summary: "Deleted" });
      deleteDialog.value = false;
      fetchData();
    })
    .catch((err) =>
      toast.add({ severity: "error", summary: "Error", detail: err.message })
    );
}

onMounted(fetchData);

// watch global filter
watch(globalFilter, (val) => {
  tableFilters.global.value = val;
});
</script>

<style scoped lang="scss">
.manage-adoptions {
  padding: 2rem;

  h1 {
    margin-bottom: 1.5rem;
    color: var(--p-primary-color);
  }

  .table-toolbar {
    margin-bottom: 1rem;
    .p-input-icon-left {
      width: 20rem;
    }
  }
}
</style>
