<script>
import LeftMenu from "./Components/LeftMenu.vue";
import HeadMenu from "./Components/HeadMenu.vue";
import Flash from "./Components/Flash.vue";

export default {
    name: "MainLayout",
    components: {HeadMenu, LeftMenu, Flash},
}
</script>

<template>
    <LeftMenu/>
    <div class="ml-14">
        <h1 class="absolute top-0 ml-4 text-sm p-3.5 bg-ui-section rounded-b-lg">Kata Library</h1>

        <HeadMenu/>
        <main class="px-[15px]">
            <Flash/>
            <slot/>
        </main>
    </div>
</template>

