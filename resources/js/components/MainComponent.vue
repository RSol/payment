<template>
    <div class="flex flex-col">

        <Modal @added="getList"/>

        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <Spinner v-if="isLoading"/>
                <table class="min-w-full" v-else>
                    <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            School name
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Payment status
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    <tr v-for="invoice in invoices" :key="invoice.id">
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm leading-5 font-medium text-gray-900">
                                        {{invoice.name}}
                                    </div>
                                    <div class="text-sm leading-5 text-gray-500">{{invoice.email}}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            {{invoice.description}}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            {{invoice.amount}}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="{'bg-green-100 text-green-800': invoice.status, 'bg-red-100 text-red-800': !invoice.status}">
                                {{invoice.status ? 'Payment received' : 'Invoice sent'}}
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                            <router-link :to="'/invoice/' + invoice.id" class="text-indigo-600 hover:text-indigo-900">Show
                            </router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Modal from "./Modal";
    import Spinner from "./Spinner";

    export default {
        name: "MainComponent",
        components: {Spinner, Modal},
        data: () => ({
            invoices: [],
            isLoading: false,
        }),
        mounted() {
            this.getList();
        },
        methods: {
            getList() {
                this.isLoading = true;
                axios.get('/api/invoices').then(response => {
                    this.isLoading = false;
                    this.invoices = response.data.data;
                })
            }
        },
    }
</script>

<style scoped>

</style>
