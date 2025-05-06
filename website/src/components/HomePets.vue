<template>
  <div class="home-pets">
    <h2>{{ categoryName }}</h2>
    <div class="pets-list">
      <Card v-for="pet in displayedPets" :key="pet.id">
        <template #header>
          <img :src="pet.image || placeholder" :alt="pet.name" />
        </template>
        <template #title>{{ pet.name }}</template>
        <template #subtitle>{{ pet.age }} yrs old</template>
        <template #footer>
          <div class="flex gap-4 mt-1">
            <Button label="View" class="w-full" @click="viewPet(pet.id)" />
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import { listPets } from "@/services/petService";
import { listCategories } from "@/services/categoryService";
import Card from "primevue/card";
import Button from "primevue/button";

const props = defineProps({
  categoryName: { type: String, required: true },
  limit: { type: Number, default: 6 },
});

const pets = ref([]);
const categories = ref([]);
const router = useRouter();
const placeholder = "https://placehold.co/600x400";

onMounted(async () => {
  [pets.value, categories.value] = await Promise.all([
    listPets(),
    listCategories(),
  ]);
});

const currentCategory = computed(() =>
  categories.value.find(
    (c) => c.name.toLowerCase() === props.categoryName.toLowerCase()
  )
);

const filteredPets = computed(() =>
  currentCategory.value
    ? pets.value.filter((p) => p.category_id === currentCategory.value.id)
    : []
);

const displayedPets = computed(() => filteredPets.value.slice(0, props.limit));

function viewPet(id) {
  router.push(`/pet/${id}`);
}
</script>

<style scoped>
.home-pets {
  &:not(:last-child) {
    margin-bottom: 3rem;
  }

  h2 {
    font-size: 2rem;
    margin-bottom: 2rem;
  }

  .pets-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
  }

  img {
    width: 100%;
    height: 250px;
    object-fit: cover;
  }
}
</style>
