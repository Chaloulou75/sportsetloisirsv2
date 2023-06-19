<script setup>
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import VueCal from "vue-cal";
import "vue-cal/dist/vuecal.css";

const props = defineProps({
    show: Boolean,
    structure: Object,
    structureActivites: Object,
});

const getEvents = () => {
    const events = [];

    for (const structureActivite of props.structureActivites) {
        for (const produit of structureActivite.produits) {
            if (produit.horaire) {
                const event = {
                    start: `${produit.horaire.dayopen} ${produit.horaire.houropen}`,
                    end: `${produit.horaire.dayclose} ${produit.horaire.hourclose}`,
                    title: structureActivite.titre,
                    content: structureActivite.description,
                    produitId: produit.id,
                    class: "course",
                    deletable: true,
                    resizable: true,
                    draggable: true,
                };

                events.push(event);
            }
        }
    }

    return events;
};

const events = getEvents();

const handleEventDeleted = (event) => {
    const url = `/structures/${props.structure.slug}/plannings/${event.produitId}`;
    router.delete(url, {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
        },
        structure: props.structure.slug,
        planning: event.produitId,
    });
};


</script>
<template>
    <div class="min-h-full w-full rounded-xl shadow-lg mt-6 overflow-x-auto">
        <vue-cal
            small
            :time-from="6 * 60"
            :time-to="24 * 60"
            :time-step="30"
            locale="fr"
            :snap-to-time="15"
            :disable-views="['years', 'year']"
            :cell-click-hold="false"
            :editable-events="{
                title: true,
                drag: true,
                resize: true,
                delete: true,
                create: true,
            }"
            :drag-to-create-threshold="15"
            class="vuecal--full-height-delete"
            :events="getEvents()"
            :on-event-create="onEventCreate"
            @event-drag-create="isOpen = true"
            @event-delete="handleEventDeleted"
        />
    </div>
</template>
<style>
.course {
    @apply bg-green-300 text-blue-700;
}
</style>
