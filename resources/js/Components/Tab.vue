<script setup>
import { inject } from 'vue';

defineProps(['title']);

const selectedTitle = inject('selectedTitle');
</script>

<template>
    <div class="tab-content" v-show="title === selectedTitle" >
        <slot></slot>
    </div>
</template>
