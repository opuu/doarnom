<template>
    <section class="profile-page">
        <h1>My Profile</h1>
        <Card class="profile-card">
            <template #title>
                <div class="profile-header">
                    <Avatar
                        image="https://placehold.co/400"
                        size="xlarge"
                        shape="circle"
                    />
                    <div class="profile-info">
                        <h2>{{ profile.name }}</h2>
                        <p class="joined">Joined on {{ profile.joined }}</p>
                    </div>
                </div>
            </template>

            <template #content>
                <div class="profile-details">
                    <div class="field">
                        <label>Name</label>
                        <InputText v-model="profile.name" />
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <InputText
                            disabled
                            v-model="profile.email"
                            type="email"
                        />
                    </div>
                </div>
            </template>

            <template #footer>
                <div class="actions">
                    <Button
                        label="Save"
                        icon="pi pi-check"
                        class="p-button-rounded"
                        @click="handleSave"
                    />
                    <Button
                        label="Logout"
                        icon="pi pi-sign-out"
                        class="p-button-text"
                        @click="handleLogout"
                    />
                </div>
            </template>
        </Card>
    </section>

    <HomeCTA />
</template>

<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth.js";
import Card from "primevue/card";
import Avatar from "primevue/avatar";
import InputText from "primevue/inputtext";
import InputTextarea from "primevue/inputtext";
import Button from "primevue/button";
import HomeCTA from "@/components/HomeCTA.vue";

const router = useRouter();
const auth = useAuthStore();

// initialise local profile from store
const profile = reactive({
    name: auth.user.name || "",
    email: auth.user.email || "",
    bio: auth.user.bio || "",
    joined: auth.user.created_at
        ? new Date(auth.user.created_at).toLocaleDateString()
        : "",
});

function handleSave() {
    auth.user = {
        ...auth.user,
        name: profile.name,
        email: profile.email,
        bio: profile.bio,
    };
    localStorage.setItem("user", JSON.stringify(auth.user));
    alert("Profile updated successfully.");
}

function handleLogout() {
    auth.logout();
    router.push("/login");
}
</script>

<style scoped lang="scss">
.profile-page {
    padding: 2rem 0;
    color: #333;

    h1 {
        font-size: 2.5rem;
        color: var(--p-primary-color);
        margin-bottom: 2rem;
        font-weight: 700;
        text-align: center;
    }

    .profile-card {
        max-width: 600px;
        margin: 0 auto;

        .profile-header {
            display: flex;
            align-items: center;
            gap: 1rem;

            .profile-info {
                h2 {
                    margin: 0;
                    font-size: 1.75rem;
                    color: var(--p-primary-color);
                }
                .joined {
                    font-size: 0.875rem;
                    color: #777;
                }
            }
        }

        .profile-details {
            display: grid;
            gap: 1rem;
            margin: 1.5rem 0;

            .field {
                display: flex;
                flex-direction: column;

                label {
                    margin-bottom: 0.5rem;
                    font-weight: 500;
                    color: #333;
                }
            }
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
    }
}
</style>
