<script setup lang="ts">
import { ref, reactive } from "vue"
import { router } from "@inertiajs/vue3"

const props = defineProps<{
    flower: {
        id: number
        name: string
        color: string
    }
}>()

// Delete form
const deleteDialog = ref<null | HTMLDialogElement>(null)

function onSubmitDeleteDialog(e: Event) {
    e.preventDefault()
    router.delete(`/flowers/${props.flower.id}`)
    deleteDialog.value?.close()
}

// Edit form
const editDialog = ref<null | HTMLDialogElement>(null)

function showEditDialog() {
    editForm.name = props.flower.name
    editForm.color = props.flower.color
    editDialog.value?.showModal()
}

const editForm = reactive<{
    name: string | null
    color: string | null
}>({
    name: null,
    color: null,
})

const hasInvalidFields = ref<boolean>(false)

function onSubmitEditDialog(e: Event) {
    e.preventDefault()
    hasInvalidFields.value = false

    if (editForm.name === null || editForm.name === "") {
        hasInvalidFields.value = true
        return
    }

    if (editForm.color === null || editForm.color === "") {
        hasInvalidFields.value = true
        return
    }

    router.patch(`/flowers/${props.flower.id}`, editForm)
    editDialog.value?.close()
}
</script>

<template>
    <div class="border border-zinc-300 rounded-md px-4 py-3">
        <div class="text-lg font-semibold">{{ flower.name }}</div>
        <div>Color: {{ flower.color }}</div>
        <div class="flex justify-end gap-3">
            <button
                type="button"
                @click="() => deleteDialog?.showModal()"
                class="px-4 py-2 font-medium tracking-wide text-white transition duration-200 bg-red-500 rounded-md hover:bg-red-400 focus:outline-none focus:bg-red-400"
            >
                Delete
            </button>

            <button
                type="button"
                @click="() => showEditDialog()"
                class="px-4 py-2 font-medium tracking-wide text-white transition duration-200 bg-zinc-500 rounded-md hover:bg-zinc-400 focus:outline-none focus:bg-zinc-400"
            >
                Edit
            </button>
        </div>

        <!-- Delete Dialog -->
        <dialog
            ref="deleteDialog"
            class="w-[min(32rem,_calc(100%_-_1.5rem))] rounded-md"
        >
            <form @submit="onSubmitDeleteDialog">
                <div class="px-12 pt-8 pb-8">
                    Are you sure you want to delete this flower?
                </div>
                <div class="flex justify-between bg-zinc-100 px-12 py-4">
                    <button
                        @click="() => deleteDialog?.close()"
                        type="button"
                        class="px-4 py-2 font-medium tracking-wide transition duration-200 bg-white text-zinc-700 rounded-md hover:bg-zinc-200 focus:outline-none focus:bg-zinc-200"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 font-medium tracking-wide text-white transition duration-200 bg-red-500 rounded-md hover:bg-red-400 focus:outline-none focus:bg-red-400"
                    >
                        Delete
                    </button>
                </div>
            </form>
        </dialog>

        <!-- Edit Dialog -->
        <dialog
            ref="editDialog"
            class="w-[min(32rem,_calc(100%_-_1.5rem))] rounded-md"
        >
            <form @submit="onSubmitEditDialog">
                <div class="px-12 pt-8 pb-8">
                    <div
                        class="text-red-500 text-center mb-3"
                        v-if="hasInvalidFields"
                    >
                        Please enter the required fields.
                    </div>
                    <div class="grid mb-3">
                        <label class="font-medium mb-1">Name</label>
                        <input
                            type="text"
                            class="w-full px-4 py-2 pr-4 text-gray-700 bg-white border rounded-md dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40"
                            v-model="editForm.name"
                        />
                    </div>
                    <div class="grid mb-3">
                        <label class="font-medium mb-1">Color</label>
                        <input
                            type="text"
                            class="w-full px-4 py-2 pr-4 text-gray-700 bg-white border rounded-md dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40"
                            v-model="editForm.color"
                        />
                    </div>
                </div>
                <div class="flex justify-between bg-zinc-100 px-12 py-4">
                    <button
                        @click="() => editDialog?.close()"
                        type="button"
                        class="px-4 py-2 font-medium tracking-wide transition duration-200 bg-white text-zinc-700 rounded-md hover:bg-zinc-200 focus:outline-none focus:bg-zinc-200"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 font-medium tracking-wide text-white transition duration-200 bg-blue-500 rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400"
                    >
                        Update
                    </button>
                </div>
            </form>
        </dialog>
    </div>
</template>
