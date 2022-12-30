<script setup>
defineProps({
    title: String,
    description: String,
    href: String,
    active: Boolean,
});
</script>
<template>
    <li class="min-w-[320px]">
        <Link class="flex items-center hover:text-ui-link-hover hover:bg-white/10 w-[302px] h-16 px-2" :class="active ? 'text-white' : 'text-[#d1d1d1]'" :href="href">
            <slot/>
            <div>
                <div class="ml-12 text-base h-6 overflow-hidden">{{ title }}</div>
                <div class="ml-12 text-xs font-normal leading-4 text-[#999]" v-html="description"></div>
            </div>
        </Link>
    </li>
</template>
