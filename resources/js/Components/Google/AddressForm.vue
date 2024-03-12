<script setup>
import { onMounted, onUnmounted, ref } from "vue";
import useScript from "@/composables/useScript.js";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    errors: Object,
    label: String,
    address: String,
    city: String,
    zip_code: String,
    country: String,
    address_lat: Number,
    address_lng: Number,
});

const emit = defineEmits([
    "update:address",
    "update:city",
    "update:zip_code",
    "update:country",
    "update:address_lat",
    "update:address_lng",
]);

const updateAddress = (event) => emit("update:address", event.target.value);
const updateCity = (event) => emit("update:city", event.target.value);
const updateZipCode = (event) => emit("update:zip_code", event.target.value);
const updateCountry = (event) => emit("update:country", event.target.value);

const isShowing = ref(true);
const streetRef = ref(null);
let autocomplete;

onMounted(async () => {
    await useScript(
        `https://maps.googleapis.com/maps/api/js?key=${
            import.meta.env.VITE_GOOGLE_MAP_API_KEY
        }&libraries=places&callback=Function.prototype`
    );

    autocomplete = new google.maps.places.Autocomplete(streetRef.value, {
        types: ["address"],
        componentRestrictions: { country: ["FR"] },
        fields: ["name", "address_components", "geometry"],
    });

    google.maps.event.addListener(autocomplete, "place_changed", () => {
        const mapping = {
            locality: "update:city",
            postal_code: "update:zip_code",
            country: "update:country",
        };

        // Reset form in case of change
        for (const type in mapping) {
            emit(mapping[type], "");
        }

        let place = autocomplete.getPlace();

        emit("update:address", place.name);
        emit("update:address_lat", place.geometry.location.lat());
        emit("update:address_lng", place.geometry.location.lng());

        place.address_components.forEach((component) => {
            component.types.forEach((type) => {
                if (mapping.hasOwnProperty(type)) {
                    emit(mapping[type], component.long_name);
                }
            });
        });
    });

    streetRef.value.addEventListener("keydown", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });
});

onUnmounted(() => {
    if (autocomplete) {
        google.maps.event.clearInstanceListeners(autocomplete);
    }
});
</script>

<template>
    <TransitionRoot
        appear
        :show="isShowing"
        enter="transition-opacity ease-linear duration-400"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-linear duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
    >
        <div class="grid grid-cols-4 gap-6">
            <!-- Adresse -->
            <div class="col-span-4 sm:col-span-2">
                <label
                    for="address"
                    class="block text-sm font-medium text-gray-700"
                >
                    Adresse *
                </label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input
                        @input="updateAddress"
                        :value="address"
                        ref="streetRef"
                        type="text"
                        name="address"
                        id="address"
                        autocomplete="off"
                        data-lpignore="true"
                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="33 rue des pins..."
                    />
                </div>
                <div v-if="errors.address" class="mt-2 text-xs text-red-500">
                    {{ errors.address }}
                </div>
            </div>

            <!-- city-->
            <div class="col-span-4 sm:col-span-2">
                <label
                    for="city"
                    class="block text-sm font-medium text-gray-700"
                    >Ville *</label
                >
                <input
                    @input="updateCity"
                    :value="city"
                    type="text"
                    name="city"
                    id="city"
                    autocomplete="off"
                    data-lpignore="true"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
                <div v-if="errors.city" class="mt-2 text-xs text-red-500">
                    {{ errors.city }}
                </div>
            </div>

            <!-- code postal -->
            <div class="col-span-4 sm:col-span-2">
                <label
                    for="zip_code"
                    class="block text-sm font-medium text-gray-700"
                    >ZIP / Code Postal *</label
                >
                <input
                    @input="updateZipCode"
                    :value="zip_code"
                    type="text"
                    name="zip_code"
                    id="zip_code"
                    autocomplete="off"
                    data-lpignore="true"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
                <div v-if="errors.zip_code" class="mt-2 text-xs text-red-500">
                    {{ errors.zip_code }}
                </div>
            </div>
            <!-- pays -->
            <div class="col-span-4 sm:col-span-2">
                <label
                    for="country"
                    class="block text-sm font-medium text-gray-700"
                    >Pays *</label
                >
                <select
                    @input="updateCountry"
                    :value="country"
                    id="country"
                    name="country"
                    autocomplete="off"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                >
                    <option value="France">France</option>
                </select>
                <div v-if="errors.country" class="mt-2 text-xs text-red-500">
                    {{ errors.country }}
                </div>
            </div>
        </div>
    </TransitionRoot>
</template>
