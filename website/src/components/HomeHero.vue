<template>
    <div class="hero">
        <div class="hero__content">
            <h1>Find Your New Best Friend</h1>
            <p class="subtitle">
                Adopt a loving pet and give them a forever home. Browse our
                adorable cats and dogs waiting for you!
            </p>
            <router-link to="/search" class="cta-btn">Browse Pets</router-link>
        </div>
        <div class="hero__image">
            <img
                src="@/assets/pets.jpg"
                alt="Happy pets waiting for adoption"
            />
        </div>
    </div>
</template>

<style scoped>
.hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 70vh;
    box-sizing: border-box;
}

.hero__content {
    max-width: 500px;
}

h1 {
    font-size: 3rem;
    color: var(--p-primary-color);
    margin-bottom: 1rem;
    font-weight: 800;
    letter-spacing: 1px;
}

.subtitle {
    font-size: 1.25rem;
    color: #444;
    margin-bottom: 2rem;
}

.cta-btn {
    display: inline-block;
    padding: 0.75rem 2rem;
    background: var(--p-primary-color);
    color: #fff;
    border-radius: 30px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
    box-shadow: 0 4px 16px rgba(255, 111, 97, 0.15);
}

.cta-btn:hover {
    background: var(--p-primary-hover-color);
}

.hero__image img {
    max-width: 600px;
    width: 100%;
    border-radius: 2rem;
    object-fit: cover;
}

@media (max-width: 900px) {
    .hero {
        flex-direction: column-reverse;
        padding: 2rem 1rem;
        text-align: center;
    }
    .hero__image img {
        margin-bottom: 2rem;
        max-width: 90vw;
    }
}
</style>
