<template>
    <div class="p-6 max-w-2xl mx-auto">
        <!-- Search Input with Clear Button Inside -->
        <div class="relative">
            <input v-model="searchTerm" type="text" placeholder="Search suggestions..."
                class="w-full p-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                aria-live="polite" :aria-label="'Search suggestions'" ref="searchInput" />
            <!-- Clear button inside input -->
            <span v-if="searchTerm" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                @click="clearSearch">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </span>
        </div>

        <!-- Loading Spinner -->
        <div v-if="isLoading" class="flex justify-center items-center mt-4">
            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                <path fill="currentColor" d="M4 12a8 8 0 1116 0A8 8 0 014 12z" class="opacity-75"></path>
            </svg>
        </div>

        <!-- Suggestions List -->
        <div v-if="filteredSuggestions.length > 0" class="mt-4 space-y-4">
            <div v-for="suggestion in filteredSuggestions" :key="suggestion.id"
                class="p-4 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition duration-200">
                <!-- Highlighted Title -->
                <h2 class="text-xl font-semibold" v-html="highlightText(suggestion.title)"></h2>
                <!-- Highlighted Content -->
                <div class="text-gray-700 mt-2" v-html="highlightText(suggestion.content)"></div>
                <div class="text-sm text-gray-400 mt-2">
                    <span>{{ formatDate(suggestion.created) }}</span>
                    <span v-if="suggestion?._totalLikes"> - {{ suggestion._totalLikes }} Likes</span>
                    <span v-if="suggestion?._totalComments"> - {{ suggestion._totalComments }} Comments</span>
                </div>
            </div>
        </div>

        <!-- No Results Message -->
        <div v-else class="mt-6 text-gray-500">
            No suggestions matched your search, try a different query.
        </div>

        <!-- Pagination Controls -->
        <div v-if="totalPages > 1" class="flex justify-between mt-6">
            <button :disabled="currentPage === 1" @click="goToPage(currentPage - 1)"
                class="px-4 py-2 bg-gray-200 rounded-lg disabled:opacity-50">
                Previous
            </button>
            <div class="flex items-center space-x-2">
                <button v-for="page in pageNumbers" :key="page"
                    :class="{ 'bg-blue-500 text-white': currentPage === page, 'text-blue-500': currentPage !== page }"
                    @click="goToPage(page)" class="px-4 py-2 rounded-lg hover:bg-blue-100">
                    {{ page }}
                </button>
            </div>
            <button :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)"
                class="px-4 py-2 bg-gray-200 rounded-lg disabled:opacity-50">
                Next
            </button>
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="mt-6 text-red-500">
            {{ errorMessage }}
            <button @click="retryFetch" class="text-blue-500 hover:underline ml-2">Retry</button>
        </div>
    </div>
</template>

<script setup>
import useSuggestions from '../../composables/suggestions';
import { onMounted } from 'vue';
// Destructure the returned values from the composable
const { searchTerm, filteredSuggestions, isLoading, errorMessage, currentPage, totalPages, getSuggestions, highlightText, clearSearch, retryFetch, formatDate, goToPage, pageNumbers } = useSuggestions();

// Fetch suggestions on component mount
onMounted(() => {
    getSuggestions('', 1);
});
</script>
