<script setup lang="ts">
import FlowerItem from "../../Components/FlowerItem.vue"
import { ref, reactive, computed } from "vue"
import { router } from "@inertiajs/vue3"

const props = defineProps<{
    flowers: {
        id: number
        name: string
        color: string
    }[]
}>()

const searchText = ref("")
const searchType = ref<"NAME" | "COLOR">("NAME")

const filteredFlowers = computed(() => {
    if (searchType.value === "NAME") {
        return props.flowers.filter((flower) =>
            flower.name.toLowerCase().includes(searchText.value)
        )
    }

    if (searchType.value === "COLOR") {
        return props.flowers.filter((flower) =>
            flower.color.toLowerCase().includes(searchText.value)
        )
    }

    return props.flowers
})

// Create form
const createDialog = ref<null | HTMLDialogElement>(null)
const createForm = reactive<{
    name: null | string
    color: null | string
}>({
    name: null,
    color: null,
})

function showCreateDialog() {
    createForm.name = null
    createForm.color = null
    createDialog.value?.showModal()
}

const hasInvalidFields = ref<boolean>(false)

function onSubmitCreateDialog(e: Event) {
    e.preventDefault()
    hasInvalidFields.value = false

    if (createForm.name === null || createForm.name === "") {
        hasInvalidFields.value = true
        return
    }

    if (createForm.color === null || createForm.color === "") {
        hasInvalidFields.value = true
        return
    }

    router.post("/flowers", createForm)
    createDialog.value?.close()
}
</script>
<template>
    <main class="min-h-screen bg-zinc-50 pt-24 pb-16">
        <div class="text-4xl text-center mb-6 font-semibold">
            VERY SIMPLE CRUD
        </div>
        <div class="max-w-3xl mx-auto bg-white shadow-md px-12 py-8 rounded-md">
            <div class="grid grid-cols-[8rem_1fr_4rem] gap-3 mb-3">
                <select v-model="searchType" class="px-4 py-2 rounded-md">
                    <option value="NAME">By name</option>
                    <option value="COLOR">By color</option>
                </select>
                <input
                    type="text"
                    class="w-full px-4 py-2 pr-4 text-gray-700 bg-white border rounded-md dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40"
                    :placeholder="`Type a ${searchType.toLowerCase()} ...`"
                    v-model="searchText"
                />

                <button
                    @click="showCreateDialog"
                    type="button"
                    class="bg-zinc-500 hover:bg-zinc-400 transition duration-200 text-white px-4 py-2 rounded-md font-medium"
                >
                    Add
                </button>
            </div>

            <div>
                <div
                    v-if="filteredFlowers.length > 0"
                    class="grid grid-cols-2 gap-3"
                >
                    <FlowerItem
                        v-for="flower in filteredFlowers"
                        :key="flower.id"
                        :flower="flower"
                    />
                </div>
                <div v-else class="text-center mt-6">No results ):</div>
            </div>

            <!-- Create Dialog -->
            <dialog
                ref="createDialog"
                class="w-[min(32rem,_calc(100%_-_1.5rem))] rounded-md"
            >
                <form @submit="onSubmitCreateDialog">
                    <div class="px-12 pt-8 pb-8">
                        <div
                            class="text-red-500 text-center mb-3"
                            v-if="hasInvalidFields"
                        >
                            Please enter the required fields.
                        </div>
                        <div class="grid mb-3">
                            <label class="font-medium mb-1"
                                >Name
                                <span class="text-red-500 font-semibold">
                                    *
                                </span>
                            </label>
                            <input
                                type="text"
                                class="w-full px-4 py-2 pr-4 text-gray-700 bg-white border rounded-md dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40"
                                v-model="createForm.name"
                            />
                        </div>
                        <div class="grid mb-3">
                            <label class="font-medium mb-1"
                                >Color
                                <span class="text-red-500 font-semibold">
                                    *
                                </span>
                            </label>
                            <input
                                type="text"
                                class="w-full px-4 py-2 pr-4 text-gray-700 bg-white border rounded-md dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40"
                                v-model="createForm.color"
                            />
                        </div>
                    </div>
                    <div class="flex justify-between bg-zinc-100 px-12 py-4">
                        <button
                            @click="() => createDialog?.close()"
                            type="button"
                            class="px-4 py-2 font-medium tracking-wide transition duration-200 bg-white text-zinc-700 rounded-md hover:bg-zinc-200 focus:outline-none focus:bg-zinc-200"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="px-4 py-2 font-medium tracking-wide text-white transition duration-200 bg-blue-500 rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400"
                        >
                            Create
                        </button>
                    </div>
                </form>
            </dialog>
        </div>
    </main>
</template>
