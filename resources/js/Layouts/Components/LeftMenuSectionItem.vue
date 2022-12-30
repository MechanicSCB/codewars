<script setup>
defineProps({
    title: String,
});
</script>
<template>
    <li class="sidenav-section mt-4 ml-3 h-2 uppercase text-xs tracking-widest text-[#999] mb-2.5">
        <svg class="pt-1 w-[7.5px] ml-2.5" fill="#555" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="50"/>
        </svg>
        <span class="hidden"><slot/></span>
    </li>
</template>
<style>
#sidenav:hover .sidenav-section svg{
    display: none;
}
#sidenav:hover .sidenav-section span{
    display: block;
}
</style>
