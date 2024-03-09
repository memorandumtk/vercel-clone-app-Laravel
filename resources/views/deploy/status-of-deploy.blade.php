{{--Status check with text--}}
<div class="flex flex-col gap-4 text-gray-900 dark:text-gray-100">
    <div>
        <div class="text-lg">Deployment Status</div>
        <div class="text-sm text-gray-700 dark:text-gray-300">
            Your website is successfully deployed!
        </div>
    </div>
    <div>
        <x-input-label for="deployed-url" value="Let's visit the URL below!"/>
        <x-text-input id="deployed-url" readonly class="block mt-2 w-full" type="text" name="deployed-url"
                      :value="old('link')" required autofocus/>
        <div class="flex items-center justify-center mt-4">
            <a id="button-in-status" href="" target="_blank" class='inline-block items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md text-white dark:text-gray-800 uppercase font-semibold tracking-wider  hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 w-full text-center'>
                Go To React Page!
            </a>
        </div>
    </div>
</div>
