<template>
    <section class="search-page">
        <div class="search-container">
            <aside class="filters">
                <h2>Filters</h2>
                <div class="filter-group">
                    <label>Search</label>
                    <InputText
                        v-model="filters.search"
                        placeholder="Name or keyword"
                    />
                </div>
                <div class="filter-group">
                    <label>Species</label>
                    <Dropdown
                        v-model="filters.species"
                        :options="speciesOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="All"
                        showClear
                    />
                </div>
                <div class="filter-group">
                    <label>Breed</label>
                    <MultiSelect
                        v-model="filters.breeds"
                        :options="breedOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Any"
                    />
                </div>
                <div class="filter-group">
                    <label>Age (yrs)</label>
                    <Slider
                        v-model="filters.ageRange"
                        :min="0"
                        :max="15"
                        :range="true"
                    />
                    <div class="range-labels">
                        <span>{{ filters.ageRange[0] }}</span>
                        <span>{{ filters.ageRange[1] }}</span>
                    </div>
                </div>
                <div class="filter-group">
                    <label>Size</label>
                    <MultiSelect
                        v-model="filters.sizes"
                        :options="sizeOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Any"
                    />
                </div>
                <div class="filter-group">
                    <label>Gender</label>
                    <MultiSelect
                        v-model="filters.genders"
                        :options="genderOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Any"
                    />
                </div>
                <div class="filter-group checkbox-group">
                    <Checkbox
                        v-model="filters.vaccinated"
                        :binary="true"
                        inputId="vaccinated"
                    />
                    <label for="vaccinated">Vaccinated</label>
                </div>
                <div class="filter-group checkbox-group">
                    <Checkbox
                        v-model="filters.fixed"
                        :binary="true"
                        inputId="fixed"
                    />
                    <label for="fixed">Spayed/Neutered</label>
                </div>
                <div class="filter-group">
                    <label>Traits</label>
                    <div v-for="t in traits" :key="t.id" class="checkbox-group">
                        <Checkbox
                            v-model="filters.traits"
                            :value="t.id"
                            :binary="false"
                            :inputId="`trait-${t.id}`"
                        />
                        <label :for="`trait-${t.id}`">{{ t.name }}</label>
                    </div>
                </div>
                <div class="filter-actions">
                    <Button
                        label="Clear All"
                        icon="pi pi-filter-slash"
                        class="p-button-text"
                        @click="resetFilters"
                    />
                </div>
            </aside>

            <div class="results">
                <h2>Available Pets ({{ filteredPets.length }})</h2>
                <div class="grid">
                    <div v-for="p in filteredPets" :key="p.id" class="pet-card">
                        <div class="img-wrap">
                            <img
                                src="https://placehold.co/600x400"
                                :alt="p.name"
                            />
                        </div>
                        <div class="info">
                            <h3>{{ p.name }}</h3>
                            <p class="meta">
                                {{
                                    breeds.find((e) => (p.breed_id = e.id)).name
                                }}
                                | {{ p.age }} yrs
                            </p>
                        </div>
                        <Button
                            label="View"
                            icon="pi pi-eye"
                            class="p-button-sm p-button-rounded"
                            @click="$router.push(`/pet/${p.id}`)"
                        />
                    </div>
                </div>
            </div>
        </div>
        <HomeCTA />
    </section>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import MultiSelect from "primevue/multiselect";
import Slider from "primevue/slider";
import Checkbox from "primevue/checkbox";
import Tag from "primevue/tag";
import Button from "primevue/button";
import HomeCTA from "@/components/HomeCTA.vue";

import { listPets } from "@/services/petService";
import { listCategories } from "@/services/categoryService";
import { listBreeds } from "@/services/breedService";
import { listTraits } from "@/services/traitService";

const router = useRouter();

const pets = ref([]);
const categories = ref([]);
const breeds = ref([]);
const traits = ref([]);

const filters = reactive({
    search: "",
    species: null,
    breeds: [],
    ageRange: [0, 15],
    sizes: [],
    genders: [],
    vaccinated: false,
    fixed: false,
    traits: [],
});

const speciesOptions = computed(() =>
    categories.value.map((c) => ({ label: c.name, value: c.id }))
);
const breedOptions = computed(() =>
    breeds.value.map((b) => ({ label: b.name, value: b.id }))
);

const sizeOptions = [
    { label: "Small", value: "Small" },
    { label: "Medium", value: "Medium" },
    { label: "Large", value: "Large" },
];
const genderOptions = [
    { label: "Male", value: "Male" },
    { label: "Female", value: "Female" },
];

const filteredPets = computed(() =>
    pets.value.filter((p) => {
        if (
            filters.search &&
            !p.name.toLowerCase().includes(filters.search.toLowerCase())
        )
            return false;
        if (filters.species && p.category_id !== filters.species) return false;
        if (filters.breeds.length && !filters.breeds.includes(p.breed_id))
            return false;
        if (p.age < filters.ageRange[0] || p.age > filters.ageRange[1])
            return false;
        if (filters.sizes.length && !filters.sizes.includes(p.size))
            return false;
        if (filters.genders.length && !filters.genders.includes(p.gender))
            return false;
        if (filters.vaccinated && !p.vaccinated) return false;
        if (filters.fixed && !p.fixed) return false;
        if (filters.traits.length) {
            const petTraitIds = Array.isArray(p.trait_ids)
                ? p.trait_ids
                : JSON.parse(p.trait_ids || "[]");
            if (!filters.traits.every((id) => petTraitIds.includes(id))) {
                return false;
            }
        }
        return true;
    })
);

function statusSeverity(status) {
    return { Available: "success", Pending: "warning", Adopted: "danger" }[
        status
    ];
}

function resetFilters() {
    filters.search = "";
    filters.species = null;
    filters.breeds = [];
    filters.ageRange = [0, 15];
    filters.sizes = [];
    filters.genders = [];
    filters.vaccinated = false;
    filters.fixed = false;
    filters.traits = [];
}

onMounted(async () => {
    try {
        pets.value = await listPets();
        categories.value = await listCategories();
        breeds.value = await listBreeds();
        traits.value = await listTraits();
    } catch (e) {
        console.error("Failed to load pets/categories/breeds", e);
    }
});
</script>

<style scoped lang="scss">
.search-page {
    .search-container {
        display: flex;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    .filters {
        width: 250px;
        background: #f9f9f9;
        padding: 1rem;
        border-radius: 0.5rem;

        h2 {
            margin-top: 0;
            font-size: 1.5rem;
            color: var(--p-primary-color);
        }
        .filter-group {
            margin: 1rem 0;
            label {
                display: block;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }
            &.checkbox-group {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin: 0.5rem 0;
            }
        }
        .range-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #555;
        }
        .filter-actions {
            text-align: right;
        }
    }
    .results {
        flex: 1;
        h2 {
            font-size: 1.75rem;
            color: var(--p-primary-color);
            margin-bottom: 1rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .pet-card {
            background: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);

            .img-wrap {
                width: 100%;
                padding-bottom: 100%;
                position: relative;
                img {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    border-radius: 0.5rem;
                }
            }
            .info {
                margin: 0.75rem 0;
                h3 {
                    margin: 0;
                    font-size: 1.25rem;
                    color: #333;
                }
                .meta {
                    font-size: 0.9rem;
                    color: #555;
                    margin-bottom: 0.5rem;
                }
            }
        }
    }
}
</style>
