<script setup>
import LeftMenuItem from "./LeftMenuItem.vue";
import LeftMenuSectionItem from "./LeftMenuSectionItem.vue";
</script>
<template>
    <nav id="sidenav" class="fixed top-0 w-[55px] hover:w-[302px] h-full text-white font-bold text-base text-gray-500 z-20 bg-[#27272a] overflow-hidden hover:overflow-y-auto delay-300 duration-300 ease-in-out">
        <ul class="">
            <LeftMenuItem title="Home" :href="route('katas.index')" :active="$page.component === 'Home'"
                          description="Report home for your next assignment"
            >
                <img class="absolute w-9" src="/images/logo.svg" alt="">
            </LeftMenuItem>

            <LeftMenuSectionItem>Training</LeftMenuSectionItem>

            <LeftMenuItem title="Practice" :href="route('katas.index')" :active="$page.component === 'Katas/Index'"
                          description="Complete challenging <strong>Kata</strong> to earn honor and ranks. Re-train to hone technique"
            >
                <svg class="ml-1.5 absolute w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.477 7.694c-.395-.559-1.312-.795-1.869-.318L20.342 8.46c-3.71-1.833-7.42-3.663-11.129-5.497-1.651-.817-2.512 1.944-.884 2.748l4.011 1.98-2.83 5.437c-.019.039-.032.08-.05.12L2.053 9.843c-1.66-.762-2.537 1.931-.899 2.685l8.863 4.074.5 2.442a272 272 0 0 1-3.176-1.45c-1.643-.767-3.08 1.672-1.428 2.443 2.031.948 4.082 1.852 6.123 2.776 1.268.572 2.405-.733 2.115-1.708v-.02c-.353-1.726-.708-3.452-1.06-5.179.076-.093.152-.19.21-.3l3.08-5.918 3.405 1.68c.384.191.723.183 1.001.055.172-.036.337-.11.478-.23.631-.54 1.26-1.081 1.893-1.62.555-.477.767-1.242.32-1.879z"></path><path d="M16.482 5.579a2.303 2.303 0 1 0 1.145-4.462l-.016-.003a2.303 2.303 0 0 0-1.145 4.461l.016.004z"></path></svg>
            </LeftMenuItem>
            <LeftMenuItem title="Freestyle sparring" :href="route('test')" :active="$page.component === 'Katas/Freestyle'"
                          description="Take turns remixing and refactoring others code through <strong>Kumite</strong>"
            >
                <svg class="ml-1.5 absolute w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="m9.441 10.67-2.908 1.233-1.06-2.317 1.848-.05c.543 0 .986-.418 1.06-.96l.296-2.416C8.751 5.568 8.332 5 7.74 4.927a1.106 1.106 0 0 0-1.232.961l-.197 1.48-1.627.049a.786.786 0 0 0-.345.074l-2.268.813a.997.997 0 0 0-.64.616 1.02 1.02 0 0 0 .048.888l2.317 4.659-1.109 6.779c-.123.69.37 1.356 1.06 1.479.074 0 .148.025.222.025a1.27 1.27 0 0 0 1.257-1.085l1.134-6.902 6.113-2.589-2.193-.936a2.199 2.199 0 0 1-.838-.567zM4.574 4.136a2.243 2.243 0 1 1-4.07 1.887 2.243 2.243 0 0 1 4.07-1.887Z"></path><path d="m21.94 6.727-2.268-.814a.87.87 0 0 0-.345-.074l-1.652-.05-.197-1.478c-.074-.592-.641-1.011-1.233-.962a1.11 1.11 0 0 0-.961 1.233l.296 2.415c.073.543.517.937 1.06.962l1.848.05-1.06 2.317-5.768-2.441c-.666-.271-1.405.025-1.676.69-.271.666.024 1.405.69 1.676l6.952 2.959 1.134 6.902a1.27 1.27 0 0 0 1.257 1.085c.074 0 .148 0 .222-.025.69-.123 1.183-.789 1.06-1.48l-1.11-6.778 2.317-4.66c.148-.27.148-.616.05-.887a.946.946 0 0 0-.617-.64zm.465-5.268a2.243 2.243 0 1 1-1.888 4.07 2.243 2.243 0 0 1 1.888-4.07z"></path></svg>
            </LeftMenuItem>

            <LeftMenuSectionItem>Career</LeftMenuSectionItem>

            <LeftMenuItem title="Opportunities" :href="route('katas.index')" :active="$page.component === 'Katas/Opportunities'"
                          description="Find your next career challenge – powered by <strong>Qualified Jobs</strong>"
            >
                <svg class="ml-1.5 absolute w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </LeftMenuItem>

            <LeftMenuSectionItem>Commutnity</LeftMenuSectionItem>

            <LeftMenuItem title="Leaderboards" :href="route('katas.index')"
                          description="Achieve honor and move up the global leaderboards"
            >
                <svg class="ml-1.5 absolute w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path></svg>
            </LeftMenuItem>
            <LeftMenuItem title="Chat" :href="route('katas.index')"
                          description="Join our <strong>Discord</strong> server and chat with your fellow code warriors"
            >
                <svg class="ml-1.5 absolute w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M9.8147 10.0948c-.6792 0-1.2191.5921-1.2191 1.315 0 .7227.5486 1.3236 1.2191 1.3236.6793 0 1.2192-.6008 1.2192-1.3237.0174-.7228-.54-1.315-1.2192-1.315zm4.3715 0c-.6792 0-1.2191.5921-1.2191 1.315 0 .7227.5486 1.3236 1.2191 1.3236.6793 0 1.2192-.6008 1.2192-1.3237 0-.7228-.54-1.315-1.2192-1.315z"></path><path d="M20.0034.0539H3.9889C2.64.0637 1.5506 1.1594 1.5506 2.5096v.0092-.0004 16.1629c0 1.3533 1.0945 2.4508 2.4465 2.4558H17.539l-.627-2.212 1.524 1.4195 1.4455 1.3411 2.569 2.2642V2.5096c0-1.3532-1.0945-2.4508-2.4466-2.4557ZM15.388 15.6595l-.7838-.9667c.889-.2145 1.636-.7207 2.1527-1.4097l.007-.0097c-.4877.3222-.958.5486-1.376.6966-.9578.4145-2.0733.6556-3.2451.6556a8.4005 8.4005 0 0 1-1.6323-.1593l.0531.0088c-.9878-.2002-1.8648-.5165-2.6764-.9407l.0552.0263-.1132-.061-.0435-.0348c-.2177-.122-.3396-.209-.3396-.209s.5747.958 2.09 1.4108c-.3484.4528-.7925.9928-.7925.9928-2.6386-.0871-3.6488-1.8201-3.6488-1.8201 0-3.8404 1.7242-6.9668 1.7242-6.9668 1.7156-1.2801 3.3527-1.2453 3.3527-1.2453l.122.148c-2.151.6183-3.135 1.5676-3.135 1.5676s.2612-.148.6966-.3484c.7886-.3714 1.7038-.6386 2.6657-.7534l.0426-.0042.209-.0174c.3922-.056.8452-.088 1.3056-.088 1.7258 0 3.3467.449 4.7522 1.2367l-.0491-.0253s-.9493-.9057-2.9783-1.5414l.1742-.1916s1.6285-.0348 3.3527 1.2628c0 0 1.7155 3.1176 1.7155 6.9667 0 0-1.0101 1.7243-3.6574 1.8114z"></path></svg>
            </LeftMenuItem>
            <LeftMenuItem title="Discussions" :href="route('katas.index')"
                          description="View our <strong>Github Discussions</strong> board to discuss general Codewars topics"
            >
                <svg class="ml-1.5 absolute w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path></svg>
            </LeftMenuItem>

            <LeftMenuSectionItem>About</LeftMenuSectionItem>

            <LeftMenuItem title="Docs" :href="route('katas.index')"
                          description="Learn about all of the different aspects of Codewars"
            >
                <svg class="ml-1.5 absolute w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path></svg>
            </LeftMenuItem>
        </ul>
    </nav>
</template>
