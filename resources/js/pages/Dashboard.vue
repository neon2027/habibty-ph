<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Clock3, Heart, ReceiptText, ShoppingBag, Sparkles } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { dashboard, home } from '@/routes';

type OrderItem = {
    id: number;
    product_name: string;
    sku: string;
    quantity: number;
    unit_price: number;
    subtotal: number;
};

type Order = {
    id: number;
    order_number: string;
    status: string;
    total: number;
    placed_at: string;
    items: OrderItem[];
};

defineProps<{
    purchaseSummary: {
        orders: number;
        items: number;
        spent: number;
    };
    orders: Order[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const formatPeso = (amount: number) =>
    new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
    }).format(amount / 100);
</script>

<template>
    <Head title="Dashboard" />

    <div
        class="flex h-full flex-1 flex-col gap-6 overflow-x-auto bg-pink-50 p-4"
    >
        <section
            class="rounded-lg border border-pink-100 bg-white p-6 shadow-sm"
        >
            <div
                class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between"
            >
                <div>
                    <p
                        class="inline-flex items-center gap-2 rounded-md bg-pink-100 px-3 py-1 text-sm font-semibold text-pink-700"
                    >
                        <Sparkles class="size-4" />
                        Customer account
                    </p>
                    <h1 class="mt-4 text-3xl font-semibold text-stone-950">
                        Habibty dashboard
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-600">
                        Continue shopping and review your purchase history from
                        your signed-in customer account.
                    </p>
                </div>

                <Button
                    as-child
                    class="bg-pink-500 text-white hover:bg-pink-600"
                >
                    <Link :href="home()">
                        <ShoppingBag class="size-4" />
                        Open store
                    </Link>
                </Button>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <div
                class="rounded-lg border border-pink-100 bg-white p-5 shadow-sm"
            >
                <span
                    class="grid size-10 place-items-center rounded-md bg-pink-100 text-pink-600"
                >
                    <ReceiptText class="size-5" />
                </span>
                <p class="mt-4 text-sm font-medium text-stone-500">
                    Total orders
                </p>
                <p class="mt-1 text-2xl font-semibold text-stone-950">
                    {{ purchaseSummary.orders }}
                </p>
            </div>

            <div
                class="rounded-lg border border-pink-100 bg-white p-5 shadow-sm"
            >
                <span
                    class="grid size-10 place-items-center rounded-md bg-pink-100 text-pink-600"
                >
                    <ShoppingBag class="size-5" />
                </span>
                <p class="mt-4 text-sm font-medium text-stone-500">
                    Items bought
                </p>
                <p class="mt-1 text-2xl font-semibold text-stone-950">
                    {{ purchaseSummary.items }}
                </p>
            </div>

            <div
                class="rounded-lg border border-pink-100 bg-white p-5 shadow-sm"
            >
                <span
                    class="grid size-10 place-items-center rounded-md bg-pink-100 text-pink-600"
                >
                    <Heart class="size-5" />
                </span>
                <p class="mt-4 text-sm font-medium text-stone-500">
                    Total spent
                </p>
                <p class="mt-1 text-2xl font-semibold text-stone-950">
                    {{ formatPeso(purchaseSummary.spent) }}
                </p>
            </div>
        </section>

        <section
            class="rounded-lg border border-pink-100 bg-white p-6 shadow-sm"
        >
            <div
                class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h2 class="text-lg font-semibold text-stone-950">
                        Purchase history
                    </h2>
                    <p class="text-sm text-stone-500">
                        Your latest orders with purchase date and time.
                    </p>
                </div>
                <Clock3 class="size-5 text-pink-500" />
            </div>

            <div v-if="orders.length" class="mt-5 space-y-4">
                <article
                    v-for="order in orders"
                    :key="order.id"
                    class="rounded-lg border border-pink-100 p-4"
                >
                    <div
                        class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"
                    >
                        <div>
                            <h3 class="font-semibold text-stone-950">
                                {{ order.order_number }}
                            </h3>
                            <p class="text-sm text-stone-500">
                                {{ order.placed_at }}
                            </p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p
                                class="text-sm font-medium text-pink-600 capitalize"
                            >
                                {{ order.status }}
                            </p>
                            <p class="font-semibold text-stone-950">
                                {{ formatPeso(order.total) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 divide-y divide-pink-50">
                        <div
                            v-for="item in order.items"
                            :key="item.id"
                            class="flex items-center justify-between gap-4 py-2 text-sm"
                        >
                            <div>
                                <p class="font-medium text-stone-800">
                                    {{ item.product_name }}
                                </p>
                                <p class="text-stone-500">
                                    {{ item.sku }} x {{ item.quantity }}
                                </p>
                            </div>
                            <p class="font-semibold text-stone-900">
                                {{ formatPeso(item.subtotal) }}
                            </p>
                        </div>
                    </div>
                </article>
            </div>

            <div
                v-else
                class="mt-5 rounded-lg border border-dashed border-pink-200 bg-pink-50 p-6 text-center"
            >
                <p class="font-semibold text-stone-950">No purchases yet</p>
                <p class="mt-1 text-sm text-stone-600">
                    Your completed checkout orders will appear here with their
                    time and total.
                </p>
            </div>
        </section>
    </div>
</template>
