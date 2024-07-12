<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
const emit = defineEmits(["page-changed"]);
const props = defineProps({
    links: {
        type: [Array, Object],
        default: null,
    },
    only: {
        type: [Array],
        default: () => [],
    },
});
const only = computed(() => (props.only.length === 0 ? props.only : []));

const handleClick = (e, link) => {
    e.preventDefault();
    if (link.url && !link.active) {
        // Extract page number from the label if it's a number
        const pageNumber = parseInt(link.label);
        if (!isNaN(pageNumber)) {
            emit("page-changed", pageNumber);
        } else if (link.label === "&laquo; Previous") {
            emit("page-changed", "previous");
        } else if (link.label === "Next &raquo;") {
            emit("page-changed", "next");
        }
    }
};
</script>

<template>
    <div v-if="links.length > 3">
        <div class="-mb-1 flex flex-wrap">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="mb-1 mr-1 rounded border px-4 py-3 text-sm leading-4 text-gray-400"
                    v-html="link.label"
                />
                <Link
                    v-else
                    :only="only"
                    @click="(e) => handleClick(e, link)"
                    :class="[
                        'mb-1 mr-1 rounded border px-4 py-3 text-sm leading-4 hover:bg-white focus:border-gray-500',
                        {
                            'text-gray-400 hover:text-gray-700 focus:text-gray-500':
                                !link.active,
                            'bg-blue-700 text-white': link.active,
                        },
                    ]"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
