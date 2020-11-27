<template>
    <div>
        <div class="mb-4">
            <label for="search" class="block text-sm font-medium text-gray-700">
                Search by Title or Author
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input type="text" v-model="search" @input="getBookOptions" id="search" class="block w-full pr-10 border-gray-600 focus:outline-none sm:text-sm rounded-md" placeholder="you@example.com" value="adamwathan" aria-invalid="true" aria-describedby="email-error">

                <p class="mt-2 text-xs">Please enter at least 3 characters.</p>

                <p class="text-sm mt-2" v-if="searchResults.length">
                    Please select a book below to add to your list.
                </p>

                <div class=" mt-1 w-full rounded-md bg-white" v-if="searchResults.length">
                    <ul tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-item-3" class="max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                        <li id="listbox-item-0" role="option" v-for="book in searchResults" :key="book.id" @click="addBook(book)" class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9 group hover:bg-indigo-600 hover:text-white">
                            <div class="flex">
                                <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                                <span class="font-normal truncate">
                                    {{ book.title }}
                                </span>
                                <!-- Highlighted: "text-indigo-200", Not Highlighted: "text-gray-500" -->
                                <span class="ml-2 text-gray-500 group-hover:text-indigo-200 truncate">
                                    {{ book.author }}
                                </span>
                            </div>

                            <span class="absolute w-10 inset-y-0 right-0 hidden group-hover:flex items-center pr-4">
                                <!-- Heroicon name: plus -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                search: '',
                searchResults: []
            };
        },

        methods: {
            addBook(book) {
                axios.post(this.route('api.books.store'), { volume_id: book.id }).then(({data}) => {
                    this.$emit('add-book', data);
                }).catch((errors) => {
                    console.error(errors);
                })
            },

            getBookOptions: _.debounce(function(value) {
                if(value.length < 3 ) {
                    return;
                }

                axios.get(this.route('api.books.search'), { params: { search: this.search }}).then(({ data }) => {
                    this.$set(this, 'searchResults', data);
                }).catch((errors) => {
                    console.log(errors);
                })
            }, 200)
        }
    };
</script>
