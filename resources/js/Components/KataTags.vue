<script setup>
import KataTag from "./KataTag.vue";

defineProps({
    tags: Object,
    isButton:Boolean,
});
</script>

<template>
    <div class="mt-1.5"><span>
        <i class="icon-moon-tag mr-1"></i></span>
        <KataTag v-for="tag in tags" :tag="tag" :isButton="isButton"></KataTag>
    </div>
</template>
