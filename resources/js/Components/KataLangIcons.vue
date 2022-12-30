<script setup>
import LangIcon from "./LangIcon.vue";

defineProps({
    kata: Object,
});
</script>

<template>
    <ul class="mt-1 flex flex-wrap justify-end space-x-2 mb-0">
        <li v-for="lang in kata.langs" class="items-center text-[#555] hover:text-ui-link-hover dark:text-ui-link-text">
            <Link :data-language="lang" :href="'/katas/' + kata.slug +'/train?languages=' + lang.slug">
                <LangIcon :lang="lang"
                          class="w-[50px] h-[50px] mb-3 text-2xl bg-gray-200 dark:bg-black/30 rounded flex items-center justify-center"
                          :class="{
                                'text-red-500': $page.props.langs.map(v=>v.slug).includes(lang.slug),
                                'text-green-600': ($page.props.katasPassedLangs[kata.id] ?? []).includes(lang.id),
                            }"></LangIcon>
            </Link>
        </li>
    </ul>
</template>
