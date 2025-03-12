<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { defineProps } from 'vue';

const abTestData = ref([]);

const props = defineProps({
    user_session_id: {
        type: [String, Number],
        required: true,
    },
});

const fetchData = async () => {
    try {
        const response = await axios.get(`/ab-test-data`);
        abTestData.value = response.data;
    } catch (error) {
        console.error("Error fetching data:", error);
    }
};

onMounted(fetchData);

// Function to format date
const formatDate = (dateString) => {
    const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
        second: "numeric",
        timeZoneName: "short",
    };

    const date = new Date(dateString);
    return date.toLocaleString("en-US", options);
};
</script>

<template>
    <div class="p-6">
        <h2 class="text-lg font-bold mb-6 text-blue-50">User Data</h2>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-600 rounded-lg" v-if="abTestData.length">
                <thead>
                    <tr class="bg-blue-50 text-black">
                        <th class="border border-gray-600 p-3">ID</th>
                        <th class="border border-gray-600 p-3">Test ID</th>
                        <th class="border border-gray-600 p-3">Session ID</th>
                        <th class="border border-gray-600 p-3">Version</th>
                        <th class="border border-gray-600 p-3">Page Views</th>
                        <th class="border border-gray-600 p-3">Conversions</th>
                        <th class="border border-gray-600 p-3">Current URL</th>
                        <th class="border border-gray-600 p-3">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in abTestData.filter(item => item.user_session_id == props.user_session_id)" :key="item.id"
                        class="border border-gray-600 even:bg-gray-800 odd:bg-gray-900 text-white">
                        <td class="border border-gray-600 p-3">{{ item.id }}</td>
                        <td class="border border-gray-600 p-3">{{ item.test_id }}</td>
                        <td class="border border-gray-600 p-3">{{ item.user_session_id }}</td>
                        <td class="border border-gray-600 p-3">{{ item.version }}</td>
                        <td class="border border-gray-600 p-3">{{ item.page_views }}</td>
                        <td class="border border-gray-600 p-3">{{ item.conversions }}</td>
                        <td class="border border-gray-600 p-3">{{ item.current_url }}</td>
                        <td class="border border-gray-600 p-3">{{ formatDate(item.created_at) }}</td>
                    </tr>
                </tbody>
            </table>
            <p v-else>No data available for this user.</p>
        </div>
    </div>
</template>
