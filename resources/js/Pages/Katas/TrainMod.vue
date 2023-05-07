<script setup>
import Rank from "../../Components/Rank.vue";
import KataInfo from "../../Components/KataInfo.vue";
import KataTags from "../../Components/KataTags.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Tabs from "../../Components/Tabs.vue";
import Tab from "../../Components/Tab.vue";
import Highlight from "../../Components/Highlight.vue";
import {Inertia} from '@inertiajs/inertia'

import {ref, onMounted} from 'vue'

let solutionTextEditor;

let props = defineProps({
    kata: Object,
    langs: Object,
    initLang: String,
    preload: String,
    attemptsResults: Object,
});

let form = useForm({
    lang: props.initLang,
    solution: '',
    attemptMode: 'sample',
});

let sampleForm = useForm({
    args_list: props.kata.sample?.args_list,
    expected_list: props.kata.sample?.expected_list,
    function_names: props.kata.sample?.function_names,
});

// console.log(JSON.parse(props.kata.random_test?.scheme));

let randomForm = useForm({
    scheme: props.kata.random_test?.scheme,
});

let functionForm = useForm({
    only_lang: true,
    lang: '',
    from: '',
    to: '',
});

let submit = (mode) => {
    myTabs.value.selectedTitle = 'Output';

    form.solution = solutionTextEditor.getValue();
    form.attemptMode = mode;
    form.post(route('katas.attempt', props.kata));
};

let saveSample = () => {
    sampleForm.put(route('samples.update', props.kata.sample));
};

let saveRandom = () => {
    randomForm.put(route('random-tests.update', props.kata.random_test));
};

let renameFunction = () => {
    functionForm.lang = functionForm.only_lang ? form.lang : null;

    functionForm.put(route('solutions.rename-function', props.kata));
};

let checkSolution = (solution, mode = 'sample') => {
    Inertia.post(route('solutions.check', solution), {'mode': mode});
};

let massCheckSolution = (lang=null, mode = 'sample') => {
    Inertia.post(route('solutions.mass-check', props.kata), {'lang':lang, 'mode': mode});
};

let copyToClipboard = (solution) => {
    try {
        // navigator clipboard api needs a secure context (https)
        if (navigator.clipboard && window.isSecureContext) {
            // navigator clipboard api method'
            return navigator.clipboard.writeText(solution);
        } else {
            // text area method
            let textArea = document.createElement("textarea");
            textArea.value = solution;
            // make the textarea out of viewport
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            return new Promise((res, rej) => {
                // here the magic happens
                document.execCommand('copy') ? res() : rej();
                textArea.remove();
            });
        }
    } catch (e) {
        throw e;
    }
};

let selectLang = (event) => {
    form.lang = event.target.value;
};

const myTabs = ref(null);

onMounted(() => {
    // myTabs.value.selectedTitle = props.activeTab ?? 'Instructions';

    var element = document.getElementById('checkingSolution');

    solutionTextEditor = CodeMirror.fromTextArea(element, {
        lineNumbers: true,
        matchBrackets: true,
        mode: element.getAttribute('mode') ?? 'javascript',
        indentUnit: 4,
    });
});

function hideOtherLangsCode(html){
    html = html.replace(/<pre><code class="language-/g,'<pre class="hidden"><code class="language-');
    html = html.replace('<pre class="hidden"><code class="language-' + form.lang, '<pre><code class="language-' + form.lang);

    return html;
}

</script>

<template>
    <h1 name="Instructions"></h1>
    <!--  KATA HEADER-INFO  -->
    <div class="" data-title="{{ kata.name }}" id="{{ kata.id }}">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center px-2 md:px-4 py-2 mb-0">
            <div class="my-2 px-1 w-full md:w-5/12">
                <div class="flex items-center space-x-2">
                    <Rank :rank="kata.rank"/>
                    <h4 class="font-bold">{{ kata.name }}</h4>
                </div>
                <KataInfo :kata="kata"/>
            </div>
            <div class="w-full md:w-7/12">
                <div class="flex flex-col sm:flex-row sm:justify-around sm:items-center px-8 md:px-0">
                    <div class="w-full sm:w-1/3">
                        <select @change="selectLang" name="lang" id="lang_select" class="select-gray">
                            <option v-for="lang in langs"
                                    :key="lang.id"
                                    :value="lang.slug"
                                    :class="{'font-semibold text-red-700':lang.has_solution, '!text-green-700':lang.has_passed_solution }"
                                    :selected="lang.slug === form.lang"
                            >{{ lang.name }}
                            </option>
                        </select>
                    </div>
                    <div class="w-full sm:w-2/3 py-2 md:py-0">
                        <div class="text-center md:text-right whitespace-nowrap">
                            <button @click="submit('sample')" class="btn btn-blue"><i class="icon-moon-play "> </i>Test
                            </button>
                            <button @click="submit('attempt')" class="btn btn-blue ml-1 sm:ml-2"><i
                                class="icon-moon-play "> </i>Attempt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  KATA DESCRIPTION, INPUT SOLUTION  -->
    <div class="md:flex md:flex-row md:space-x-4">
        <!-- LEFT-BLOCK (description/output/past solution) -->
        <div class="w-full md:w-5/12">
            <Tabs ref="myTabs">
                <Tab id="instructions_tab" title="Instructions" name="Instructions" :selected="true">
                    <div class="w-full pt-1 p-5 mb-5 bg-ui-section rounded-lg">
                        <Highlight class="description" :content="hideOtherLangsCode(kata.description)"></Highlight>
                        <hr class="mt-8">
                        <KataTags :tags="kata.tags" class="!mt-4"/>
                    </div>
                </Tab>
                <Tab id="output_tab" title="Output" name="Output">
                    <div class="bg-ui-section rounded-lg min-h-[100px]">
                        <div v-if="attemptsResults.items" class="border-2 p-4 rounded-lg"
                             :class="attemptsResults.failed ? 'border-red-500' : 'border-green-500'">
                            <div
                                class="tracking-wider text-xs flex items-center border-0 border-b border-solid border-black/10 dark:border-white/20 pt-2">
                                <span>Time:&nbsp;{{ attemptsResults.time ?? 'n/a' }} </span>
                                <span class="ml-2 p-1 cursor-pointer rounded"
                                      :class="attemptsResults.passed ? 'text-green-500' : ''">Passed:&nbsp;{{
                                        attemptsResults.passed ?? 'n/a'
                                    }}</span>
                                <span class="ml-1 p-1 cursor-pointer rounded"
                                      :class="attemptsResults.failed ? 'text-red-500' : ''">Failed:&nbsp;{{
                                        attemptsResults.failed ?? 'n/a'
                                    }}</span>
                                <span v-if="attemptsResults.failed" class="ml-1 p-1 errors nowrap text-red-500">Exit Code: 1</span>
                            </div>
                            <div class="mt-4">
                                <h2 class="border-l-4 pl-2.5 text-sm font-bold mb-4"
                                    :class="attemptsResults.failed ? 'border-red-500' : 'border-green-500'">Test
                                    Results:</h2>
                                <div class="text-xs text-green-500 overflow-x-auto mb-1"
                                     v-for="passed in attemptsResults.items.passed">
                                    <div class="block">{{ passed.function }}</div>
                                    <div class="flex mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                            <path
                                                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"></path>
                                        </svg>
                                        <pre class="ml-1.5" v-html="passed.result"></pre>
                                    </div>
                                </div>
                                <div class="text-xs text-red-500 overflow-x-auto mb-1"
                                     v-for="failed in attemptsResults.items.failed">
                                    <div class="block">{{ failed.function }}</div>
                                    <div class="flex mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             aria-hidden="true" class="w-4 h-4 grow-0 shrink-0 basis-4">
                                            <path fill-rule="evenodd"
                                                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                        <pre class="ml-1.5" v-html="failed.result"></pre>
                                    </div>
                                </div>
                                <!--                                <div v-else class="flex text-green-500 text-sm">-->
                                <!--                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-4 h-4 grow-0 shrink-0 basis-4"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>-->
                                <!--                                    <span class="ml-1.5">Test Passed</span>-->
                                <!--                                </div>-->
                            </div>
                        </div>

                        <div v-else class="p-4">Your results will be shown here.</div>
                    </div>
                </Tab>
                <Tab id="past_solutions_tab" title="Past Solutions" name="Past Solutions">
                    <button @click="massCheckSolution(form.lang)"
                            class="text-xs rounded px-2 py-1 btn-green mb-2"
                    >check {{ form.lang.toUpperCase() }}</button>
                    <button @click="massCheckSolution(form.lang, 'attempt')"
                            class="ml-2 text-xs rounded px-2 py-1 btn-green mb-2"
                    >attempt {{ form.lang.toUpperCase() }}</button>

                    <div v-for="solution in kata.solutions" v-show="solution.lang.slug === form.lang">
                        <div class="-ml-[100px] relative float-right">
                            <button @click="checkSolution(solution)"
                                    class="text-xxs font-bold rounded px-1 py-0.5 btn-gray2 mb-1"
                            >Sample</button>
                            <button @click="checkSolution(solution, 'attempt')"
                                    class="ml-1 text-xxs font-bold rounded px-1 py-0.5 btn-gray2 mb-1"
                            >Attempt</button>
                        </div>
                        <Highlight @click="copyToClipboard(solution.body)"
                                   class="text-xs p-2 mb-2 bg-ui-section overflow-x-auto max-h-[150px] overflow-y-auto"
                                   :class="'language-' + solution.lang.slug
                                    + (solution.is_control ? ' border-4 border-l-green-700' : '')
                                    + (solution.status==='sample_passed' ? ' bg-green-100' : '')
                                    + (solution.status==='sample_semi_passed' ? ' bg-yellow-100' : '')
                                    + (solution.status==='sample_failed' ? ' bg-red-200' : '')
                                    "
                                   :content="'<pre>' + solution.body + '</pre>'"/>
                    </div>
                </Tab>
            </Tabs>
        </div>

        <!-- RIGHT-BLOCK (input solution) -->
        <div class="w-full md:w-7/12">
            <!-- input solution -->
            <div class="p-2 rounded-t-lg bg-ui-section">
                <div class="text-sm font-bold">Solution {{ kata.id }}</div>
                <form @submit.prevent="submit">
                    <textarea v-model="form.solution"
                              class="w-full h-96"
                              name="solution"
                              id="checkingSolution"
                              rows="10"
                    >Solution text</textarea>
                </form>
            </div>

            <!-- Additional forms -->
            <div class="mb-8">
                <!-- input sample -->
                <h2>Sample</h2>
                <form @submit.prevent="saveSample">
                    <textarea v-model="sampleForm.args_list" class="w-full" rows="1"/>
                    <textarea v-model="sampleForm.expected_list" class="w-full" rows="1"/>
                    <textarea v-model="sampleForm.function_names" class="w-full" rows="1"/>
                    <button>Save</button>
                </form>
                <!-- input random args -->
                <h2>Random</h2>
                <form @submit.prevent="saveRandom">
                    <textarea v-model="randomForm.scheme" class="w-full" rows="1"/>

                    <button>Save</button>
                </form>

                <!-- rename function -->
                <h2 class="mt-2">Rename function</h2>
                <form @submit.prevent="renameFunction">
                    <label class="mb-2">only lang <input type="checkbox" v-model="functionForm.only_lang"></label>

                    <input class="block mb-2" v-model="functionForm.from">
                    <input v-model="functionForm.to">
                    <button>Rename</button>
                </form>

            </div>
        </div>

    </div>
</template>
