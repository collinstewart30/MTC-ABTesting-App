<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const abTestData = ref([]);

const fetchData = async () => {
    try {
        const response = await axios.get("/ab-conversion-data");
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
    <div class="min-h-screen p-6">
        <h2 class="text-xl font-bold mb-6 text-blue-50">A/B Conversion Data</h2>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-600 rounded-lg">
                <thead>
                    <tr class="bg-blue-50 text-black">
                        <th class="border border-gray-600 p-3">User Session ID</th>
                        <th class="border border-gray-600 p-3">Conversion Type</th>
                        <th class="border border-gray-600 p-3">Conversion Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in abTestData" :key="item.id"
                        class="border border-gray-600 even:bg-gray-800 odd:bg-gray-900 text-white">
                        <td class="border border-gray-600 p-3"><a :href="`/user-data/${item.user_session_id}`" class="underline hover:text-gray-400">{{ item.user_session_id }}</a></td>
                        <td class="border border-gray-600 p-3">{{ item.conversion_type }}</td>
                        <td class="border border-gray-600 p-3">{{ formatDate(item.created_at) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
