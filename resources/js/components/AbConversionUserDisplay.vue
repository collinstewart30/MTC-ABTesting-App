<script setup>
import { ref, onMounted, computed } from "vue";
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

// Filtered data based on user_session_id
const filteredData = computed(() => {
    return abTestData.value.filter(item => item.user_session_id == props.user_session_id);
});

// Datatable columns
const columns = [
    { data: 'user_session_id' },
    { data: 'conversion_type' },
    {
        data: 'created_at',
        render: (data) => formatDate(data), // Format the date in the DataTable
    }
];

const options = {
    order: [[2, "desc"]], // Sort by the 8th column (created_at) in descending order
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
    <div class="p-6">
        <h2 class="text-lg font-bold mb-6 text-blue-50">Conversion Data</h2>
        <div class="overflow-x-auto">
            <DataTable :data="filteredData" :columns="columns" class="w-full border border-gray-600 rounded-lg"
                v-if="filteredData.length" :options="options">
                <thead>
                    <tr class="bg-blue-50 text-black">
                        <th class="border border-gray-600 p-3">Session ID</th>
                        <th class="border border-gray-600 p-3">Conversion Type</th>
                        <th class="border border-gray-600 p-3">Created At</th>
                    </tr>
                </thead>
            </DataTable>
            <!-- <table class="w-full border border-gray-600 rounded-lg">
                <thead>
                    <tr class="bg-blue-50 text-black">
                        <th class="border border-gray-600 p-3">ID</th>
                        <th class="border border-gray-600 p-3">User Session ID</th>
                        <th class="border border-gray-600 p-3">Conversion Type</th>
                        <th class="border border-gray-600 p-3">Conversion Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in abTestData.filter(item => item.user_session_id == props.user_session_id)"
                        :key="item.id" class="border border-gray-600 even:bg-gray-800 odd:bg-gray-900 text-white">
                        <td class="border border-gray-600 p-3">{{ item.id }}</td>
                        <td class="border border-gray-600 p-3">{{ item.user_session_id }}</td>
                        <td class="border border-gray-600 p-3">{{ item.conversion_type }}</td>
                        <td class="border border-gray-600 p-3">{{ formatDate(item.created_at) }}</td>
                    </tr>
                </tbody>
            </table> -->
        </div>
    </div>
</template>
