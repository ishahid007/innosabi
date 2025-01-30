import { ref, watch, computed } from 'vue'
import debounce from 'lodash/debounce';
import axios from 'axios'

export default function useSuggestions() {

    // Reactive state
    const searchTerm = ref('');
    const suggestions = ref([]);
    const filteredSuggestions = ref([]);
    const isLoading = ref(false);
    const errorMessage = ref('');
    const currentPage = ref(1); // Track the current page
    const totalPages = ref(1); // Total number of pages
    const perPage = ref(10); // Number of items per page (adjustable)
    const filterParam = ref(''); // Store the filter in a ref for memoization

    // Watch search term and update filtered suggestions
    watch(searchTerm, debounce((newTerm) => {
        filterParam.value = buildFilterParam(newTerm); // Only update filter if search term changes
        getSuggestions(newTerm, 1); // Fetch results for page 1 when search term changes
    }, 500)); // Debounced search term

    // Fetch suggestions from the API
    const getSuggestions = async (searchTerm = '', page = 1) => {
        isLoading.value = true;
        errorMessage.value = ''; // Reset error message
        try {
            const baseUrl = window.apiBaseUrl;
            const filter = buildFilterParam(searchTerm);
            const url = `${baseUrl}/api/suggestion?filter=${encodeURIComponent(filter)}&page=${page}&limit=${perPage.value}&order=id:desc`;

            let response = await axios.get(url);
            let result = response.data;

            if (response.status == 200) {
                suggestions.value = result.data; // Extract the suggestions from the response
                filteredSuggestions.value = suggestions.value;
                totalPages.value = result.meta.total_pages; // Set the total number of pages
                currentPage.value = result.meta.current_page; // Set the current page
            } else {
                if (response.status === 404) {
                    errorMessage.value = 'No suggestions found.';
                } else if (response.status === 500) {
                    errorMessage.value = 'Server error. Please try again later.';
                } else {
                    errorMessage.value = 'Failed to load suggestions.';
                }
            }
        } catch (error) {
            handleError(error.response?.status);
        } finally {
            isLoading.value = false;
        }
    };

    // Handle different types of errors
    const handleError = (status) => {
        if (status === 404) {
            errorMessage.value = 'No suggestions found.';
        } else if (status === 500) {
            errorMessage.value = 'Server error. Please try again later.';
        } else {
            errorMessage.value = 'Failed to load suggestions.';
        }
    };

    // Highlight search term in text
    const highlightText = (text) => {
        if (!searchTerm.value) return text;

        const term = searchTerm.value;
        return text.replaceAll(term, `<span class="bg-yellow-200">${term}</span>`);
    };

    // Build filter param for API call
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

    // Clear search term and reset suggestions
    const clearSearch = () => {
        searchTerm.value = '';
        filterParam.value = ''; // Reset memoized filter when clearing search
        getSuggestions('', 1); // Fetch first page results when clearing search
    };

    // Retry fetching suggestions
    const retryFetch = () => {
        getSuggestions(searchTerm.value, currentPage.value);
    };

    // Format date
    const formatDate = (dateObj) => {
        const date = new Date(dateObj.date);
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    };

    // Pagination controls
    const goToPage = (page) => {
        if (page >= 1 && page <= totalPages.value) {
            currentPage.value = page;
            getSuggestions(searchTerm.value, page); // Fetch suggestions for the new page
        }
    };

    // Get page numbers to display in pagination controls (max 5 pages)
    const pageNumbers = computed(() => {
        const pages = [];
        const maxPages = 5; // Maximum number of pages to display in pagination
        let start = Math.max(currentPage.value - Math.floor(maxPages / 2), 1);
        let end = start + maxPages - 1;

        // Adjust the range to ensure it doesn't exceed totalPages
        if (end > totalPages.value) {
            end = totalPages.value;
            start = Math.max(end - maxPages + 1, 1);
        }

        // Fill the array with page numbers
        for (let i = start; i <= end; i++) {
            pages.push(i);
        }

        return pages;
    });

    // Return the composable API
    return {
        searchTerm,
        filteredSuggestions,
        isLoading,
        errorMessage,
        currentPage,
        totalPages,
        getSuggestions,
        highlightText,
        clearSearch,
        retryFetch,
        formatDate,
        goToPage,
        pageNumbers,
    };

}
