<script setup>
defineProps({
    lang: Object,
});
</script>

<template>
    <div v-tippy="{ content:lang.name }" >
        <i :class="'-mb-1 icon-moon-'+ lang.slug"></i>
    </div>
</template>
