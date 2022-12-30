<script setup>
import {onMounted, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import Rank from "../../Components/Rank.vue";

let profileMenuShow = ref(false);

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

let handleScroll = function (event) {
    if (window.pageYOffset > 0) {
        document.body.classList.add("scrolling");
    } else {
        document.body.classList.remove("scrolling");
    }
}
</script>

<template>
    <header id="head-menu" class="fixed top-0 z-10 right-0 h-16 rounded-bl-lg">
        <ul class="list-none flex text-base space-x-6">
            <!-- THEME SWITCHER -->
            <li class="float-left relative h-full text-center py-4 pl-6 pr-2 leading-8 hidden dark:inline-block">
                <Link class="block w-6"  onclick="localStorage.setItem('theme','light'); setTheme(); return false;">
                    <svg class="hidden dark:block" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </Link>
            </li>
            <li class="float-left relative h-full text-center py-4 pr-2 leading-8 dark:hidden inline-block">
                <Link class="block w-6" onclick="localStorage.setItem('theme','dark'); setTheme(); return false;">
                    <svg class="block dark:hidden" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd"></path></svg>
                </Link>
            </li>
            <!-- \\ THEME SWITCHER -->

            <div v-if="! $page.props.user">
                <li class="float-left relative h-full text-center p-4 leading-8"><span><Link class="" data-turbolinks="false" :href="route('login')">Log In</Link></span></li>
                <li class="float-left relative h-full text-center p-4 leading-8"><span><Link class="btn btn-red" :href="route('register')">Sign Up</Link></span></li>
            </div>
            <div v-else class="flex items-center space-x-10 h-16 pr-6">
                <li><a href=""><i class="icon-moon-bookmark text-2xl"></i></a></li>
                <li><a href=""><i class="icon-moon-bell text-2xl -mr-1"></i></a></li>
                <li @mouseover="profileMenuShow=true" @mouseout="profileMenuShow=false" class="">
                    <div href="/users/metronomicus7" class="flex space-x-2 items-center">
                        <div>
                            <img class="rounded w-9" title="" :alt="$page.props.user.name" :src="$page.props.user.profile_photo_url"></div>
                            <Rank class="hidden sm:block" rank="4"/>
                            <div class="hidden sm:block">762
                        </div>
                    </div>
                    <div v-show="profileMenuShow" class="profile_menu absolute right-0 sm:right-auto shadow-lg bg-white dark:bg-black w-full">
                        <ul class="text-sm py-4 space-y-4 px-4">
                            <li>
                                <Link :href="'/users/' + $page.props.user.id">
                                    <i class="icon-moon-user "></i>
                                    View Profile
                                </Link>
                            </li>
                            <li class="border-t pt-2">
                                <Link href="#">
                                    <i class="icon-moon-settings "></i>
                                    Account Settings
                                </Link>
                            </li>
                            <li>
                                <Link href="#">
                                    <i class="icon-moon-settings "></i>
                                    Training Setup
                                </Link>
                            </li>
                            <li class="border-t pt-2">
                                <a data-turbolinks="false" href="#">
                                    <i class="icon-moon-red-badge text-red-700"></i>
                                    Upgrade to Red
                                </a>
                            </li>
                            <li class="border-t pt-2">
                                <Link data-turbolinks="false" href="/katas/create">
                                    <i
                                        class="icon-moon-begin "></i>
                                    New Kata
                                </Link>
                            </li>
                            <li>
                                <Link href="#">
                                    <i class="icon-moon-begin "></i>
                                    New Kumite
                                </Link>
                            </li>
                            <li class="border-t pt-2">
                                <Link :href="route('logout')" method="post">
                                    <i class="icon-moon-power "></i>
                                    Sign out
                                </Link>
                            </li>
                        </ul>
                    </div>
                </li>
            </div>
        </ul>
    </header>
</template>
<style>
body.scrolling #head-menu,
#head-menu:hover{
    background-color: var(--color-ui-header);
    transition: background-color .3s ease-in-out;
    box-shadow: 0px 0px 1px 1px rgba(0,0,0,0.1);
}

.profile_menu li:hover{
    color: rgb(82, 82, 91);
}

.dark .profile_menu li:hover{
    color: rgb(192, 192, 192);
}
</style>
