const form = document.getElementById('git_url_form')
const formHandler = async (e) => {
    console.log(e)
    e.preventDefault(); // Prevent the default form submission
    console.log('form will be sent.')
    const formButton = document.getElementById('git_url_form_button')
    formButton.textContent = 'Deploying...'
    formButton.disabled = true

    const formData = new FormData(form);
    const url = 'http://vercel-local.com/api/upload';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData,
        });

        if (response.ok) {

            // `data` is going to be an id.
            const data = await response.text();
            // Store the id for when checking the id value after getting deployed status.
            sessionStorage.setItem('id', data);
            console.log(data);

        }

    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

form.addEventListener('submit', formHandler)
