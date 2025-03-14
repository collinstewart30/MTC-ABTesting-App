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

//Datatable
const columns = [
    {
        data: "user_session_id",
        render: (data) => {
            return `<a href="/user-data/${data}" class="underline hover:text-gray-400">${data}</a>`;
        }
    },
    { data: 'conversion_type' },
    {
        data: 'created_at',
        render: (data) => formatDate(data), // Only format for display
    }
];
const options = {
    order: [[2, "desc"]], // Sort by the 3rd column (created_at) in descending order
    pageLength: 25,
    rowCallback: (row, data, index) => {
        row.classList.add("!border");
        row.classList.add("!border-gray-600");
        row.classList.add("even:!bg-gray-800");
        row.classList.add("odd:!bg-gray-900");
    },
    columnDefs: [
        {
            targets: 2, // Target the created_at column
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
        <h2 class="text-xl font-bold mb-6 text-blue-50">A/B Conversion Data</h2>
        <div class="overflow-x-auto">
            <DataTable :data="abTestData" :columns="columns" class="w-full border border-gray-600 rounded-lg"
                v-if="abTestData.length" :options="options">
                <thead>
                    <tr class="bg-blue-50 text-black">
                        <th class="border border-gray-600 p-3">User Session ID</th>
                        <th class="border border-gray-600 p-3">Conversion Type</th>
                        <th class="border border-gray-600 p-3">Conversion Date</th>
                    </tr>
                </thead>
            </DataTable>
            <!-- <table class="w-full border border-gray-600 rounded-lg">
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
                        <td class="border border-gray-600 p-3"><a :href="`/user-data/${item.user_session_id}`"
                                class="underline hover:text-gray-400">{{ item.user_session_id }}</a></td>
                        <td class="border border-gray-600 p-3">{{ item.conversion_type }}</td>
                        <td class="border border-gray-600 p-3">{{ formatDate(item.created_at) }}</td>
                    </tr>
                </tbody>
            </table> -->
        </div>
    </div>
</template>
