<template>
    <div>

        <div class="inline-flex rounded-md shadow mb-2">
            <a href="#"
               @click.prevent="isOpen = !isOpen"
               class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                New invoice
            </a>
        </div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-100"
            leave-class="opacity-100"
            leave-to-class="opacity-0"
        >

            <div v-show="isOpen"
                 class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div
                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full"
                    role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                    New invoice
                                </h3>
                                <div class="mt-2">
                                    <form action="#" method="POST">
                                        <input type="hidden" name="remember" value="true"/>
                                        <div class="rounded-md shadow-sm">
                                            <div class="mb-1">
                                                <input aria-label="School name" name="name" required
                                                       v-model="name"
                                                       class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
                                                       placeholder="School name"/>
                                                <small v-if="errors.name" class="text-orange-600">
                                                    {{errors.name[0]}}
                                                </small>
                                            </div>
                                            <div class="mb-1">
                                                <input aria-label="Email" name="email" type="email" required
                                                       v-model="email"
                                                       class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
                                                       placeholder="Email"/>
                                                <small v-if="errors.email" class="text-orange-600">
                                                    {{errors.email[0]}}
                                                </small>
                                            </div>
                                            <div class="mb-1">
                                                <input aria-label="Amount" name="amount" required
                                                       v-model="amount"
                                                       class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
                                                       placeholder="Amount"/>
                                                <small v-if="errors.amount" class="text-orange-600">
                                                    {{errors.amount[0]}}
                                                </small>
                                            </div>
                                            <div class="-mt-px">
                                                <textarea aria-label="Description" name="description" required
                                                          v-model="description"
                                                          class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
                                                          placeholder="Description">
                                                </textarea>
                                                <small v-if="errors.description" class="text-orange-600">
                                                    {{errors.description[0]}}
                                                </small>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
      <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
        <button type="button"
                @click="newInvoice"
                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5">
          Send
        </button>
      </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
        <button type="button"
                @click="isOpen = false"
                class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
          Cancel
        </button>
      </span>
                    </div>
                </div>
            </div>

        </transition>
    </div>
</template>

<script>
    export default {
        name: "Modal",
        data: () => ({
            isOpen: false,
            name: '',
            email: '',
            amount: '',
            description: '',
            errors: []
        }),
        methods: {
            resetForm() {
                this.name = '';
                this.email = '';
                this.amount = '';
                this.description = '';
            },
            newInvoice() {
                axios.post('/api/invoices', {
                    name: this.name,
                    email: this.email,
                    amount: this.amount,
                    description: this.description,
                }).then(response => {
                    this.isOpen = false;
                    this.resetForm();
                    this.$emit('added');
                    return response;
                }).catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            }
        },
    }
</script>

<style scoped>

</style>
