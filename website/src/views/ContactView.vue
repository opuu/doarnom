<template>
    <section class="contact-page">
        <h1>Contact Us</h1>
        <p>Have questions or need assistance?</p>

        <form class="contact-form" @submit.prevent="handleSubmit">
            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" v-model="form.name" type="text" required />
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" v-model="form.email" type="email" required />
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea
                    id="message"
                    v-model="form.message"
                    rows="5"
                    required
                ></textarea>
            </div>

            <button type="submit" class="cta-btn">Send Message</button>
        </form>
    </section>

    <HomeCTA />
</template>

<script setup>
import { ref } from "vue";
import HomeCTA from "@/components/HomeCTA.vue";

const form = ref({
    name: "",
    email: "",
    message: "",
});

function handleSubmit() {
    // TODO: replace with real submit logic
    alert(`Thanks, ${form.value.name}! We'll be in touch soon.`);
    form.value = { name: "", email: "", message: "" };
}
</script>

<style scoped lang="scss">
.contact-page {
    padding: 2rem 0;
    color: #333;

    h1 {
        font-size: 2.5rem;
        color: var(--p-primary-color);
        margin-bottom: 1rem;
        font-weight: 700;
        text-align: center;
    }

    p {
        font-size: 1.125rem;
        color: #555;
        margin-bottom: 2rem;
        line-height: 1.6;
        text-align: center;
    }

    .contact-form {
        display: grid;
        gap: 1.5rem;
        max-width: 600px;
        margin: 0 auto;

        .form-group {
            display: flex;
            flex-direction: column;

            label {
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: #333;
            }

            input,
            textarea {
                padding: 0.75rem 1rem;
                border: 1px solid #ccc;
                border-radius: 0.5rem;
                font-size: 1rem;
                font-family: inherit;

                &:focus {
                    outline: none;
                    border-color: var(--p-primary-color);
                    box-shadow: 0 0 0 2px rgba(255, 111, 97, 0.2);
                }
            }
        }

        .cta-btn {
            padding: 0.75rem 2rem;
            background: var(--p-primary-color);
            color: #fff;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 4px 16px rgba(255, 111, 97, 0.15);

            &:hover {
                background: var(--p-primary-hover-color);
            }
        }
    }
}
</style>
