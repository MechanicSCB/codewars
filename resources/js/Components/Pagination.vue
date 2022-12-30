<script>
import {Link} from '@inertiajs/inertia-vue3';

export default {
    props: {
        links: Array,
        preserveScroll: false,
    },
    components: {
        Link,
    },
};
</script>
<template>
    <div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="(link, index) in links"
            :href="link.url"
            v-html="link.label"
            class="px-3 py-2 border border-gray-300 border-l-0 text-sm text-[#007bff] hover:text-[#0056b3] hover:bg-[#e9ecef]"
            :class="{
                '!text-gray-500' : ! link.url,
                'bg-[#007bff] !text-white hover:!bg-[#007bff] hover:!text-white border border-[#007bff]' : link.active,
                '!border-l rounded-l' : index===0,
                'rounded-r' : index===links.length - 1,
            }"
            :preserve-scroll="preserveScroll"
            preserve-state
        />
    </div>
</template>
