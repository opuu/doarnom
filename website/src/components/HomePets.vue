<template>
    <div class="home-pets">
        <h2>Cats</h2>
        <div class="pets-list">
            <Card style="overflow: hidden" v-for="i in 6" :key="i">
                <template #header>
                    <img alt="user header" src="https://placehold.co/600x400" />
                </template>
                <template #title>Advanced Card</template>
                <template #subtitle>Card subtitle</template>

                <template #footer>
                    <div class="flex gap-4 mt-1">
                        <Button label="Adopt" class="w-full" />
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { listPets } from "@/services/petService";
import Card from "primevue/card";
import Button from "primevue/button";

const pets = ref([]);

onMounted(async () => {
    pets.value = await listPets();
});

// use filteredPets computed against pets.value
</script>

<style scoped lang="scss">
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
