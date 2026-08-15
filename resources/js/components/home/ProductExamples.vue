<script setup>
import { aliveProducts } from '../../data/aliveProducts';
</script>

<template>
    <section class="section alive-products-section motion-reveal">
        <div class="shell">
            <div class="section-heading">
                <div><p class="eyebrow">06 — Produktu piemēri</p><h2>Suvenīri, kas<br>atdzīvojas kamerā</h2></div>
                <a href="/catalog">Atvērt katalogu <span>→</span></a>
            </div>
            <div class="alive-product-grid">
                <article v-for="(product, index) in aliveProducts" :key="product.title" class="alive-product-card tilt-card" :class="`alive-${product.tone}`">
                    <span class="alive-product-number">{{ String(index + 1).padStart(2, '0') }}</span>
                    <div class="alive-product-art" aria-hidden="true">
                        <div class="alive-product-qr"><i v-for="cell in 9" :key="cell"></i></div>
                    </div>
                    <div>
                        <h3>{{ product.title }}</h3>
                        <p>{{ product.detail }}</p>
                    </div>
                    <strong>{{ product.price }}</strong>
                </article>
            </div>
        </div>
    </section>
</template>
