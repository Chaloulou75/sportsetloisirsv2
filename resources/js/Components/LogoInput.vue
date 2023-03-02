<template>
    <div class="mx-auto">
        <input
            type="file"
            :accept="accept"
            class="hidden"
            ref="file"
            :name="name"
            :id="id"
            @change="change"
        />
        <div class="relative inline-block overflow-hidden">
            <img
                :src="src"
                alt="Logo"
                class="object-cover w-24 h-24 rounded-full"
            />
            <div
                class="absolute top-0 flex items-center justify-center w-full h-full bg-black bg-opacity-25 rounded-full"
            >
                <button
                    type="button"
                    @click="browse()"
                    class="p-2 text-white transition duration-200 rounded-full hover:bg-white hover:bg-opacity-25 focus:outline-none"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                </button>
                <button
                    type="button"
                    v-if="file"
                    @click="remove()"
                    class="p-2 text-white transition duration-200 rounded-full hover:bg-white hover:bg-opacity-25 focus:outline-none"
                >
                    <svg
                        class="w-5 h-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: File,
        accept: String,
        name: String,
        id: String,
        defaultSrc: String,
    },
    emits: ["update:modelValue"],
    data() {
        return {
            src: this.defaultSrc,
            file: null,
        };
    },
    methods: {
        browse() {
            this.$refs.file.click();
        },
        remove() {
            this.file = null;
            this.src = this.defaultSrc;
            this.$emit("update:modelValue", this.file);
        },
        change(e) {
            this.file = e.target.files[0];
            this.$emit("update:modelValue", this.file);
            let reader = new FileReader();
            reader.readAsDataURL(this.file);
            reader.onload = (e) => {
                this.src = e.target.result;
            };
        },
    },
};
</script>
