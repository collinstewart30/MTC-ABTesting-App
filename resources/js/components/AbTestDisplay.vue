<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const abTestData = ref([]);

const fetchData = async () => {
    try {
        const response = await axios.get("/ab-test-data");
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

//Datatable
const columns = [
    { data: 'test_id' },
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
        render: (data) => formatDate(data), // Only format for display
    }
];
const options = {
    order: [[6, "desc"]], // Sort by the 7th column (created_at) in descending order
    pageLength: 25,
    rowCallback: (row, data, index) => {
        row.classList.add("!border");
        row.classList.add("!border-gray-600");
        row.classList.add("even:!bg-gray-800");
        row.classList.add("odd:!bg-gray-900");
    },
    columnDefs: [
        {
            targets: 6, // Target the created_at column
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
    <div class="min-h-screen p-6">
        <div class="overflow-x-auto">
            <DataTable :data="abTestData" :columns="columns" class="w-full border border-gray-600 rounded-lg"
                v-if="abTestData.length" :options="options">
                <thead>
                    <tr class="bg-blue-50 text-black">
                        <th class="border border-gray-600 p-3">Test ID</th>
                        <th class="border border-gray-600 p-3">Session ID</th>
                        <th class="border border-gray-600 p-3">Version</th>
                        <th class="border border-gray-600 p-3">Page Views</th>
                        <th class="border border-gray-600 p-3">Conversions</th>
                        <th class="border border-gray-600 p-3">Current URL</th>
                        <th class="border border-gray-600 p-3">Created At</th>
                    </tr>
                </thead>
            </DataTable>
            <!-- <table class="w-full border border-gray-600 rounded-lg">
                <thead>
                    <tr class="bg-blue-50 text-black">
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
                    <tr v-for="item in abTestData" :key="item.id"
                        class="border border-gray-600 even:bg-gray-800 odd:bg-gray-900 text-white">
                        <td class="border border-gray-600 p-3">{{ item.test_id }}</td>
                        <td class="border border-gray-600 p-3"><a :href="`/user-data/${item.user_session_id}`"
                                class="underline hover:text-gray-400">{{ item.user_session_id }}</a></td>
                        <td class="border border-gray-600 p-3">{{ item.version }}</td>
                        <td class="border border-gray-600 p-3">{{ item.page_views }}</td>
                        <td class="border border-gray-600 p-3">{{ item.conversions }}</td>
                        <td class="border border-gray-600 p-3">{{ item.current_url }}</td>
                        <td class="border border-gray-600 p-3">{{ formatDate(item.created_at) }}</td>
                    </tr>
                </tbody>
            </table> -->
        </div>
    </div>
</template>

<style>
table.dataTable th.dt-type-numeric, table.dataTable th.dt-type-date, table.dataTable td.dt-type-numeric, table.dataTable td.dt-type-date {
    text-align: left;
}
</style>