<script>
import { ref, provide } from 'vue';

export default {
    setup(props, { slots }){
        const tabTitles = ref(slots.default().map((tab) => tab.props.title));
        const selectedTitle = ref(tabTitles.value[0]);

        provide("selectedTitle", selectedTitle);

        return { selectedTitle, tabTitles };
    }
}
</script>

<template>
    <div class="tabs">
        <ul class="flex items-center mb-2 text-sm">
            <li v-for="title in tabTitles" :key="title" @click="selectedTitle = title"
                class="block px-4 py-2 rounded-lg cursor-pointer" :class="{ 'bg-ui-section': title === selectedTitle }"
            >
                <div v-html="title"></div>
            </li>
        </ul>
        <slot/>
    </div>
</template>

