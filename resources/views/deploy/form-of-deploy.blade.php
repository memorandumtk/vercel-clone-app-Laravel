{{--Deploy form with description text--}}
<div class="flex flex-col gap-4 text-gray-900 dark:text-gray-100">
    <div>
        <div class="text-lg">Deploy Your React Project</div>
        <div class="text-sm text-gray-700 dark:text-gray-300">
            Enter the URL of your GitHub repository to deploy it
        </div>
    </div>
    <form id="git_url_form">
        <x-input-label for="git_url" :value="__('Your Github Repository Link')"/>
        <x-text-input id="git_url" class="block mt-2 w-full" type="text" name="git_url"
                      :value="old('git_url')" required autofocus autocomplete="git_url"/>
        <x-input-error :messages="$errors->get('git_url')" class="mt-2"/>
        <div class="flex items-center justify-center mt-4">
            <button id="git_url_form_button" type='submit'
                    class='items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md text-white dark:text-gray-800 uppercase font-semibold tracking-wider hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 w-full'>
                Deploy
            </button>
        </div>
    </form>
</div>

