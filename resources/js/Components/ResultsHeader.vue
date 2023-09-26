<script setup>
import { ref, computed } from "vue";
const props = defineProps({
    discipline: Object,
});

const classMapping = {
    1: "bg-discipline-1",
    3: "bg-discipline-3",
    5: "bg-discipline-5",
    7: "bg-discipline-7",
    8: "bg-discipline-8",
    14: "bg-discipline-14",
    23: "bg-discipline-23",
    29: "bg-discipline-29",
    30: "bg-discipline-30",
    32: "bg-discipline-32",
    35: "bg-discipline-35",
    49: "bg-discipline-49",
    53: "bg-discipline-53",
    58: "bg-discipline-58",
    61: "bg-discipline-61",
    63: "bg-discipline-63",
    68: "bg-discipline-68",
    72: "bg-discipline-72",
    73: "bg-discipline-73",
    83: "bg-discipline-83",
    88: "bg-discipline-88",
    106: "bg-discipline-106",
    129: "bg-discipline-129",
    131: "bg-discipline-131",
    145: "bg-discipline-145",
    167: "bg-discipline-167",
    170: "bg-discipline-170",
    176: "bg-discipline-176",
    181: "bg-discipline-181",
    189: "bg-discipline-189",
    204: "bg-discipline-204",
    206: "bg-discipline-206",
    212: "bg-discipline-212",
    214: "bg-discipline-214",
    220: "bg-discipline-220",
    221: "bg-discipline-221",
    222: "bg-discipline-222",
    223: "bg-discipline-223",
    224: "bg-discipline-224",
    229: "bg-discipline-229",
    232: "bg-discipline-232",
    233: "bg-discipline-233",
    238: "bg-discipline-238",
    240: "bg-discipline-240",
    241: "bg-discipline-241",
    243: "bg-discipline-243",
    244: "bg-discipline-244",
    246: "bg-discipline-246",
    247: "bg-discipline-247",
    248: "bg-discipline-248",
    249: "bg-discipline-249",
    250: "bg-discipline-250",
    251: "bg-discipline-251",
    254: "bg-discipline-254",
    259: "bg-discipline-259",
    262: "bg-discipline-262",
    263: "bg-discipline-263",
    265: "bg-discipline-265",
    270: "bg-discipline-270",
    271: "bg-discipline-271",
    276: "bg-discipline-276",
    279: "bg-discipline-279",
    280: "bg-discipline-280",
    286: "bg-discipline-286",
    288: "bg-discipline-288",
    289: "bg-discipline-289",
    293: "bg-discipline-293",
    294: "bg-discipline-294",
    295: "bg-discipline-295",
    296: "bg-discipline-296",
    298: "bg-discipline-298",
    302: "bg-discipline-302",
    305: "bg-discipline-305",
    306: "bg-discipline-306",
    307: "bg-discipline-307",
    308: "bg-discipline-308",
    309: "bg-discipline-309",
    310: "bg-discipline-310",
    311: "bg-discipline-311",
    312: "bg-discipline-312",
    313: "bg-discipline-313",
    315: "bg-discipline-315",
    316: "bg-discipline-316",
    319: "bg-discipline-319",
    320: "bg-discipline-320",
    321: "bg-discipline-321",
    328: "bg-discipline-328",
};

const headerClass = computed(() => {
    const defaultClass = "bg-la-base";
    if (props.discipline && props.discipline.id) {
        const disciplineId = props.discipline.id;
        if (classMapping[disciplineId]) {
            return classMapping[disciplineId];
        } else {
            return defaultClass;
        }
    } else {
        return defaultClass;
    }
});
</script>

<template>
    <div
        class="mx-auto bg-slate-100/20 bg-cover bg-center bg-no-repeat px-10 py-2 bg-blend-soft-light md:py-6"
        :class="headerClass"
    >
        <div
            class="mx-auto my-2 flex w-full max-w-max flex-col items-center justify-center bg-slate-100/70 px-2 py-2 md:w-auto md:px-6"
        >
            <h1
                class="w-auto text-center text-2xl font-black leading-tight tracking-widest text-gray-600 md:text-4xl"
                v-if="$slots.title"
            >
                <slot name="title"></slot>
            </h1>
            <h2
                class="hidden w-auto text-center text-lg font-semibold leading-tight tracking-widest text-gray-600 md:block md:border-t-2 md:border-slate-400 md:text-2xl"
                v-if="$slots.subtitle"
            >
                <slot name="subtitle"></slot>
            </h2>
        </div>
        <div
            class="mx-auto hidden w-auto max-w-max flex-col items-center justify-center space-y-2 bg-gray-100/50 px-2 py-2 md:flex md:px-4"
            v-if="$slots.ariane"
        >
            <slot name="ariane"></slot>
        </div>
    </div>
</template>
