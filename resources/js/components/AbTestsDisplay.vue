<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { defineProps } from 'vue';

const openA = ref(false);
const openB = ref(false);

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
const abUniqueConversions = ref([]);
const abUniqueConversionsA = ref([]);
const abUniqueConversionsB = ref([]);
const uniqueConversionRate = ref([]);
const uniqueConversionRateA = ref([]);
const uniqueConversionRateB = ref([]);
const abMobileVisitsA = ref([]);
const abMobileVisitsB = ref([]);
const abDesktopVisitsA = ref([]);
const abDesktopVisitsB = ref([]);

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

        // Unique Conversions
        const uniqueConversions = new Set(filteredData.filter(item => item.conversions > 0).map(item => item.user_session_id));
        abUniqueConversions.value = uniqueConversions.size;
        const uniqueConversionsA = new Set(filteredVersionA.filter(item => item.conversions > 0).map(item => item.user_session_id));
        abUniqueConversionsA.value = uniqueConversionsA.size;
        const uniqueConversionsB = new Set(filteredVersionB.filter(item => item.conversions > 0).map(item => item.user_session_id));
        abUniqueConversionsB.value = uniqueConversionsB.size;

        //Unique Conversion Rate
        uniqueConversionRate.value = parseFloat((abUniqueConversions.value / abVisitorsData.value) * 100).toFixed(2);
        uniqueConversionRateA.value = parseFloat((abUniqueConversionsA.value / abVisitorsDataA.value) * 100).toFixed(2);
        uniqueConversionRateB.value = parseFloat((abUniqueConversionsB.value / abVisitorsDataB.value) * 100).toFixed(2);

        //Mobile Visits
        const uniqueMobileA = filteredVersionA.filter(item => item.device_type === 'mobile');
        abMobileVisitsA.value = uniqueMobileA.length;
        const uniqueMobileB = filteredVersionB.filter(item => item.device_type == 'mobile');
        abMobileVisitsB.value = uniqueMobileB.length;

        //Desktop Visits
        const uniqueDesktopA = filteredVersionA.filter(item => item.device_type == 'desktop');
        abDesktopVisitsA.value = uniqueDesktopA.length;
        const uniqueDesktopB = filteredVersionB.filter(item => item.device_type == 'desktop');
        abDesktopVisitsB.value = uniqueDesktopB.length;

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

// Filtered data based on user_session_id
const dataTableFiltered = computed(() => {
    return abTestData.value.filter(item => item.test_id == props.test_id);
});

//Toggle Dropdown
function toggleDropdown(version) {
    if (version === 'A') {
        openA.value = !openA.value;
    } else if (version === 'B') {
        openB.value = !openB.value;
    }
}

//Datatable
const columns = [
    {
        data: "user_session_id",
        render: (data) => {
            return `<a href="/user-data/${data}" class="underline hover:text-gray-400">${data}</a>`;
        }
    },
    { data: 'version' },
    { data: 'page_views' },
    { data: 'conversions' },
    {
        data: 'current_url',
        render: (data) => {
            const parsedUrl = new URL(data);  // Create a URL object from the current_url
            const pathName = parsedUrl.pathname;  // Return only the pathname (path without the domain)
            return `<a href="${data}" target="_blank" class="underline hover:text-gray-400">${pathName}</a>`;
        },
    },
    {
        data: 'created_at',
        render: (data) => formatDate(data), // Format the date in the DataTable
    }

];
const options = {
    order: [[5, "desc"]], // Sort by the 6th column (created_at) in descending order
    pageLength: 25,
    rowCallback: (row, data, index) => {
        row.classList.add("!border");
        row.classList.add("!border-gray-600");
        row.classList.add("even:!bg-gray-800");
        row.classList.add("odd:!bg-gray-900");
    },
    columnDefs: [
        {
            targets: 5, // Target the created_at column
            render: (data, type, row) => {
                if (type === "display") {
                    return formatDate(data); // Display formatted date
                }
                return new Date(data).getTime(); // Sort using timestamp
            },
            type: "num", // Ensure it is treated as a numeric value for sorting
        }
    ]
};
</script>

<template>
    <div class="p-6">
        <h2 class="text-xl font-bold mb-6 text-blue-50">{{ test_id }}</h2>
        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6 bg-gray-900 p-6 rounded-lg shadow-lg text-white">
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-base font-semibold">Visitors</p>
                <p class="text-xl font-bold">{{ abVisitorsData }}</p>
            </div>
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-base font-semibold">Views</p>
                <p class="text-xl font-bold">{{ abPageViewData }}</p>
            </div>
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-base font-semibold">Conversions</p>
                <p class="text-xl font-bold">{{ abConversionData }}</p>
            </div>
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-base font-semibold">Conversion Rate</p>
                <p class="text-xl font-bold">{{ abConversionRate }}%</p>
            </div>
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-base font-semibold">Unique Conversions</p>
                <p class="text-xl font-bold">{{ abUniqueConversions }}</p>
            </div>
            <div class="p-4 bg-gray-800 rounded-lg text-center">
                <p class="text-base font-semibold">Unique Conversion Rate</p>
                <p class="text-xl font-bold">{{ uniqueConversionRate }}%</p>
            </div>
        </div>
        <br>


        <div class="space-y-4">
            <!-- Version A Dropdown -->
            <div class="bg-gray-900 p-4 rounded-lg shadow-lg text-white">
                <button @click="toggleDropdown('A')"
                    class="w-full text-left text-lg font-bold flex justify-between items-center">
                    Version A
                    <svg :class="{ 'rotate-180': openA }" class="w-5 h-5 transition-transform transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div v-show="openA" class="mt-4">
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
                        <div class="p-3 bg-gray-800 rounded-lg text-center">
                            <p class="text-sm font-semibold">Unique Conversions</p>
                            <p class="text-xl font-bold">{{ abUniqueConversionsA }}</p>
                        </div>
                        <div class="p-3 bg-gray-800 rounded-lg text-center">
                            <p class="text-sm font-semibold">Unique Conversion Rate</p>
                            <p class="text-xl font-bold">{{ uniqueConversionRateA }}%</p>
                        </div>
                        <div class="p-3 bg-gray-800 rounded-lg text-center">
                            <p class="text-sm font-semibold">Mobile Visits : Desktop Visits</p>
                            <p class="text-xl font-bold">{{ abMobileVisitsA }}<span class="mx-4">:</span>{{
                                abDesktopVisitsA }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Version B Dropdown -->
            <div class="bg-gray-900 p-4 rounded-lg shadow-lg text-white">
                <button @click="toggleDropdown('B')"
                    class="w-full text-left text-lg font-bold flex justify-between items-center">
                    Version B
                    <svg :class="{ 'rotate-180': openB }" class="w-5 h-5 transition-transform transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div v-show="openB" class="mt-4">
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
                        <div class="p-3 bg-gray-800 rounded-lg text-center">
                            <p class="text-sm font-semibold">Unique Conversions</p>
                            <p class="text-xl font-bold">{{ abUniqueConversionsB }}</p>
                        </div>
                        <div class="p-3 bg-gray-800 rounded-lg text-center">
                            <p class="text-sm font-semibold">Unique Conversion Rate</p>
                            <p class="text-xl font-bold">{{ uniqueConversionRateB }}%</p>
                        </div>
                        <div class="p-3 bg-gray-800 rounded-lg text-center">
                            <p class="text-sm font-semibold">Mobile Visits : Desktop Visits</p>
                            <p class="text-xl font-bold">{{ abMobileVisitsB }}<span class="mx-4">:</span>{{
                                abDesktopVisitsB }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <br>
        <div class="overflow-x-auto">
            <DataTable :data="dataTableFiltered" :columns="columns" class="w-full border border-gray-600 rounded-lg"
                v-if="dataTableFiltered.length" :options="options">
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
            </DataTable>
            <!-- <table class="w-full border border-gray-600 rounded-lg" v-if="abTestData.length">
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
            </table> -->
            <p v-else>No data available for this user.</p>
        </div>
    </div>
</template>

<style>
table.dataTable th.dt-type-numeric,
table.dataTable th.dt-type-date,
table.dataTable td.dt-type-numeric,
table.dataTable td.dt-type-date {
    text-align: left;
}
</style>
