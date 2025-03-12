<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { defineProps } from 'vue';

const abTestData = ref([]);
const abVisitorsData = ref([]);
const abVisitorsDataA = ref([]);
const abVisitorsDataB = ref([]);
const abPageViewData = ref([]);
const abPageViewDataA = ref([]);
const abPageViewDataB = ref([]);
const abConversionData = ref([]);
const abConversionDataA = ref([]);
const abConversionDataB = ref([]);
const abConversionRate = ref([]);
const abConversionRateA = ref([]);
const abConversionRateB = ref([]);

const props = defineProps({
    test_id: {
        type: [String, Number],
        required: true,
    },
});

const fetchData = async () => {
    try {
        const response = await axios.get(`/ab-test-data`);
        abTestData.value = response.data;

        // Compute unique session count only from abTestData that matches test_id
        const filteredData = abTestData.value.filter(item => item.test_id == props.test_id);
        const filteredVersionA = abTestData.value.filter(item => item.test_id == props.test_id && item.version == 'A');
        const filteredVersionB = abTestData.value.filter(item => item.test_id == props.test_id && item.version == 'B');

        //Unique Session Count
        const uniqueSessions = new Set(filteredData.map(item => item.user_session_id));
        abVisitorsData.value = uniqueSessions.size;
        const uniqueSessionsA = new Set(filteredVersionA.map(item => item.user_session_id));
        abVisitorsDataA.value = uniqueSessionsA.size;
        const uniqueSessionsB = new Set(filteredVersionB.map(item => item.user_session_id));
        abVisitorsDataB.value = uniqueSessionsB.size;

        //Page View Count
        abPageViewData.value = filteredData.reduce((total, item) => total + (item.page_views || 0), 0);
        abPageViewDataA.value = filteredVersionA.reduce((total, item) => total + (item.page_views || 0), 0);
        abPageViewDataB.value = filteredVersionB.reduce((total, item) => total + (item.page_views || 0), 0);

        //Conversion Count
        abConversionData.value = filteredData.reduce((total, item) => total + (item.conversions || 0), 0);
        abConversionDataA.value = filteredVersionA.reduce((total, item) => total + (item.conversions || 0), 0);
        abConversionDataB.value = filteredVersionB.reduce((total, item) => total + (item.conversions || 0), 0);

        //Conversion Rate
        abConversionRate.value = parseFloat((abConversionData.value / abVisitorsData.value) * 100).toFixed(2);
        abConversionRateA.value = parseFloat((abConversionDataA.value / abVisitorsDataA.value) * 100).toFixed(2);
        abConversionRateB.value = parseFloat((abConversionDataB.value / abVisitorsDataB.value) * 100).toFixed(2);
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
        <h2 class="text-xl font-bold mb-6 text-blue-50">{{ test_id }}</h2>
        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 bg-gray-900 p-6 rounded-lg shadow-lg text-white">
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-lg font-semibold">Visitors</p>
                <p class="text-2xl font-bold">{{ abVisitorsData }}</p>
            </div>
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-lg font-semibold">Views</p>
                <p class="text-2xl font-bold">{{ abPageViewData }}</p>
            </div>
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-lg font-semibold">Conversions</p>
                <p class="text-2xl font-bold">{{ abConversionData }}</p>
            </div>
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-lg font-semibold">Conversion Rate</p>
                <p class="text-2xl font-bold">{{ abConversionRate }}%</p>
            </div>
        </div>
        <br>


        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Version A Section -->
            <div class="bg-gray-900 p-4 rounded-lg shadow-lg text-white">
                <h3 class="text-lg font-bold mb-4 text-center">Version A</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-800 rounded-lg text-center">
                        <p class="text-sm font-semibold">Visitors</p>
                        <p class="text-xl font-bold">{{ abVisitorsDataA }}</p>
                    </div>
                    <div class="p-3 bg-gray-800 rounded-lg text-center">
                        <p class="text-sm font-semibold">Views</p>
                        <p class="text-xl font-bold">{{ abPageViewDataA }}</p>
                    </div>
                    <div class="p-3 bg-gray-800 rounded-lg text-center">
                        <p class="text-sm font-semibold">Conversions</p>
                        <p class="text-xl font-bold">{{ abConversionDataA }}</p>
                    </div>
                    <div class="p-3 bg-gray-800 rounded-lg text-center">
                        <p class="text-sm font-semibold">Conversion Rate</p>
                        <p class="text-xl font-bold">{{ abConversionRateA }}%</p>
                    </div>
                </div>
            </div>

            <!-- Version B Section -->
            <div class="bg-gray-900 p-4 rounded-lg shadow-lg text-white">
                <h3 class="text-lg font-bold mb-4 text-center">Version B</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-800 rounded-lg text-center">
                        <p class="text-sm font-semibold">Visitors</p>
                        <p class="text-xl font-bold">{{ abVisitorsDataB }}</p>
                    </div>
                    <div class="p-3 bg-gray-800 rounded-lg text-center">
                        <p class="text-sm font-semibold">Views</p>
                        <p class="text-xl font-bold">{{ abPageViewDataB }}</p>
                    </div>
                    <div class="p-3 bg-gray-800 rounded-lg text-center">
                        <p class="text-sm font-semibold">Conversions</p>
                        <p class="text-xl font-bold">{{ abConversionDataB }}</p>
                    </div>
                    <div class="p-3 bg-gray-800 rounded-lg text-center">
                        <p class="text-sm font-semibold">Conversion Rate</p>
                        <p class="text-xl font-bold">{{ abConversionRateB }}%</p>
                    </div>
                </div>
            </div>
        </div>



        <br>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-600 rounded-lg" v-if="abTestData.length">
                <thead>
                    <tr class="bg-blue-50 text-black">
                        <th class="border border-gray-600 p-3">Session ID</th>
                        <th class="border border-gray-600 p-3">Version</th>
                        <th class="border border-gray-600 p-3">Page Views</th>
                        <th class="border border-gray-600 p-3">Conversions</th>
                        <th class="border border-gray-600 p-3">Current URL</th>
                        <th class="border border-gray-600 p-3">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in abTestData.filter(item => item.test_id == props.test_id)" :key="item.id"
                        class="border border-gray-600 even:bg-gray-800 odd:bg-gray-900 text-white">
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
