<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    sentEmails: Array
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="container mx-auto p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left border-b">#</th>
                                        <th class="px-4 py-2 text-left border-b">Sent By</th>
                                        <th class="px-4 py-2 text-left border-b">Subject</th>
                                        <th class="px-4 py-2 text-left border-b">Body</th>
                                        <th class="px-4 py-2 text-left border-b">Emails</th>
                                        <th class="px-4 py-2 text-left border-b">CC</th>
                                        <th class="px-4 py-2 text-left border-b">Attachment Path</th>
                                        <th class="px-4 py-2 text-left border-b">Send At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(email, index) in sentEmails.data" :key="email.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ email.sent.name }}</td>
                                        <td>{{ email.subject.slice(0, 200) }}</td>
                                        <td v-html="email.body"></td>
                                        <td>{{ email.emails }}</td>
                                        <td>{{ email.cc }}</td>
                                        <td>{{ email.attachment_path }}</td>
                                        <td>{{ email.created_at }}</td>

                                    </tr>
                                </tbody>
                            </table>
                            <!-- pagination inertia -->



                        </div>
                    </div>

                </div>
                <div class="mt-4" v-if="sentEmails.data.length">
                    <div class="flex justify-end flex-wrap -mb-1">
                        <template v-for="(link, key) in sentEmails.links">
                            <div v-if="link.url === null" :key="key"
                                class="mr-1 bg-white mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                                v-html="link.label" />
                            <inertia-link v-else :key="label"
                                class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                                :class="{ 'bg-blue-500 text-white': link.active }" :href="link.url"
                                v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
