<script setup>
let props = defineProps({
    icon: String,
    tippy: String,
});
</script>
<template>
    <li class="block w-[100px] h-20 pt-2 cursor-pointer text-center mr-2.5 mb-2 transition
                 text-gray-600 bg-ui-accent hover:text-ui-link-hover hover:bg-transparent
                 dark:text-gray-400 dark:hover:text-ui-link-hover dark:hover:bg-ui-section"
               :data-tippy-content="tippy"
               data-tippy-placement="bottom"
        >
            <i class="text-3xl" :class="'icon-moon-' + icon"></i>
            <p><slot/></p>
    </li>
</template>


