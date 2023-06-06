<script>
import { onMounted, onUnmounted, ref } from "vue";
import useScript from "@/composables/useScript.js";

export default {
    props: {
        errors: Object,
        label: {
            type: String,
            default: "",
        },
        address: {
            type: String,
            default: "",
        },
        city: {
            type: String,
            default: "",
        },
        zip_code: {
            type: String,
            default: "",
        },
        country: {
            type: String,
            default: "",
        },
        address_lat: {
            type: Number,
            default: "",
        },
        address_lng: {
            type: Number,
            default: "",
        },
    },

    setup(props, context) {
        const streetRef = ref(null);
        let autocomplete;

        onMounted(async () => {
            await useScript(
                `https://maps.googleapis.com/maps/api/js?key=${
                    import.meta.env.VITE_GOOGLE_MAP_API_KEY
                }&libraries=places`
            );
            autocomplete = new google.maps.places.Autocomplete(
                streetRef.value,
                {
                    types: ["address"],
                    componentRestrictions: { country: ["FR"] },
                    fields: ["name", "address_components", "geometry"],
                }
            );

            google.maps.event.addListener(autocomplete, "place_changed", () => {
                const mapping = {
                    locality: "update:city",
                    postal_code: "update:zip_code",
                    country: "update:country",
                };

                let address = "update:address";
                let address_lat = "update:address_lat";
                let address_lng = "update:address_lng";

                //reset form in case of change
                for (const type in mapping) {
                    context.emit(mapping[type], "");
                }

                let place = autocomplete.getPlace();

                context.emit(address, place.name);
                context.emit(address_lat, place.geometry.location.lat());
                context.emit(address_lng, place.geometry.location.lng());

                place.address_components.forEach((component) => {
                    component.types.forEach((type) => {
                        if (mapping.hasOwnProperty(type)) {
                            context.emit(mapping[type], component.long_name);
                        }
                    });
                    // document.getElementById("department_code").focus();
                });
            });

            google.maps.event.addDomListener(
                streetRef.value,
                "keydown",
                (event) => {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        // document.getElementById("department_code").focus();
                    }
                }
            );
        });

        onUnmounted(() => {
            if (autocomplete) {
                google.maps.event.clearInstanceListeners(autocomplete);
            }
        });

        return {
            streetRef,
        };
    },
};
</script>

<template>
    <div class="grid grid-cols-4 gap-6">
        <!-- Adresse -->
        <div class="col-span-4 sm:col-span-2">
            <label
                for="address"
                class="block text-sm font-medium text-gray-700"
            >
                Adresse *
            </label>
            <div class="flex mt-1 rounded-md shadow-sm">
                <input
                    @input="$emit('update:address', $event.target.value)"
                    :value="address"
                    ref="streetRef"
                    type="text"
                    name="address"
                    id="address"
                    autocomplete="off"
                    data-lpignore="true"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="33 rue des pins..."
                />
            </div>
            <div v-if="errors.address" class="mt-2 text-xs text-red-500">
                {{ errors.address }}
            </div>
        </div>

        <!-- city-->
        <div class="col-span-4 sm:col-span-2">
            <label for="city" class="block text-sm font-medium text-gray-700"
                >Ville *</label
            >
            <input
                @input="$emit('update:city', $event.target.value)"
                :value="city"
                type="text"
                name="city"
                id="city"
                autocomplete="off"
                data-lpignore="true"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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
                @input="$emit('update:zip_code', $event.target.value)"
                :value="zip_code"
                type="text"
                name="zip_code"
                id="zip_code"
                autocomplete="off"
                data-lpignore="true"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            />
            <div v-if="errors.zip_code" class="mt-2 text-xs text-red-500">
                {{ errors.zip_code }}
            </div>
        </div>
        <!-- department_code -->
        <!-- <div class="col-span-3 sm:col-span-1">
            <label
                for="department_code"
                class="block text-sm font-medium text-gray-700"
            >
                Code département
            </label>
            <div class="mt-1">
                <select
                    name="department_code"
                    id="department_code"
                    @input="
                        $emit('update:department_code', $event.target.value)
                    "
                    :value="department_code"
                    class="block w-full text-sm text-gray-800 border-gray-300 rounded-lg shadow-sm"
                >
                    <option
                        v-for="department in departments"
                        :key="department.id"
                        :value="department.id"
                    >
                        {{ department.code }}
                        -
                        {{ department.name }}
                    </option>
                </select>
            </div>
            <div
                v-if="errors.department_code"
                class="mt-2 text-xs text-red-500"
            >
                {{ errors.department_code }}
            </div>
        </div> -->
        <!-- pays -->
        <div class="col-span-4 sm:col-span-2">
            <label for="country" class="block text-sm font-medium text-gray-700"
                >Pays *</label
            >
            <select
                @input="$emit('update:country', $event.target.value)"
                :value="country"
                id="country"
                name="country"
                autocomplete="off"
                class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
            >
                <option value="France">France</option>
            </select>
            <div v-if="errors.country" class="mt-2 text-xs text-red-500">
                {{ errors.country }}
            </div>
        </div>
    </div>
</template>
