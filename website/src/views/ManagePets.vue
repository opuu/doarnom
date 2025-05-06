<template>
  <section class="manage-pets">
    <h1>Manage Pets</h1>

    <!-- Toolbar: New Pet + Search -->
    <Toolbar class="p-mb-3">
      <template #start>
        <!-- everybody -->
        <Button
          label="New Pet"
          icon="pi pi-plus"
          class="p-button-success p-mr-2"
          @click="openNew"
        />
      </template>

      <!-- admin only -->
      <template #center>
        <template v-if="isAdmin">
          <Button
            label="New Category"
            icon="pi pi-bookmark"
            class="p-button-sm p-button-info p-mr-2"
            @click="openCategoryDialog"
          />
          <Button
            label="New Breed"
            icon="pi pi-tag"
            class="p-button-sm p-button-warning p-mr-2"
            @click="openBreedDialog"
          />
          <Button
            label="New Trait"
            icon="pi pi-star"
            class="p-button-sm p-button-help"
            @click="openTraitDialog"
          />
        </template>
      </template>

      <template #end>
        <span class="p-input-icon-left">
          <i class="pi pi-search"></i>
          <InputText v-model="search" placeholder="Search pets..." />
        </span>
      </template>
    </Toolbar>

    <!-- Pets table -->
    <DataTable
      :value="filteredPets"
      dataKey="id"
      responsiveLayout="scroll"
      :paginator="true"
      :rows="8"
      class="p-mb-5"
      emptyMessage="No pets found."
    >
      <Column
        field="name"
        header="Name"
        sortable
        filter
        filterPlaceholder="Name"
      />
      <Column field="category_id" header="Category" sortable />
      <Column field="age" header="Age" sortable />
      <Column header="Actions">
        <template #body="{ data }">
          <Button
            icon="pi pi-pencil"
            class="p-button-text"
            @click="editPet(data)"
          />
          <Button
            icon="pi pi-trash"
            class="p-button-text p-button-danger"
            @click="confirmDeleteDialog(data)"
          />
        </template>
      </Column>
    </DataTable>

    <!-- Pet Create/Edit Dialog -->
    <Dialog
      :header="editMode ? 'Edit Pet' : 'New Pet'"
      v-model:visible="petDialog"
      modal
      class="p-fluid pet-dialog"
      :style="{ width: '600px' }"
    >
      <div class="formgrid grid">
        <div class="field col-6">
          <label for="name">Name</label>
          <InputText id="name" v-model="currentPet.name" />
        </div>
        <div class="field col-6">
          <label for="category">Category</label>
          <Dropdown
            id="category"
            v-model="currentPet.category_id"
            :options="categories"
            optionLabel="name"
            optionValue="id"
            placeholder="Select category"
          />
        </div>
        <div class="field col-6">
          <label for="breed">Breed</label>
          <Dropdown
            id="breed"
            v-model="currentPet.breed_id"
            :options="breeds"
            optionLabel="name"
            optionValue="id"
            placeholder="Select breed"
          />
        </div>
        <div class="field col-6">
          <label for="age">Age</label>
          <InputNumber id="age" v-model="currentPet.age" :min="0" />
        </div>
        <div class="field col-6">
          <label for="gender">Gender</label>
          <Dropdown
            id="gender"
            v-model="currentPet.gender"
            :options="genderOptions"
            placeholder="Select gender"
            optionLabel="label"
            optionValue="value"
          />
        </div>
        <div class="field col-6">
          <label for="weight">Weight</label>
          <InputNumber id="weight" v-model="currentPet.weight" :min="0" />
        </div>
        <div class="field col-12">
          <label for="description">Description</label>
          <InputTextarea
            id="description"
            v-model="currentPet.description"
            rows="3"
          />
        </div>
        <div class="field col-12">
          <label for="traits">Traits</label>
          <MultiSelect
            id="traits"
            v-model="currentPet.trait_ids"
            :options="traits"
            optionLabel="name"
            optionValue="id"
            placeholder="Select traits"
          />
        </div>
        <div class="field col-12">
          <label for="location">Location</label>
          <InputText id="location" v-model="currentPet.location" />
        </div>
        <div class="field col-12">
          <label for="image">Image</label>
          <input
            type="file"
            id="image"
            accept="image/*"
            @change="handleFileChange"
          />
        </div>

        <div class="field col-12" v-if="currentPet.imagePreview">
          <img
            :src="currentPet.imagePreview"
            style="max-width: 100%; max-height: 200px; margin-top: 0.5rem"
            alt="Preview"
          />
        </div>

        <div
          class="field col-12 classification-preview"
          v-if="currentPet.classification"
        >
          <h5>Classification Results:</h5>
          <p>
            <strong>Animal Type:</strong>
            {{ currentPet.classification.animalType }}
          </p>
          <ul>
            <li
              v-for="(p, i) in currentPet.classification.predictions"
              :key="i"
            >
              {{ p.label }} – {{ (p.score * 100).toFixed(1) }}%
            </li>
          </ul>
        </div>
      </div>

      <template #footer>
        <Button
          label="Cancel"
          icon="pi pi-times"
          class="p-button-text"
          @click="hideDialog"
        />
        <Button
          :label="editMode ? 'Update' : 'Create'"
          icon="pi pi-check"
          @click="savePet"
        />
      </template>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <Dialog
      header="Confirm"
      v-model:visible="deleteDialog"
      modal
      class="p-fluid confirm-dialog"
      :style="{ width: '400px' }"
    >
      <p>
        Are you sure you want to delete <b>{{ currentPet.name }}</b
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
          @click="deletePetConfirmed"
        />
      </template>
    </Dialog>

    <!-- Admin: Category Dialog -->
    <Dialog
      header="New Category"
      v-model:visible="categoryDialog"
      modal
      class="p-fluid category-dialog"
      :style="{ width: '350px' }"
    >
      <div class="p-field">
        <label>Name</label><InputText v-model="newCategory.name" />
      </div>
      <template #footer>
        <Button
          label="Cancel"
          icon="pi pi-times"
          class="p-button-text"
          @click="categoryDialog = false"
        />
        <Button label="Create" icon="pi pi-check" @click="saveCategory" />
      </template>
    </Dialog>

    <!-- Admin: Breed Dialog -->
    <Dialog
      header="New Breed"
      v-model:visible="breedDialog"
      modal
      class="p-fluid breed-dialog"
      :style="{ width: '350px' }"
    >
      <div class="p-field">
        <label>Name</label><InputText v-model="newBreed.name" />
      </div>

      <!-- new: select a category for this breed -->
      <div class="p-field">
        <label>Category</label>
        <Dropdown
          v-model="newBreed.category_id"
          :options="categories"
          optionLabel="name"
          optionValue="id"
          placeholder="Select category"
        />
      </div>

      <template #footer>
        <Button
          label="Cancel"
          icon="pi pi-times"
          class="p-button-text"
          @click="breedDialog = false"
        />
        <Button label="Create" icon="pi pi-check" @click="saveBreed" />
      </template>
    </Dialog>

    <!-- Admin: Trait Dialog -->
    <Dialog
      header="New Trait"
      v-model:visible="traitDialog"
      modal
      class="p-fluid trait-dialog"
      :style="{ width: '350px' }"
    >
      <div class="p-field">
        <label>Name</label><InputText v-model="newTrait.name" />
      </div>
      <template #footer>
        <Button
          label="Cancel"
          icon="pi pi-times"
          class="p-button-text"
          @click="traitDialog = false"
        />
        <Button label="Create" icon="pi pi-check" @click="saveTrait" />
      </template>
    </Dialog>

    <Toast />
  </section>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import {
  listPets,
  createPet,
  updatePet,
  deletePet,
} from "@/services/petService";
import { listCategories, createCategory } from "@/services/categoryService";
import { listBreeds, createBreed } from "@/services/breedService";
import { listTraits, createTrait } from "@/services/traitService";

import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import InputTextarea from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Dropdown from "primevue/dropdown";
import MultiSelect from "primevue/multiselect";
import Button from "primevue/button";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import axios from "axios";

const auth = useAuthStore();
const toast = useToast();
const pets = ref([]);
const categories = ref([]);
const breeds = ref([]);
const traits = ref([]);
const search = ref("");

const statusOptions = [
  { label: "Available", value: "Available" },
  { label: "Pending", value: "Pending" },
  { label: "Adopted", value: "Adopted" },
];
const genderOptions = [
  { label: "Male", value: "male" },
  { label: "Female", value: "female" },
];
const petDialog = ref(false);
const deleteDialog = ref(false);
const editMode = ref(false);

const categoryDialog = ref(false);
const breedDialog = ref(false);
const traitDialog = ref(false);

const currentPet = reactive({
  id: null,
  name: "",
  description: "",
  breed_id: null,
  age: null,
  status: null,
  category_id: null,
  trait_ids: [],
  gender: null,
  weight: null,
  location: "",
  image: "",
  imagePreview: "",
  classification: null,
});
const newCategory = reactive({ name: "" });
const newBreed = reactive({
  name: "",
  category_id: null,
});
const newTrait = reactive({ name: "" });

const isAdmin = computed(
  () => auth.user?.role && ["admin", "superadmin"].includes(auth.user.role)
);

const filteredPets = computed(() => {
  const q = search.value.trim().toLowerCase();
  return q
    ? pets.value.filter((p) => p.name.toLowerCase().includes(q))
    : pets.value;
});

function fetchData() {
  listPets().then((r) => (pets.value = r));
  listCategories().then((r) => (categories.value = r));
  listBreeds().then((r) => (breeds.value = r));
  listTraits().then((r) => (traits.value = r));
}

onMounted(fetchData);

function openNew() {
  editMode.value = false;
  Object.assign(currentPet, {
    id: null,
    name: "",
    description: "",
    breed_id: null,
    age: null,
    status: null,
    category_id: null,
    trait_ids: [],
    gender: null,
    weight: null,
    location: "",
    image: "",
    imagePreview: "",
    classification: null,
  });
  petDialog.value = true;
}

function editPet(p) {
  console.log("editPet called:", p);
  editMode.value = true;
  Object.assign(currentPet, {
    id: p.id,
    name: p.name,
    description: p.description,
    breed_id: p.breed_id,
    age: p.age,
    status: p.status,
    category_id: p.category_id,
    trait_ids: JSON.parse(p.trait_ids),
    gender: p.gender,
    weight: p.weight,
    location: p.location,
    image: p.image,
    imagePreview: "",
    classification: p.classification ? JSON.parse(p.classification) : null,
  });
  petDialog.value = true;
}

function hideDialog() {
  petDialog.value = false;
}

async function handleFileChange(e) {
  const file = e.target.files[0];
  if (!file) return;

  // show local preview
  const reader = new FileReader();
  reader.onload = (ev) => (currentPet.imagePreview = ev.target.result);
  reader.readAsDataURL(file);

  // upload to AI service & get classification
  const form = new FormData();
  form.append("image", file);
  try {
    const res = await fetch("http://localhost:5000/classify", {
      method: "POST",
      body: form,
    });
    // {
    //     "success": true,
    //     "animal_type": "Dog",
    //     "predictions": [
    //         {
    //             "breed": "golden retriever",
    //             "category": "Dog",
    //             "confidence": 22
    //         },
    //         {
    //             "breed": "Labrador retriever",
    //             "category": "Dog",
    //             "confidence": 10
    //         },
    //         {
    //             "breed": "Cardigan",
    //             "category": "Other",
    //             "confidence": 8
    //         },
    //         {
    //             "breed": "Pembroke",
    //             "category": "Other",
    //             "confidence": 3
    //         },
    //         {
    //             "breed": "Rhodesian ridgeback",
    //             "category": "Other",
    //             "confidence": 3
    //         }
    //     ],
    //     "image_info": {
    //         "format": "JPEG",
    //         "mode": "RGB",
    //         "width": 900,
    //         "height": 563,
    //         "channels": 3,
    //         "file_size_bytes": 53049,
    //         "mean_color": [
    //             207,
    //             186.32,
    //             167.72
    //         ],
    //         "std_color": [
    //             59.66,
    //             74.78,
    //             91.61
    //         ],
    //         "exif": {}
    //     },
    //     "imageUrl": "http://localhost:5000/image/1746560070600-pets.jpg"
    // }

    const { success, animal_type, predictions, image_info, imageUrl } =
      await res.json();

    currentPet.image = imageUrl; // save the image URL to the pet object
    currentPet.colors = {
      mean: image_info.mean_color,
      std: image_info.std_color,
    };
    currentPet.classification = {
      animalType: animal_type,
      predictions: predictions.map((p) => ({
        label: p.breed,
        score: p.confidence / 100,
      })),
    };
  } catch (err) {
    toast.add({
      severity: "error",
      summary: "Classification Error",
      detail: err.message,
    });
  }
}

function savePet() {
  const payload = {
    name: currentPet.name,
    description: currentPet.description,
    breed_id: currentPet.breed_id,
    age: currentPet.age,
    status: currentPet.status,
    category_id: currentPet.category_id,
    trait_ids: JSON.stringify(currentPet.trait_ids),
    gender: currentPet.gender,
    weight: currentPet.weight,
    location: currentPet.location,
    image: currentPet.image,
    classification: JSON.stringify(currentPet.classification),
    colors: JSON.stringify(currentPet.colors),
  };
  const action = editMode.value
    ? updatePet(currentPet.id, payload)
    : createPet(payload);

  action
    .then(() => {
      toast.add({
        severity: "success",
        summary: editMode.value ? "Updated" : "Created",
        life: 3000,
      });
      petDialog.value = false;
      fetchData();
    })
    .catch((e) =>
      toast.add({
        severity: "error",
        summary: "Error",
        detail: e.message,
      })
    );
}

function confirmDeleteDialog(p) {
  Object.assign(currentPet, p);
  deleteDialog.value = true;
}

function deletePetConfirmed() {
  deletePet(currentPet.id)
    .then(() => {
      toast.add({ severity: "info", summary: "Deleted", life: 3000 });
      deleteDialog.value = false;
      fetchData();
    })
    .catch((e) =>
      toast.add({
        severity: "error",
        summary: "Error",
        detail: e.message,
      })
    );
}

// Admin: Category
function openCategoryDialog() {
  newCategory.name = "";
  categoryDialog.value = true;
}
function saveCategory() {
  createCategory({ name: newCategory.name })
    .then(() => {
      toast.add({ severity: "success", summary: "Category Created" });
      categoryDialog.value = false;
      listCategories().then((r) => (categories.value = r));
    })
    .catch((e) =>
      toast.add({
        severity: "error",
        summary: "Error",
        detail: e.message,
      })
    );
}

// Admin: Breed
function openBreedDialog() {
  newBreed.name = "";
  newBreed.category_id = null; // reset category
  breedDialog.value = true;
}
function saveBreed() {
  createBreed({
    name: newBreed.name,
    category_id: newBreed.category_id, // <— API expects snake_case
  })
    .then(() => {
      toast.add({ severity: "success", summary: "Breed Created" });
      breedDialog.value = false;
      return listBreeds();
    })
    .then((r) => (breeds.value = r))
    .catch((e) =>
      toast.add({
        severity: "error",
        summary: "Error",
        detail: e.message,
      })
    );
}

// Admin: Trait
function openTraitDialog() {
  newTrait.name = "";
  traitDialog.value = true;
}
function saveTrait() {
  createTrait({ name: newTrait.name })
    .then(() => {
      toast.add({ severity: "success", summary: "Trait Created" });
      traitDialog.value = false;
      listTraits().then((r) => (traits.value = r));
    })
    .catch((e) =>
      toast.add({
        severity: "error",
        summary: "Error",
        detail: e.message,
      })
    );
}
</script>

<style scoped lang="scss">
.manage-pets {
  padding: 2rem;
  h1 {
    margin-bottom: 1.5rem;
    color: var(--p-primary-color);
  }

  .p-toolbar-group-right {
    .p-input-icon-left input {
      width: 200px;
    }
  }
}

// Common dialog overrides
.pet-dialog,
.confirm-dialog,
.category-dialog,
.breed-dialog,
.trait-dialog {
  .p-dialog-content {
    padding: 1.5rem 2rem;
  }
  .p-dialog-header {
    background: var(--p-primary-color);
    color: #fff;
    font-size: 1.25rem;
    font-weight: 600;
    padding: 1rem 1.5rem;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
  }
  .p-dialog-footer {
    background: #f9f9f9;
    padding: 1rem 1.5rem;
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
  }
}

// Two-column grid for the pet form
.pet-dialog {
  .formgrid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));

    .field {
      margin-bottom: 1rem;
      label {
        font-weight: 600;
        margin-bottom: 0.25rem;
        display: block;
      }
    }
    /* make all half-cols full width */
    > .col-6 {
      flex: 0 0 100% !important;
      max-width: 100% !important;
    }
  }
}

.classification-preview {
  background: #f5f5f5;
  padding: 1rem;
  border-radius: 4px;
  margin-top: 1rem;
}

.p-mb-5 {
  margin-bottom: 2rem !important;
}

.p-mb-3 {
  margin-bottom: 1.5rem !important;
}

.p-mr-2 {
  margin-right: 0.5rem !important;
}
</style>
