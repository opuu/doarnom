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
                        :value="pet.status"
                        :severity="statusSeverity"
                        class="status-tag"
                    />
                </div>
                <h2 class="subheading">{{ breedName }} — {{ pet.age }} yrs</h2>

                <div class="pet-details">
                    <div class="detail-item">
                        <label>Gender:</label>
                        <span>{{ pet.gender }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Size:</label>
                        <span>{{ pet.size }}</span>
                    </div>
                    <div class="detail-full">
                        <label>Description:</label>
                        <p>{{ pet.description }}</p>
                    </div>
                    <div class="detail-item">
                        <label>Location:</label>
                        <span>{{ pet.location }}</span>
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
                </div>

                <div class="actions">
                    <Button
                        label="Adopt Me"
                        icon="pi pi-heart"
                        class="p-button-rounded p-button-lg p-button-success"
                        @click="goToSignup"
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
</template>

<script setup>
import { reactive, ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import Button from "primevue/button";
import Tag from "primevue/tag";
import HomeCTA from "@/components/HomeCTA.vue";

import { getPet } from "@/services/petService";
import { listBreeds } from "@/services/breedService";
import { listTraits } from "@/services/traitService";

const route = useRoute();
const router = useRouter();

// placeholder image if pet.image is empty
const placeholder = "https://placehold.co/600x400";

// reactive pet model
const pet = reactive({
    id: null,
    name: "",
    description: "",
    breed_id: null,
    age: null,
    gender: "",
    size: "",
    status: "",
    location: "",
    vaccinated: false,
    fixed: false,
    image: "",
});

// load lookup data
const breeds = ref([]);
const traitsList = ref([]);

// compute display name
const breedName = computed(() => {
    const b = breeds.value.find((b) => b.id === pet.breed_id);
    return b ? b.name : "";
});

// compute this pet’s traits objects
const petTraits = computed(() => {
    const ids = Array.isArray(pet.trait_ids)
        ? pet.trait_ids
        : JSON.parse(pet.trait_ids || "[]");
    return traitsList.value.filter((t) => ids.includes(t.id));
});

// status → tag severity
const statusSeverity = computed(
    () =>
        ({ Available: "success", Pending: "warning", Adopted: "danger" }[
            pet.status
        ])
);

function goToSignup() {
    router.push("/signup");
}

onMounted(async () => {
    // fetch pet and breed list in parallel
    const [p, bList, tList] = await Promise.all([
        getPet(route.params.id),
        listBreeds(),
        listTraits(),
    ]);
    Object.assign(pet, p);
    breeds.value = bList;
    traitsList.value = tList;
});
</script>

<style scoped lang="scss">
.pet-page {
    padding: 2rem 0;
}

.pet-container {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.image-section {
    flex: 1 1 300px;
    img {
        width: 100%;
        border-radius: 1rem;
        object-fit: cover;
        max-height: 500px;
    }
}

.info-section {
    flex: 2 1 400px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;

    .header {
        display: flex;
        align-items: center;
        gap: 1rem;

        h1 {
            margin: 0;
            font-size: 2.5rem;
            color: var(--p-primary-color);
            font-weight: 700;
        }

        .status-tag {
            font-weight: 600;
        }
    }

    .subheading {
        margin: 0.5rem 0 1.5rem;
        font-size: 1.5rem;
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
            span {
                color: #555;
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
    }

    .actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
    }
}
</style>
