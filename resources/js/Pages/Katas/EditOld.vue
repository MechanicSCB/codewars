<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import KataCategoryCheckbox from "../../Components/KataCategoryCheckbox.vue"
import Tabs from "../../Components/Tabs.vue";
import Tab from "../../Components/Tab.vue";
import {onMounted, ref} from "vue";
import VueMultiselect from 'vue-multiselect'
import AirSummernote from "../../Components/AirSummernote.vue";

let props = defineProps({
    kata: Object,
    allTags: Object,
});

let form = useForm({
    name: props.kata.name,
    category: props.kata.category,
    rank: props.kata.rank,
    tags: props.kata.tags,
    description: props.kata.description,
    // solution: props.kata.solutions[0].body ?? '',
    solution: '',
});

let submit = () => {
    form.solution = solutionTextEditor.getValue();
    form.put(route('katas.update', props.kata));
};

let checkCategory = (value) => {
    form.category = value;
};

function summernoteSubmit(data, id) {
    form[id] = data;
}

const descriptionTabs = ref(null);
const solutionsTabs = ref(null);
const testCasesTabs = ref(null);

let solutionTextEditor;
let initialSolutionTextEditor;
let preloadedTextEditor;
let testCasesTextEditor;
let exampleTestCasesTextEditor;

onMounted(() => {
    // solutionsTabs.value.selectedTitle = 'Initial Solution';
    let element = document.getElementById('complete_solution');

    var editorOptions = {
        //lineNumbers: true,
        matchBrackets: true,
        mode: element.getAttribute('mode') ?? 'javascript',
        indentUnit: 4,
    };

    solutionTextEditor = CodeMirror.fromTextArea(element, editorOptions);
    initialSolutionTextEditor = CodeMirror.fromTextArea(document.getElementById('initial_solution'), editorOptions);
    preloadedTextEditor = CodeMirror.fromTextArea(document.getElementById('preloaded'), editorOptions);
    testCasesTextEditor = CodeMirror.fromTextArea(document.getElementById('test_cases'), editorOptions);
    exampleTestCasesTextEditor = CodeMirror.fromTextArea(document.getElementById('example_test_cases'), editorOptions);
});
</script>

<template>
    <Head :title="'Edit kata: ' + kata.name"/>

    <form @submit.prevent="submit">
        <!--  NAV BAR   -->
        <ul class="flex bg-ui-accent text-ui-link-text text-sm mb-4">
            <li>
                <button class="flex h-10 items-center px-5 hover:text-gray-700 hover:bg-gray-400 dark:hover:text-white hover:bg-[#6795de]" @click="submit">
                    <i class="icon-moon-database mr-1.5"></i>Save
                </button>
            </li>
            <li>
                <Link class="flex h-10 items-center px-5 hover:text-gray-700 hover:bg-gray-400 dark:hover:text-white hover:bg-[#6795de]" href="#">
                    <i class="icon-moon-refresh mr-1.5"></i>Reset
                </Link>
            </li>
            <li>
                <Link class="flex h-10 items-center px-5 hover:text-gray-700 hover:bg-red-700 dark:hover:text-white hover:bg-[#b1361e]">
                    <i class="icon-moon-trash mr-1.5"></i>Delete
                </Link>
            </li>
        </ul>

        <!--  KATA INPUT FIELDS  -->
        <div class="md:flex md:flex-row py-4">
            <!--  LEFT FIELDS  -->
            <div class="md:w-1/2 md:pr-4 text-sm">
                <!-- Name -->
                <div class="flex flex-col space-y-2">
                    <label for="name">Name:</label>
                    <input v-model="form.name"
                           id="name"
                           class="input-gray rounded-lg text-sm"
                           placeholder="Give your kata a name"
                           type="text"
                    >
                </div>

                <!-- Discipline -->
                <div class="mt-8 mb-3">
                    <h2 class="mb-2">Discipline:</h2>
                    <ul class="flex flex-wrap">
                        <KataCategoryCheckbox
                            @click="checkCategory('reference')"
                            :class="form.category === 'reference' ? 'bg-transparent dark:bg-ui-section' : ''"
                            icon="reference"
                            tippy="Code that tests the ability to utilize core language features and APIs"
                        >
                            Fundamentals
                        </KataCategoryCheckbox>
                        <KataCategoryCheckbox
                            @click="checkCategory('algorithms')"
                            :class="form.category === 'algorithms' ? 'bg-transparent dark:bg-ui-section' : ''"
                            icon="algorithm"
                            tippy="Code that needs to be created in order to meet the criteria specified. Typically these types of katas involve elaborate specifications."
                        >
                            Algorithms
                        </KataCategoryCheckbox>
                        <KataCategoryCheckbox
                            @click="checkCategory('bug_fixes')"
                            :class="form.category === 'bug_fixes' ? 'bg-transparent dark:bg-ui-section' : ''"
                            icon="bug"
                            tippy="Code that is broken and needs to be corrected in order for it to work as expected."
                        >
                            Bug Fixes
                        </KataCategoryCheckbox>
                        <KataCategoryCheckbox
                            @click="checkCategory('refactoring')"
                            :class="form.category === 'refactoring' ? 'bg-transparent dark:bg-ui-section' : ''"
                            icon="cone"
                            tippy="Code that needs to be transformed in some way, usually to create code that is either more DRY, efficient or readable."
                        >
                            Refactoring
                        </KataCategoryCheckbox>
                        <KataCategoryCheckbox
                            @click="checkCategory('games')"
                            :class="form.category === 'games' ? 'bg-transparent !dark:bg-ui-section' : ''"
                            icon="rubix"
                            tippy="Code that is fun to play and involves solving puzzles that may not be strictly programming challenges"
                        >
                            Puzzles
                        </KataCategoryCheckbox>
                    </ul>
                    <input type="hidden" value="reference" name="code_challenge[category]" id="code_challenge_category">
                    <div class="clearfix"></div>
                </div>

                <!-- Rank -->
                <div class="flex flex-col mb-5">
                    <label for="rank">
                        <i data-tippy-content="Choose the rank you are expecting this kata to be ranked as."
                           data-tippy-placement="bottom" class="icon-moon-info mr-1"/>
                        Estimated Rank:
                    </label>
                    <select v-model="form.rank"
                            id="rank"
                            class="mt-2 input-gray rounded-lg text-sm"
                    >
                        <option value=""></option>
                        <option value="8">8 kyu (white)</option>
                        <option value="7">7 kyu (white)</option>
                        <option value="6">6 kyu (yellow)</option>
                        <option value="5">5 kyu (yellow)</option>
                        <option value="4">4 kyu (blue)</option>
                        <option value="3">3 kyu (blue)</option>
                        <option value="2">2 kyu (purple)</option>
                        <option value="1">1 kyu (purple)</option>
                    </select>
                </div>

                <!-- Tags Multi -->
                <div class="flex flex-col mb-8">
                    <label>Tags <span class="note">(Comma separated)</span></label>
                    <VueMultiselect
                        class="mt-2 input-gray rounded-lg text-sm dark:bg-black"
                        v-model="form.tags"
                        :options="allTags"
                        :multiple="true"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Pick tags"
                        label="name"
                        track-by="name"
                        :preselect-first="false"
                    />
                </div>

                <!-- Allow Contributors -->
                <label class="inline-flex items-center mb-8" for="coauthors_wanted">
                    <input name="coauthors_wanted" type="hidden" value="false">
                    <input checked="checked"
                           class="form-checkbox rounded bg-gray-400 dark:bg-gray-600 border-transparent focus:border-transparent focus:bg-gray-400 text-gray-700 focus:ring-1 focus:ring-offset-2 dark:ring-offset-1 focus:ring-gray-500 dark:focus:ring-gray-700 dark:focus:ring-offset-gray-800 mx-0"
                           id="coauthors_wanted"
                           type="checkbox"
                           value="true">
                    <span class="mx-2">Allow Contributors?</span>
                    <span data-tippy-content="Check to allow users with the 'contributor' ability to make changes to
                    this kata while it is in beta. Regardless if this is checked or not, contributors will be allowed
                    to edit beta kata that have outstanding issues or suggestions older than 5 days.
                    You will be notified of any edits made by other users."
                          data-tippy-placement="bottom">
                        <i class="icon-moon-info"/>
                    </span>
                </label>
            </div>

            <!--  RIGHT TEXTAREA  -->
            <div class="md:w-1/2 md:pl-4">
                <Tabs ref="descriptionTabs">
                    <Tab class="description overflow-y-auto h-[420px] border border-gray-400" id="description_tab" title="Description" name="description_tab" :selected="true">
                        <AirSummernote class="hidden" v-on:submitted="summernoteSubmit" classes="rounded"
                                       summernoteId="description"/>
                        <textarea id="description" v-model="form.description">{{ form.description }}</textarea>
                    </Tab>
                    <Tab id="preview" title="<i class='icon-moon-preview mr-1'></i>Preview" name="preview">
                        Enter your
                        instructions/description text within the
                        Description tab and you will see your markup preview here.
                    </Tab>
                    <Tab id="help" title="<i class='icon-moon-help mr-1'></i>Help" name="help">
                        <div class="overflow-y-auto h-[420px]">
                            <h2 class="text-xl font-bold mb-5">What is the description field for?</h2>
                            <p>Use this area to set the stage for the kata. Describe what the problem is and
                                what the user needs to do to begin to solve it.</p>
                            <p>You can use markdown within the description field to describe your kata. For
                                example you can link to external web links, use bullet points or section
                                headings. Perhaps the most useful markdown feature is the ability to embed
                                code within your description. Simply wrap your code block within three back
                                ticks (```) both before and after the code. After the opening set of back
                                ticks you can specify the language.</p>
                            <p>For example - to embed javascript:</p>
                            <pre><code>```javascript var a = 1; ```</code></pre>
                            <p>If you are creating a kata with multiple languages than you can specify
                                examples for multiple languages back-to-back. Only the relevant language
                                will be displayed within the rendered output. If you place any text between
                                the code blocks then they will not be grouped. For example:</p>
                            <p><strong>These examples will be grouped</strong></p>
                            <pre><code>var a = 1;</code></pre>
                            <p><strong>These will not be grouped</strong></p>
                            <pre><code>```javascript var a = 1;
``` CoffeeScript:
```coffeescript a = 1; ```</code></pre>
                            <h2>Tips</h2>
                            <p>Some kata descriptions will be pretty long, especially if you are describing
                                an algorithm that needs to be coded from scratch. Others may end up being
                                very short. Sometimes its easier to put notes within the initial code that
                                the user will see instead of describing things within the description.</p>
                        </div>
                    </Tab>
                </Tabs>
            </div>
        </div>

        <div class="text-sm my-4">
            <!-- LANGUAGES_BAR -->
            <div class="md:flex md:flex-row mb-4 space-y-2 md:space-y-0">
                <!-- LANGUAGE_SELECT -->
                <div class="md:w-2/3 flex flex-col">
                    <label for="language">
                        Languages:
                        <i data-tippy-content="Select the language you wish to create the kata with. You can select multiple languages by editing them one at a time. Any languages that have been saved will have an X next to them indicating that they can be removed."
                           data-tippy-placement="bottom" class="icon-moon-info mr-1"/>
                    </label>
                    <div class="flex space-x-4">
                        <select v-model="form.language"
                                id="language"
                                class="mt-2 input-gray w-40 text-sm"
                        >
                            <option value="agda">Agda (Beta)</option>
                            <option value="agda">Agda (Beta)</option>
                            <option value="agda">Agda (Beta)</option>
                        </select>
                        <select v-model="form.language_version"
                                id="language_version"
                                class="mt-2 input-gray w-40 text-sm"
                        >
                            <option value="1.0">1.0</option>
                            <option value="2.0">2.0</option>
                            <option value="3.0">3.0</option>
                        </select>
                    </div>
                </div>
                <!-- SOLUTIONS BUTTONS -->
                <div class="md:w-1/3 md:text-right">
                    <button class="btn btn-green" id="validate_answer">
                        <i class="icon-moon-check"></i> Validate Solution
                    </button>
                    <button class="btn ml-4 bg-gray-300 hover:bg-gray-100 text-gray-800 border-gray-800"
                            data-tour="example"
                            id="insert_example">
                        <i class="icon-moon-info"></i> Insert Example
                    </button>
                </div>
            </div>

            <!-- SOLUTIONS / TEST CASES CONTAINER-->
            <div class="flex md:flex-row flex-col h-min-[300px] md:space-x-8 space-y-4 md:space-y-0">
                <!-- SOLUTIONS TABS -->
                <div class="md:w-1/2">
                    <Tabs ref="solutionsTabs">
                        <Tab title="Complete Solution" :selected="true"
                             data-tippy-content="This is where you will code a complete working solution.">
                            <textarea v-model="form.solution" id="complete_solution"></textarea>
                        </Tab>
                        <Tab title="Initial Solution"
                             data-tippy-content="This is the initial code that a user will see when taking the kata">
                            <textarea id="initial_solution">Initial Solution textarea!</textarea>
                        </Tab>
                        <Tab title="Preloaded"
                             data-tippy-content="This is an optional set of code">
                            <textarea id="preloaded">Preloaded textarea!</textarea>
                        </Tab>
                    </Tabs>
                </div>
                <!-- TEST CASES TABS -->
                <div class="md:w-1/2">
                    <Tabs ref="testCasesTabs">
                        <Tab title="Test Cases" :selected="true"
                             data-tippy-content="Provide test cases that will determine if the solution is valid or not">
                            <textarea id="test_cases">Test Cases textarea!</textarea>
                        </Tab>
                        <Tab title="Example Test Cases"
                             data-tippy-content="Use this code block to provide some example test cases that will be preloaded for the user."
                        >
                            <textarea id="example_test_cases">Example Test Cases textarea!</textarea>
                        </Tab>
                        <Tab id="help" title="<i class='icon-moon-help mr-1'></i>Help" name="help">
                            <div class=""><h3>What is a test fixture?</h3>
                                <p>The test fixture is used to write code that will validate the kata solution.
                                    The entire set of code in this block acts as a single test case. To see an
                                    example of how the test fixture
                                    can be used, click "Insert Example", which will insert example code for your
                                    selected language.</p>
                                <h3>Validating the solution:</h3>
                                <p>Once your solution and fixtures have code you can click "validate solution"
                                    to check if your code validates. Generally only reference/syntax errors will
                                    be descriptive.
                                    Failed test expectations will have generic messages unless you provide a
                                    custom message.
                                    These are the same errors that others will see when they are solving your
                                    kata.</p>
                                <h3>Previewing the challenge:</h3>
                                <p>Un-published kata's have the ability to preview solving them. This action is
                                    available in the top
                                    right of the action bar. It is recommended that you try to take the
                                    challenge yourself and see
                                    what types of common errors may come up during the process. This is a useful
                                    way of discovering
                                    ways in which you can provide Test.expect methods with custom messages to
                                    help guide others.
                                    These custom messages can be much more useful then the standard runtime
                                    errors that
                                    would otherwise be shown.</p></div>
                        </Tab>
                    </Tabs>
                </div>
            </div>
        </div>
    </form>
</template>

<style>
/* disable summernote textarea border */
.note-editing-area .note-editable{
    border: none !important;
}

.multiselect__tag {
    background: rgb(82, 82, 91) !important;
}

i.multiselect__tag-icon::after {
    color: rgb(0, 0, 0);
}

i.multiselect__tag-icon:hover::after {
    color: #fff;
}

.dark .multiselect__tags {
    border: 1px solid rgb(63, 63, 70);
    background: rgb(39,39,42);
}

.dark .multiselect__option {
    color: #aaa;
    background: rgb(39,39,42);
    border: 1px solid #333;

}

.dark .multiselect__input {
    color: #aaa;
    background: rgb(39,39,42);
    border: 1px solid #333;

}
</style>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
