<x-main-layout>
    <h2 class="font-semibold text-xl text-center text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Vercel Clone App') }}
    </h2>

    <div class="py-6 flex flex-col gap-6">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-gray-200 dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                @include('deploy.form-of-deploy')
            </div>
        </div>
        <div id="deploy-status-div" style="display: none;" class="max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-gray-200 dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                @include('deploy.status-of-deploy')
            </div>
        </div>
    </div>
</x-main-layout>
