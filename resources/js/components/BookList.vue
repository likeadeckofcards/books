<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <search-books @add-book="addBook"></search-books>

        <div class="flex flex-row-reverse">
            <select class="mb-2" v-model="sort">
                <option value="order">Reading Order</option>
                <option value="title">Title</option>
                <option value="author">Author</option>
            </select>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Author
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Published On
                                    </th>
                                    <th scope="col" class="px-6 py-3 bg-gray-50">
                                        <span class="sr-only">View</span>
                                    </th>
                                </tr>
                                </thead>
                                <draggable tag="tbody" :disabled="sort !== 'order'" :value="books" group="books"
                                           @change="changeOrder">
                                    <!-- Odd row -->
                                    <tr :class="index % 2 ? 'bg-gray-50' : 'bg-white'" v-for="(book, index) in books">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ book.title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ book.author }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ book.published_on | moment }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a :href="route('books.show', book.id)" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            <a @click="deleteBook(book)" class="text-indigo-600 hover:text-indigo-900">Delete</a>
                                        </td>
                                    </tr>
                                </draggable>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SearchBooks from './SearchBooks';
import draggable from 'vuedraggable';

export default {
    data() {
        return {
            books: [],
            sort: 'order',
        };
    },

    components: {
        draggable, SearchBooks,
    },

    computed: {
        params() {
            return {
                order: this.sort,
            };
        }
    },

    methods: {
        addBook(book) {
            this.books.push(book);
        },

        changeOrder(event) {
            axios.patch(this.route('api.books.update', event.moved.element.id), { newOrder: event.moved.newIndex + 1 }).then(({ data }) => {
                this.getBooks();
            }).catch((errors) => {
                console.error(errors)
            });
        },

        deleteBook(book) {
            axios.delete(this.route('api.books.destroy', book.id)).then(() => {
                let index = this.books.findIndex((item) => {
                    return item.id === book.id
                });

                this.books.splice(index, 1);
            }).catch((errors) => {
                console.error(errors);
            })
        },

        getBooks() {
            axios.get(this.route('api.books.index'), {params: this.params}).then(({ data }) => {
                this.$set(this, 'books', data);
            })
        },
    },

    created() {
        this.getBooks();
    },

    watch: {
        sort() {
            this.getBooks();
        }
    }
};
</script>
