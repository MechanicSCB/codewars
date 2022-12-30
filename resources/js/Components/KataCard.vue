<script setup>
import Rank from "./Rank.vue";
import KataInfo from "./KataInfo.vue";
import KataTags from "./KataTags.vue";
import KataLangIcons from "./KataLangIcons.vue";

defineProps({
    kata: Object,
});
</script>

<template>
    <div class="p-4 rounded-lg shadow-md bg-ui-section">
        <div class="flex flex-col md:flex-row">
            <div class="my-2 px-1 w-full md:w-6/12">
                <div class="text-ui-link-text flex items-center pr-4">
                    <Rank :rank="kata.rank" class="inline-block mr-1"/>
                    <Link class="text-base font-bold hover:text-ui-link-hover" :href="'katas/' + kata.slug">{{ kata.name }}</Link>
                    <svg v-if="kata.verified" class="ml-1 w-4 min-w-[16px] inline mb-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"></path>
                    </svg>
                </div>

                <KataInfo :kata="kata"></KataInfo>
                <KataTags :tags="kata.tags" :isButton="true"></KataTags>
            </div>
            <div class="px-0 w-full md:w-6/12">
                <KataLangIcons :kata="kata"></KataLangIcons>
            </div>
        </div>
        <div class="flex items-center">
            <div class="text-xs">{{ kata.id }}</div>
            <Link class="ml-2 text-xss" :href="route('katas.edit', kata)"><button class="btn py-1 btn-gray2">Edit</button></Link>
        </div>
    </div>
</template>
