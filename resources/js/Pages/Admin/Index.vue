<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import { ChevronLeftIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";

const props = defineProps({
    notifications: Object,
    user_can: Object,
});
const page = usePage();

const user = computed(() => page.props.auth.user);
const appName = computed(() => page.props.appName);
const adminNotificationsCount = computed(
    () => page.props.admin_notifications_count
);

const notificationForm = useForm({
    markedAsRead: false,
});

const markNotificationAsRead = (notification) => {
    notificationForm.patch(
        route("admin.notifications.markAsRead", {
            notification: notification,
        }),
        {
            only: ["notifications"],
            preserveScroll: true,
        }
    );
};
</script>
<template>
    <Head title="Gestion du site" description="Administration du site." />
    <AdminLayout>
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    :href="route('admin.index')"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-center text-base font-semibold text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Panel d'administration
                </h1>
            </div>
        </template>

        <div class="w-full space-y-4 px-2 py-6 text-slate-700 md:px-6">
            <h2 class="text-lg">
                Bienvenue
                <span class="font-semibold">{{ user.name }}</span> dans le panel
                d'administration de
                <span class="font-semibold">{{ appName }}</span>
            </h2>
            <p v-if="adminNotificationsCount > 0">
                Vous avez reçu
                <span v-if="adminNotificationsCount > 1" class="text-indigo-500"
                    >{{ adminNotificationsCount }} notifications</span
                >
                <span v-else class="text-indigo-500"
                    >{{ adminNotificationsCount }} notification</span
                >
                d'activités sur le site.
            </p>
            <div>
                <ul>
                    <li
                        v-for="notification in notifications"
                        :key="notification.id"
                    >
                        <template
                            v-if="
                                notification.type ===
                                'App\\Notifications\\ReservationPaidToAdmin'
                            "
                        >
                            <div class="mt-2 flex items-start justify-between">
                                <ul class="list-inside list-disc pl-4">
                                    <span
                                        class="text-sm font-medium text-gray-700"
                                        >Une nouvelle réservation a été
                                        réglée:</span
                                    >
                                    <li
                                        v-for="(
                                            value, key
                                        ) in notification.data"
                                        :key="key"
                                        class="text-sm"
                                    >
                                        <span class="font-semibold capitalize"
                                            >{{ key }}:</span
                                        >
                                        {{ value }}
                                    </li>
                                </ul>
                                <div>
                                    <label class="inline-flex items-center">
                                        <span class="mr-2 text-xs"
                                            >Marquer comme lu</span
                                        >
                                        <input
                                            type="checkbox"
                                            class="form-checkbox h-4 w-4 shrink-0 rounded border-gray-200 text-indigo-600 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50"
                                            v-model="
                                                notificationForm.markedAsRead
                                            "
                                            @change="
                                                markNotificationAsRead(
                                                    notification
                                                )
                                            "
                                        />
                                    </label>
                                </div>
                            </div>
                        </template>
                    </li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>
