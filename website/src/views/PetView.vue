<template>
  <section class="pet-page" v-if="pet.id">
    <div class="pet-container">
      <div class="image-section">
        <img :src="pet.image || placeholder" :alt="pet.name" />
      </div>
      <div class="info-section">
        <div class="header">
          <h1>{{ pet.name }}</h1>
          <Tag
            :value="pet.classification?.animalType || 'Unknown'"
            :severity="statusSeverity"
            class="status-tag"
          />
        </div>
        <h2 class="subheading">
          {{ categoryName }} / {{ breedName }} — {{ pet.age }} yrs
        </h2>

        <div class="pet-details">
          <div class="detail-item">
            <label>Gender:</label>
            <span>{{ pet.gender || "N/A" }}</span>
          </div>
          <div class="detail-item">
            <label>Size:</label>
            <span>{{ pet.size || "N/A" }}</span>
          </div>
          <div class="detail-item">
            <label>Weight:</label>
            <span>{{ pet.weight ? pet.weight + " kg" : "N/A" }}</span>
          </div>
          <div class="detail-item">
            <label>Height:</label>
            <span>{{ pet.height ? pet.height + " cm" : "N/A" }}</span>
          </div>
          <div class="detail-item">
            <label>Location:</label>
            <span>{{ pet.location || "N/A" }}</span>
          </div>
          <div class="detail-item">
            <label>Traits:</label>
            <span>
              <template v-if="petTraits.length">
                <Tag
                  v-for="t in petTraits"
                  :key="t.id"
                  :value="t.name"
                  class="p-mr-2"
                />
              </template>
              <template v-else>None</template>
            </span>
          </div>
          <div class="detail-item">
            <label>Vaccinated:</label>
            <span>{{ pet.vaccinated ? "Yes" : "No" }}</span>
          </div>
          <div class="detail-item">
            <label>Spayed/Neutered:</label>
            <span>{{ pet.fixed ? "Yes" : "No" }}</span>
          </div>

          <div class="detail-item">
            <label>Colors:</label>

            <div
              class="color"
              :style="`background: linear-gradient(45deg, rgba(${pet.colors?.std.join(
                ', '
              )},0.5) 0%, rgb(${pet.colors?.mean.join(', ')}) 100%);`"
            ></div>
          </div>

          <div class="detail-full">
            <label>Description:</label>
            <p>{{ pet.description || "No description." }}</p>
          </div>

          <div class="detail-item">
            <label>Added On:</label>
            <span>{{ formatDate(pet.created_at) }}</span>
          </div>
          <div class="detail-item">
            <label>Updated On:</label>
            <span>{{ formatDate(pet.updated_at) }}</span>
          </div>

          <div
            class="detail-full classification-results"
            v-if="pet.classification"
          >
            <h3>AI Classification</h3>
            <p>
              <strong>Animal Type:</strong> {{ pet.classification.animalType }}
            </p>
            <ul>
              <li
                v-for="(pred, idx) in pet.classification.predictions"
                :key="idx"
              >
                {{ pred.label }} — {{ (pred.score * 100).toFixed(1) }}%
              </li>
            </ul>
          </div>
        </div>

        <div class="actions">
          <Button
            label="Adopt Me"
            icon="pi pi-heart"
            class="p-button-rounded p-button-lg p-button-success"
            @click="openAdoptionDialog"
          />
          <Button
            label="Back to List"
            icon="pi pi-arrow-left"
            class="p-button-text"
            @click="$router.push('/search')"
          />
        </div>
      </div>
    </div>

    <HomeCTA />
  </section>

  <!-- Adoption Request Dialog -->
  <Dialog
    header="Request Adoption"
    v-model:visible="adoptionDialog"
    modal
    class="p-fluid"
    :style="{ width: '400px' }"
  >
    <div class="p-field">
      <label for="message">Phone</label>
      <InputTextarea
        id="message"
        v-model="adoptionMessage"
        rows="4"
        placeholder="Phone number and any additional message..."
      />
    </div>
    <template #footer>
      <Button
        label="Cancel"
        icon="pi pi-times"
        class="p-button-text"
        @click="adoptionDialog = false"
      />
      <Button label="Send Request" icon="pi pi-check" @click="submitAdoption" />
    </template>
  </Dialog>

  <Toast />
</template>

<script setup>
import { reactive, ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import Button from "primevue/button";
import Tag from "primevue/tag";
import Dialog from "primevue/dialog";
import InputTextarea from "primevue/inputtext";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import HomeCTA from "@/components/HomeCTA.vue";

import { getPet } from "@/services/petService";
import { listBreeds } from "@/services/breedService";
import { listCategories } from "@/services/categoryService";
import { listTraits } from "@/services/traitService";
import { createAdoption } from "@/services/adoptionService";
import { useAuthStore } from "@/stores/auth";

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();
const toast = useToast();
const placeholder = "https://placehold.co/600x400";

const pet = reactive({
  id: null,
  name: "",
  description: "",
  category_id: null,
  breed_id: null,
  trait_ids: [],
  age: null,
  gender: "",
  size: "",
  weight: null,
  height: null,
  location: "",
  status: "",
  vaccinated: false,
  fixed: false,
  image: "",
  created_at: "",
  updated_at: "",
  classification: null,
  colors: null,
  custom_fields: null,
});

const breeds = ref([]);
const categories = ref([]);
const traitsList = ref([]);

const breedName = computed(() => {
  const b = breeds.value.find((b) => b.id === pet.breed_id);
  return b?.name || "";
});
const categoryName = computed(() => {
  const c = categories.value.find((c) => c.id === pet.category_id);
  return c?.name || "";
});
const petTraits = computed(() =>
  traitsList.value.filter((t) => pet.trait_ids.includes(t.id))
);
const statusSeverity = computed(
  () =>
    ({
      Available: "success",
      Pending: "warning",
      Adopted: "danger",
    }[pet.status] || "info")
);

const adoptionDialog = ref(false);
const adoptionMessage = ref("");

function goToSignup() {
  router.push("/signup");
}
function formatDate(dt) {
  return dt ? new Date(dt).toLocaleDateString() : "";
}
function openAdoptionDialog() {
  if (!auth.user) {
    router.push("/login");
  } else {
    adoptionMessage.value = "";
    adoptionDialog.value = true;
  }
}
async function submitAdoption() {
  try {
    await createAdoption({
      pet_id: pet.id,
      message: adoptionMessage.value,
    });
    toast.add({
      severity: "success",
      summary: "Requested",
      detail: "Your adoption request has been sent.",
    });
    adoptionDialog.value = false;
  } catch (e) {
    toast.add({
      severity: "error",
      summary: "Error",
      detail: e.message,
    });
  }
}

onMounted(async () => {
  const [p, bList, cList, tList] = await Promise.all([
    getPet(route.params.id),
    listBreeds(),
    listCategories(),
    listTraits(),
  ]);
  // parse the JSON-strings we stored
  p.trait_ids = Array.isArray(p.trait_ids)
    ? p.trait_ids
    : JSON.parse(p.trait_ids || "[]");
  p.colors = JSON.parse(p.colors || "{}");
  p.custom_fields = p.custom_fields ? JSON.parse(p.custom_fields) : null;
  p.classification = p.classification ? JSON.parse(p.classification) : null;

  Object.assign(pet, p);
  breeds.value = bList;
  categories.value = cList;
  traitsList.value = tList;
});
</script>

<style scoped lang="scss">
.pet-page {
  padding: 2rem 0;
  color: #333;

  .pet-container {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }
  .image-section {
    flex: 1 1 300px;
    img {
      width: 100%;
      height: auto;
      object-fit: contain;
      border-radius: 8px;
    }
  }

  .info-section {
    flex: 2 1 400px;
    padding: 2rem;
    display: flex;
    flex-direction: column;

    .header {
      display: flex;
      align-items: center;
      gap: 1rem;
      h1 {
        margin: 0;
        font-size: 2.5rem;
        color: var(--p-primary-color);
      }
    }

    .subheading {
      margin: 0.5rem 0 1.5rem;
      font-size: 1.25rem;
      color: #555;
    }

    .pet-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;

      .detail-item {
        display: flex;
        justify-content: space-between;
        label {
          font-weight: 600;
          color: #333;
        }
      }
      .detail-full {
        grid-column: 1 / -1;
        label {
          display: block;
          font-weight: 600;
          margin-bottom: 0.5rem;
        }
        p {
          margin: 0;
          color: #555;
          line-height: 1.6;
        }
      }
      .classification-results {
        background: #f9f9f9;
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        h3 {
          margin-top: 0;
        }
        ul {
          margin: 0.5rem 0 0;
          padding-left: 1.2rem;
        }
      }
    }

    .actions {
      margin-top: auto;
      display: flex;
      gap: 1rem;
    }
  }
}
</style>
