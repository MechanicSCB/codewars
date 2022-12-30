<script setup>
import Rank from "../../Components/Rank.vue";
let props = defineProps({
    member: Object,
});

</script>
<template>
    <div class="max-w-[1024px] mx-auto">
        <section class="p-4 bg-ui-section rounded-lg w-full flex mb-4">
            <div class="mr-4">
                <a :href="'/users/'+ member.id"><img class="w-[75px] min-w-[75px] rounded" :src="member.profile_photo_url"></a>
            </div>
            <div class="w-full">
                <div class="inline-block mb-1">
                    <a :href="'/users/'+ member.id" class="h-10 flex items-center bg-ui-accent pl-2">
                        <Rank rank="4"/>
                        <p class="text-xl pb-1 mr-40">{{ member.name }}</p>
                        <p class="pl-6 flex bg-white dark:bg-black h-full items-center pr-16">{{ member.id }}</p>
                    </a>
                </div>
                <div class="text-sm text-ui-link-text flex pr-36 mb-3">
                    <div class="mr-8">
                        <div class="stat"><b>Name: </b>Unknown</div>
                        <div class="stat"><b>Clan: </b>Unknown</div>
                    </div>
                    <div class="mr-24">
                        <div class="stat"><b>Member Since: </b>{{ member.email_verified_at ?? member.created_at }}</div>
                        <div class="stat"><b>Last Seen: </b>{{ member.email_verified_at ?? member.updated_at }}</div>
                        <div class="stat"><b>Profiles: </b>
                            <ul class=""></ul>
                        </div>
                    </div>
                    <div class="">
                        <div class="stat"><b>Following: </b>0</div>
                        <div class="stat"><b>Followers: </b>0</div>
                        <div class="stat"><b>Allies: </b>0</div>
                    </div>
                </div>
                <div class="">
                    <a class="btn h-6 normal-case inline-flex items-center" :href="'/users/'+ member.id + '/badges'">View Profile Badges</a>
                </div>
            </div>
        </section>

        <section class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 bg-ui-accent rounded-lg mb-4">
            <div class="w-full md:w-8/12">
                <div class="h-full flex mb-0 p-6">
                    <figure class="float-left pt-1 mr-3 min-w-[70px]"><i class="icon-moon-share text-5xl"></i></figure>
                    <figcaption class="block w-full">
                        <h5 class="text-ui-text-hc my-0 font-bold">Earn extra honor and gain new allies!</h5>
                        <div class="text-sm">Honor is earned for each new codewarrior who joins.</div>
                        <a class="btn mt-[15px] bg-white dark:bg-black" id="tell_me_more_referrals">Learn more</a></figcaption>
                </div>
                <div class="hidden p-4 bg-indigo-300 dark:bg-indigo-700 rounded-lg" id="referrals"><p class="mb-2">
                    Use the referral URL to invite your friends to Codewars. Once they complete the initiation and
                    confirm their account, you will be awarded honor.</p>
                    <div class="panel p-10px mb-15px bg-ui-section"><b>www.codewars.com/r/Cuetnw</b></div>
                </div>
            </div>
            <div class="w-full md:w-4/12 bg-ui-section md:min-w-[320px] rounded-b-lg md:rounded-r-lg md:rounded-b-none p-4">
                <a class="flex" rel="nofollow noopener external" target="_blank" href="https://www.codewars.com">
                    <img alt="Ad" class="w-14 h-14 mr-3" src="/images/logo.svg">
                    <div class="">
                        <div class="text-ui-link-hover text-sm font-bold">7 Resources to Level Up with Python for Data Science</div>
                        <div class="hover:text-ui-link-hover text-xs"><span>7 resources to help boost your python based data science knowledge.</span>
                            <!--v-if--></div>
                    </div>
                </a>
                <a class="cw-ad__callout" target="carbon" href="http://www.codewars.com/blog"></a>
            </div>
        </section>

        <section>

        </section>
    </div>
</template>
