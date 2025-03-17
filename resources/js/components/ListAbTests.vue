<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const abTestData = ref([]);

const fetchData = async () => {
    try {
        const response = await axios.get("/list-ab-tests");
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

// Datatable columns
const columns = [
    {
        data: 'test_id',
        render: (data) => {
            return `<a href="/ab-tests/${data}" class="underline hover:text-gray-400">${data}</a>`;
        },
    }
];

const options = {
    rowCallback: (row) => {
        row.classList.add('!border');
        row.classList.add('!border-gray-600');
        row.classList.add('even:!bg-gray-800');
        row.classList.add('odd:!bg-gray-900');
    }
};
</script>

<template>
    <div class="p-6">
        <div class="overflow-x-auto">
            <DataTable :data="abTestData" :columns="columns" class="w-full border border-gray-600 rounded-lg"
                v-if="abTestData.length" :options="options">
                <thead>
                    <tr class="bg-blue-50 text-black">
                        <th class="border border-gray-600 p-3">A/B Tests</th>
                    </tr>
                </thead>
            </DataTable>
            <!-- <table class="w-full border border-gray-600 rounded-lg">
                <thead>
                    <tr class="bg-blue-50 text-black">
                        <th class="border border-gray-600 p-3">A/B Tests</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in abTestData" :key="item.id"
                        class="border border-gray-600 even:bg-gray-800 odd:bg-gray-900 text-white">
                        <td class="border border-gray-600 p-3"><a :href="`/ab-tests/${item.test_id}`"
                                class="underline hover:text-gray-400">{{ item.test_id }}</a></td>
                    </tr>
                </tbody>
            </table> -->
        </div>
    </div>
</template>
