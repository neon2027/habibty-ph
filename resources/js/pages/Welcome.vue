<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ArrowRight,
    Camera,
    LockKeyhole,
    MessageCircle,
    ShoppingBag,
    Sparkles,
    Star,
    Trash2,
} from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { dashboard, home, login, register } from '@/routes';
import {
    checkout,
    destroy,
    store,
} from '@/actions/App/Http/Controllers/CartController';

type Product = {
    id: number;
    sku: string;
    name: string;
    category: string;
    description: string;
    price: number;
    stock: number;
    shade: string;
};

type CartItem = Product & {
    quantity: number;
    subtotal: number;
};

type Cart = {
    items: CartItem[];
    total: number;
    count: number;
};

const props = defineProps<{
    products: Product[];
    cart: Cart;
}>();

const activeCategory = ref('All');

const categories = computed(() => [
    'All',
    ...new Set(props.products.map((product) => product.category)),
]);
const filteredProducts = computed(() =>
    activeCategory.value === 'All'
        ? props.products
        : props.products.filter(
              (product) => product.category === activeCategory.value,
          ),
);
const customerReviews = [
    {
        name: 'Alyssa M.',
        location: 'Daraga',
        product: 'Rosy Matte Lip Tint',
        image: '/assets/reviews/review.jpg',
        quote: 'The shade is soft and pretty. Browsing the store and checking out after login was quick.',
    },
    {
        name: 'Rica B.',
        location: 'Legazpi City',
        product: 'Pearl Pink Mini Tote',
        image: '/assets/reviews/review2.jpg',
        quote: 'Super cute packaging and the tote feels premium. The app made it easy to check the item details.',
    },
    {
        name: 'Camille D.',
        location: 'Rawis',
        product: 'Hydra Glow Face Mist',
        image: '/assets/reviews/review3.jpg',
        quote: 'I love that I can browse before buying. The product info matched what I received.',
    },
    {
        name: 'Mae L.',
        location: 'Albay',
        product: 'Blush Gift Set',
        image: '/assets/reviews/review4.jpg',
        quote: 'Bought this as a gift and it looked lovely. The pink theme fits Habibty perfectly.',
    },
    {
        name: 'Janelle P.',
        location: 'Tabaco',
        product: 'Sakura Hair Claw',
        image: '/assets/reviews/review5.jpg',
        quote: 'The clip is sturdy and pretty. I like seeing other reviews before adding to cart.',
    },
    {
        name: 'Trisha G.',
        location: 'Camalig',
        product: 'Cotton Candy Hand Cream',
        image: '/assets/reviews/review6.jpg',
        quote: 'Smells sweet but not too strong. The store page feels clean and easy to use.',
    },
    {
        name: 'Nicole R.',
        location: 'Guinobatan',
        product: 'Rosy Matte Lip Tint',
        image: '/assets/reviews/review7.jpg',
        quote: 'The store helped me compare items before checkout. I will buy again.',
    },
    {
        name: 'Bea S.',
        location: 'Sto. Domingo',
        product: 'Blush Gift Set',
        image: '/assets/reviews/review8.jpg',
        quote: 'Pretty colors, smooth transaction, and the reviews made me confident to order.',
    },
];
const carouselReviews = computed(() => [
    ...customerReviews,
    ...customerReviews,
]);
const currentYear = new Date().getFullYear();
const socialLinks = [
    {
        name: 'Instagram',
        href: 'https://www.instagram.com/habibtylegazpiph',
        icon: Camera,
    },
    {
        name: 'Facebook',
        href: 'https://www.facebook.com/profile.php?id=61585055453077',
        icon: MessageCircle,
    },
];
const formatMoney = (amount: number) =>
    new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
    }).format(amount / 100);

const addProduct = (product: Product) => {
    router.post(
        store.url(),
        { product_id: product.id, quantity: 1 },
        { preserveScroll: true },
    );
};

const removeProduct = (item: CartItem) => {
    router.delete(destroy.url(item.id), { preserveScroll: true });
};

const checkoutCart = () => {
    router.post(checkout.url(), {}, { preserveScroll: true });
};
</script>

<template>
    <Head title="Habibty Legazpi PH" />

    <main class="min-h-screen bg-pink-50 text-stone-950">
        <header
            class="sticky top-0 z-30 border-b border-pink-100 bg-white/90 backdrop-blur"
        >
            <nav
                class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8"
            >
                <Link :href="home()" class="flex items-center gap-3">
                    <span
                        class="grid size-10 place-items-center rounded-md bg-pink-500 text-white shadow-sm"
                        ><ShoppingBag class="size-5"
                    /></span>
                    <span class="leading-tight"
                        ><span class="block text-base font-semibold"
                            >Habibty</span
                        ><span class="block text-xs font-medium text-pink-600"
                            >Legazpi PH</span
                        ></span
                    >
                </Link>

                <div class="flex items-center gap-2">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex h-9 items-center gap-2 rounded-md border border-pink-200 bg-white px-3 text-sm font-medium text-pink-700 shadow-sm transition hover:bg-pink-50"
                    >
                        <Sparkles class="size-4" />Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-flex h-9 items-center gap-2 rounded-md px-3 text-sm font-medium text-pink-700 transition hover:bg-pink-100"
                            ><LockKeyhole class="size-4" />Log in</Link
                        >
                        <Link
                            :href="register()"
                            class="hidden h-9 items-center gap-2 rounded-md bg-pink-500 px-3 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-600 sm:inline-flex"
                            >Register<ArrowRight class="size-4"
                        /></Link>
                    </template>
                </div>
            </nav>
        </header>

        <section
            class="relative overflow-hidden border-b border-pink-100 bg-white"
        >
            <div class="hero-waves" aria-hidden="true">
                <svg
                    class="hero-wave hero-wave-back"
                    viewBox="0 0 1440 320"
                    preserveAspectRatio="none"
                >
                    <path
                        d="M0,224L80,213.3C160,203,320,181,480,186.7C640,192,800,224,960,224C1120,224,1280,192,1360,176L1440,160L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"
                    />
                </svg>
                <svg
                    class="hero-wave hero-wave-front"
                    viewBox="0 0 1440 320"
                    preserveAspectRatio="none"
                >
                    <path
                        d="M0,192L80,176C160,160,320,128,480,138.7C640,149,800,203,960,202.7C1120,203,1280,149,1360,122.7L1440,96L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"
                    />
                </svg>
            </div>
            <div
                class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 sm:py-14 lg:min-h-[680px] lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-16"
            >
                <div class="flex flex-col gap-7">
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center gap-2 rounded-md bg-pink-100 px-3 py-1 text-sm font-semibold text-pink-700"
                            ><ShoppingBag class="size-4" />Browse the
                            store</span
                        >
                        <span
                            class="inline-flex items-center gap-2 rounded-md bg-white px-3 py-1 text-sm font-semibold text-stone-600 ring-1 ring-pink-100"
                            ><LockKeyhole class="size-4 text-pink-500" />Login
                            required for checkout</span
                        >
                    </div>

                    <div class="max-w-3xl">
                        <h1
                            class="font-serif text-4xl font-semibold tracking-normal text-stone-950 sm:text-5xl lg:text-6xl"
                        >
                            Kikay on a budget? Say less, Habibty
                        </h1>
                        <p
                            class="mt-5 max-w-2xl text-base leading-7 text-stone-600 sm:text-lg"
                        >
                            Trendy and affordable | authentic. Pasabuy +
                            beauty finds from Habibty Legazpi PH.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a
                            href="#catalog"
                            class="inline-flex h-12 items-center gap-2 rounded-md bg-pink-500 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-pink-600"
                        >
                            <ShoppingBag class="size-4" />
                            Browse products
                        </a>
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="login()"
                            class="inline-flex h-12 items-center gap-2 rounded-md border border-pink-200 bg-white px-5 text-sm font-semibold text-pink-700 shadow-sm transition hover:bg-pink-50"
                        >
                            <LockKeyhole class="size-4" />
                            Log in to buy
                        </Link>
                    </div>
                </div>

                <aside class="grid gap-4 sm:grid-cols-2 lg:gap-5">
                    <figure class="group relative overflow-hidden rounded-lg">
                        <img
                            src="/assets/illustrations/banner1.jpg"
                            alt="Habibty Legazpi PH pink product banner"
                            class="aspect-[9/14] h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110"
                        />
                        <figcaption
                            class="absolute inset-x-0 bottom-0 bg-linear-to-t from-pink-950/70 to-transparent p-4 text-white"
                        >
                            <p
                                class="text-xs font-semibold tracking-wide uppercase"
                            >
                                New arrivals
                            </p>
                            <p class="mt-1 text-lg font-semibold">
                                Browse pretty finds
                            </p>
                        </figcaption>
                    </figure>

                    <figure
                        class="group relative overflow-hidden rounded-lg sm:translate-y-8"
                    >
                        <img
                            src="/assets/illustrations/banner2.jpg"
                            alt="Habibty Legazpi PH featured shopping banner"
                            class="aspect-[9/14] h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110"
                        />
                        <figcaption
                            class="absolute inset-x-0 bottom-0 bg-linear-to-t from-pink-950/70 to-transparent p-4 text-white"
                        >
                            <p
                                class="text-xs font-semibold tracking-wide uppercase"
                            >
                                Easy shopping
                            </p>
                            <p class="mt-1 text-lg font-semibold">
                                Login to checkout
                            </p>
                        </figcaption>
                    </figure>
                </aside>
            </div>
        </section>

        <section class="border-y border-pink-100 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
                >
                    <div>
                        <p
                            class="inline-flex items-center gap-2 rounded-md bg-pink-100 px-3 py-1 text-sm font-semibold text-pink-700"
                        >
                            <Star class="size-4 fill-pink-500 text-pink-500" />
                            Customer reviews
                        </p>
                        <h2 class="mt-3 text-2xl font-semibold text-stone-950">
                            Loved by Habibty buyers
                        </h2>
                        <p
                            class="mt-2 max-w-2xl text-sm leading-6 text-stone-600"
                        >
                            Real purchase moments from customers who browsed,
                            logged in, and checked out their pink picks.
                        </p>
                    </div>
                    <div class="flex items-center gap-1 text-pink-500">
                        <Star
                            v-for="rating in 5"
                            :key="rating"
                            class="size-5 fill-pink-500"
                        />
                    </div>
                </div>

                <div
                    class="review-carousel -mx-4 overflow-hidden px-4"
                    aria-label="Customer review carousel"
                >
                    <div class="review-carousel-track flex w-max gap-4 py-1">
                        <article
                            v-for="(review, index) in carouselReviews"
                            :key="`${review.name}-${index}`"
                            class="w-[17rem] shrink-0 overflow-hidden rounded-lg border border-pink-100 bg-pink-50 shadow-sm sm:w-[18rem] lg:w-[19rem]"
                            :aria-hidden="index >= customerReviews.length"
                        >
                            <div class="relative bg-white">
                                <img
                                    :src="review.image"
                                    :alt="`${review.name} review for ${review.product}`"
                                    class="aspect-[9/14] w-full object-cover"
                                    loading="lazy"
                                />
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-linear-to-t from-pink-950/75 to-transparent p-3 text-white"
                                >
                                    <p class="text-xs font-semibold">
                                        {{ review.product }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid gap-3 p-4">
                                <div
                                    class="flex items-center gap-1 text-pink-500"
                                >
                                    <Star
                                        v-for="rating in 5"
                                        :key="rating"
                                        class="size-3.5 fill-pink-500"
                                    />
                                </div>
                                <p class="text-sm leading-6 text-stone-700">
                                    {{ review.quote }}
                                </p>
                                <div>
                                    <p
                                        class="text-sm font-semibold text-stone-950"
                                    >
                                        {{ review.name }}
                                    </p>
                                    <p class="text-xs text-stone-500">
                                        Verified buyer, {{ review.location }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section
            id="catalog"
            class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_22rem] lg:px-8"
        >
            <div class="min-w-0">
                <div
                    class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <h2 class="text-2xl font-semibold text-stone-950">
                            Store catalog
                        </h2>
                        <p class="mt-1 text-sm text-stone-600">
                            Pink, light pink, and white essentials.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="category in categories"
                            :key="category"
                            type="button"
                            class="h-9 rounded-md px-3 text-sm font-semibold transition"
                            :class="
                                activeCategory === category
                                    ? 'bg-pink-500 text-white shadow-sm'
                                    : 'bg-white text-stone-600 ring-1 ring-pink-100 hover:bg-pink-100'
                            "
                            @click="activeCategory = category"
                        >
                            {{ category }}
                        </button>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <article
                        v-for="product in filteredProducts"
                        :key="product.id"
                        class="overflow-hidden rounded-lg border border-pink-100 bg-white shadow-sm"
                    >
                        <div class="p-4">
                            <div
                                class="relative mb-4 grid aspect-[4/3] place-items-center overflow-hidden rounded-md bg-pink-50"
                                :style="{ '--product-shade': product.shade }"
                            >
                                <div
                                    class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,white_0,transparent_35%),linear-gradient(135deg,white,var(--product-shade))]"
                                />
                                <div
                                    class="relative h-32 w-24 rounded-[1.1rem] border border-white/80 bg-white/70 p-3 shadow-lg backdrop-blur"
                                >
                                    <div
                                        class="h-16 rounded-md bg-white shadow-inner"
                                    />
                                    <div
                                        class="mt-4 h-2 w-16 rounded-full bg-pink-500"
                                    />
                                    <div
                                        class="mt-2 h-2 w-10 rounded-full bg-pink-200"
                                    />
                                </div>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p
                                        class="text-xs font-semibold tracking-wide text-pink-600 uppercase"
                                    >
                                        {{ product.category }}
                                    </p>
                                    <h3
                                        class="mt-1 text-base font-semibold text-stone-950"
                                    >
                                        {{ product.name }}
                                    </h3>
                                </div>
                                <span
                                    class="rounded-md bg-pink-50 px-2 py-1 text-sm font-semibold text-pink-700"
                                    >{{ formatMoney(product.price) }}</span
                                >
                            </div>
                            <p
                                class="mt-3 min-h-12 text-sm leading-6 text-stone-600"
                            >
                                {{ product.description }}
                            </p>
                            <div class="mt-4 border-t border-pink-100 pt-4">
                                <Button
                                    v-if="$page.props.auth.user"
                                    class="h-10 w-full bg-pink-500 text-white hover:bg-pink-600"
                                    @click="addProduct(product)"
                                    ><ShoppingBag class="size-4" />Add to
                                    cart</Button
                                >
                                <Button
                                    v-else
                                    as-child
                                    class="h-10 w-full bg-pink-500 text-white hover:bg-pink-600"
                                    ><Link :href="login()"
                                        ><LockKeyhole class="size-4" />Log in to
                                        buy</Link
                                    ></Button
                                >
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div
                    class="rounded-lg border border-pink-100 bg-white p-5 shadow-sm"
                >
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-semibold text-stone-950">
                                Cart
                            </h2>
                            <p class="text-sm text-stone-500">
                                {{ cart.count }} items
                            </p>
                        </div>
                        <span
                            class="grid size-10 place-items-center rounded-md bg-pink-100 text-pink-600"
                            ><ShoppingBag class="size-5"
                        /></span>
                    </div>

                    <div v-if="$page.props.auth.user" class="grid gap-3">
                        <div
                            v-if="cart.items.length === 0"
                            class="rounded-md border border-dashed border-pink-200 bg-pink-50 p-5 text-sm text-stone-600"
                        >
                            Cart is empty.
                        </div>
                        <div
                            v-for="item in cart.items"
                            :key="item.id"
                            class="flex items-start justify-between gap-3 rounded-md border border-pink-100 bg-pink-50 p-3"
                        >
                            <div class="min-w-0">
                                <p
                                    class="truncate text-sm font-semibold text-stone-950"
                                >
                                    {{ item.name }}
                                </p>
                                <p class="mt-1 text-xs text-stone-500">
                                    {{ item.quantity }} x
                                    {{ formatMoney(item.price) }}
                                </p>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <span
                                    class="text-sm font-semibold text-stone-950"
                                    >{{ formatMoney(item.subtotal) }}</span
                                ><button
                                    type="button"
                                    class="grid size-8 place-items-center rounded-md text-pink-600 transition hover:bg-white"
                                    aria-label="Remove item"
                                    @click="removeProduct(item)"
                                >
                                    <Trash2 class="size-4" />
                                </button>
                            </div>
                        </div>
                        <div
                            class="mt-2 flex items-center justify-between border-t border-pink-100 pt-4"
                        >
                            <span class="text-sm font-medium text-stone-500"
                                >Subtotal</span
                            ><span
                                class="text-lg font-semibold text-stone-950"
                                >{{ formatMoney(cart.total) }}</span
                            >
                        </div>
                        <Button
                            class="h-11 bg-pink-500 text-white hover:bg-pink-600"
                            :disabled="cart.count === 0"
                            @click="checkoutCart"
                            ><ShoppingBag class="size-4" />Checkout</Button
                        >
                    </div>

                    <div v-else class="grid gap-3">
                        <div
                            class="rounded-md bg-pink-50 p-4 text-sm leading-6 text-stone-600 ring-1 ring-pink-100"
                        >
                            <LockKeyhole
                                class="mb-3 size-5 text-pink-500"
                            />Customer login is required before cart and
                            checkout actions.
                        </div>
                        <Button
                            as-child
                            class="bg-pink-500 text-white hover:bg-pink-600"
                            ><Link :href="login()"
                                ><LockKeyhole class="size-4" />Log in</Link
                            ></Button
                        >
                        <Button
                            as-child
                            variant="outline"
                            class="border-pink-200 text-pink-700 hover:bg-pink-50"
                            ><Link :href="register()"
                                ><ArrowRight class="size-4" />Create
                                account</Link
                            ></Button
                        >
                    </div>
                </div>
            </aside>
        </section>

        <footer class="border-t border-pink-100 bg-white">
            <div
                class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8"
            >
                <div
                    class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between"
                >
                    <Link :href="home()" class="flex items-center gap-3">
                        <span
                            class="grid size-10 place-items-center rounded-md bg-pink-500 text-white shadow-sm"
                            ><ShoppingBag class="size-5"
                        /></span>
                        <span class="leading-tight">
                            <span class="block text-base font-semibold"
                                >Habibty</span
                            >
                            <span class="block text-xs font-medium text-pink-600"
                                >Legazpi PH</span
                            >
                        </span>
                    </Link>

                    <nav
                        class="flex flex-wrap items-center gap-3"
                        aria-label="Social media"
                    >
                        <a
                            v-for="socialLink in socialLinks"
                            :key="socialLink.name"
                            :href="socialLink.href"
                            target="_blank"
                            rel="noreferrer"
                            class="inline-flex h-10 items-center gap-2 rounded-md border border-pink-200 bg-pink-50 px-3 text-sm font-semibold text-pink-700 transition hover:bg-pink-100"
                        >
                            <component :is="socialLink.icon" class="size-4" />
                            {{ socialLink.name }}
                        </a>
                    </nav>
                </div>

                <div
                    class="flex flex-col gap-3 border-t border-pink-100 pt-5 text-sm text-stone-500 sm:flex-row sm:items-center sm:justify-between"
                >
                    <p>
                        &copy; {{ currentYear }} Habibty Legazpi PH. All rights
                        reserved.
                    </p>
                    <p>
                        Kikay on a budget? Say less, Habibty. Trendy,
                        affordable, and authentic beauty finds.
                    </p>
                </div>
            </div>
        </footer>
    </main>
</template>

<style scoped>
.hero-waves {
    pointer-events: none;
    position: absolute;
    inset: auto -18% -1px -18%;
    height: min(30rem, 52vh);
    overflow: hidden;
}

.hero-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 100%;
    width: 130%;
    transform-origin: center bottom;
}

.hero-wave path {
    fill: #fce7f3;
}

.hero-wave-back {
    opacity: 0.58;
    animation: hero-wave-drift 18s ease-in-out infinite alternate;
}

.hero-wave-front {
    opacity: 0.78;
    animation: hero-wave-drift 14s ease-in-out infinite alternate-reverse;
}

@keyframes hero-wave-drift {
    from {
        transform: translateX(-4%) translateY(1.5rem) scaleX(1.02);
    }

    to {
        transform: translateX(2%) translateY(0) scaleX(1.08);
    }
}
.review-carousel {
    mask-image: linear-gradient(
        to right,
        transparent,
        black 6%,
        black 94%,
        transparent
    );
}

.review-carousel-track {
    animation: review-carousel-spin 42s linear infinite;
}

.review-carousel:hover .review-carousel-track,
.review-carousel:focus-within .review-carousel-track {
    animation-play-state: paused;
}

@keyframes review-carousel-spin {
    from {
        transform: translateX(0);
    }

    to {
        transform: translateX(-50%);
    }
}

@media (prefers-reduced-motion: reduce) {
    .review-carousel {
        overflow-x: auto;
        mask-image: none;
    }

    .review-carousel-track,
    .hero-wave {
        animation: none;
    }
}
</style>
