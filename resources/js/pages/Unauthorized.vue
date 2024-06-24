<template>
    <div class="flex justify-center items-center h-screen w-full bg-gray-200">
        <div
            class="relative transform overflow-hidden rounded-lg  px-4 pb-4 pt-5 text-left transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
            <!-- <div> -->
            <div class="mt-3 text-center sm:mt-5">
                <div class="text-base font-semibold leading-6 text-gray-900">Non Autorisé
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Veuillez vérifier vos accès à Kiamo</p>
                </div>
            </div>
            <!-- </div> -->
            <div class="mt-2 sm:mt-6 flex justify-center">
                <button @click="tryAgain" type="button" class="inline-flex w-1/2 justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-secondaryHover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Réessayer</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "Unauthorized",
    methods: {
        tryAgain() {
            window.location.href = "/home";
        }
    }
}
</script>
