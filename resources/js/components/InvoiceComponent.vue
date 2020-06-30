<template>
    <div>

        <div class="inline-flex rounded-md shadow mb-2">
            <router-link to="/"
                         class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                List of invoices
            </router-link>
        </div>

        <div class="flex flex-col">

            <Spinner v-if="isLoading"/>
            <div v-else class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Invoice Information
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                        Details and amount.
                    </p>
                </div>
                <div>
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                School name
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                {{invoice.name}}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                Description
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                {{invoice.description}}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                Email address
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                {{invoice.email}}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                Amount
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                ${{invoice.amount}}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">

                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                <div class="inline-flex rounded-md shadow mb-2">
                                    <a :href="'/invoice/pay/' + invoice.id"
                                       v-if="invoice.status === 0"
                                       class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                        Pay
                                    </a>
                                    <p v-else
                                       class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Payment received
                                    </p>
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import Modal from "./Modal";
    import Spinner from "./Spinner";

    export default {
        name: "InvoiceComponent",
        components: {Spinner, Modal},
        data: () => ({
            invoice: {},
            isLoading: false,
        }),
        mounted() {
            this.getInvoice();
        },
        methods: {
            getInvoice() {
                this.isLoading = true;
                axios.get('/api/invoices/' + this.$route.params.id).then(response => {
                    this.isLoading = false;
                    this.invoice = response.data.data;
                })
            }
        },
    }
</script>

<style scoped>

</style>
