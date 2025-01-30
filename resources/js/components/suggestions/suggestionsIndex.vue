<template>
    <div class="p-6 max-w-2xl mx-auto">
        <!-- Search Input -->
        <input v-model="searchTerm" type="text" placeholder="Search suggestions..."
            class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            @input="handleSearch" />

        <!-- Suggestions List -->
        <div v-if="filteredSuggestions.length" class="mt-6 space-y-4">
            <div v-for="suggestion in filteredSuggestions" :key="suggestion.id"
                class="p-4 border border-gray-200 rounded-lg shadow-sm">
                <!-- Highlighted Title -->
                <h2 class="text-xl font-semibold" v-html="highlightText(suggestion.title)"></h2>
                <!-- Highlighted Content -->
                <div class="text-gray-700 mt-2" v-html="highlightText(suggestion.content)"></div>
            </div>
        </div>

        <!-- No Results Message -->
        <div v-else class="mt-6 text-gray-500">
            No suggestions found.
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// Reactive state
const searchTerm = ref('');
const suggestions = ref([]);

// Fetch suggestions from the API
const fetchSuggestions = async (searchTerm = '') => {
    try {
        const baseUrl = window.apiBaseUrl;
        const filter = buildFilterParam(searchTerm);
        const url = `${baseUrl}/api/suggestion?filter=${encodeURIComponent(filter)}`;
        const response = await fetch(url);
        const result = await response.json();
        console.log('API Response:', result);

        suggestions.value = result.data; // Extract the array from the response
    } catch (error) {
        console.error('Error fetching suggestions:', error);
        suggestions.value = []; // Fallback to an empty array on error
    }
};

// Fetch suggestions on component mount
onMounted(fetchSuggestions);

// Filter suggestions based on search term
const filteredSuggestions = computed(() => {
    if (!Array.isArray(suggestions.value)) return []; // Fallback if suggestions is not an array
    if (!searchTerm.value) return suggestions.value;

    const term = searchTerm.value.toLowerCase();
    return suggestions.value.filter(
        (suggestion) =>
            suggestion.title.toLowerCase().includes(term) ||
            suggestion.content.toLowerCase().includes(term)
    );
});

// Highlight search term in text
const highlightText = (text) => {
    if (!searchTerm.value) return text;

    const term = searchTerm.value;
    const regex = new RegExp(`(${term})`, 'gi');
    return text.replace(regex, '<span class="bg-yellow-200">$1</span>');
};

//
const buildFilterParam = (searchTerm) => {
    return JSON.stringify([
        {
            name: 'any',
            modifiers: [
                { name: 'fields', params: ['title', 'content'] },
                { name: 'contains', params: [searchTerm] },
            ],
        },
    ]);
};

// Handle search input
const handleSearch = () => {
    fetchSuggestions(searchTerm.value);
};
</script>
