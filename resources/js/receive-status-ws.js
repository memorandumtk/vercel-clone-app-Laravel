window.onload = function() {
    Echo.channel('deploy-status')
        .listen('DeployStatusMessage', (e) => {
            console.log(e); // This will log the entire event object
            window.dispatchEvent(new CustomEvent('flash', { detail: e }));
        });

    window.addEventListener('flash', function(event) {
        const { id, message } = event.detail; // Destructure the id and message from event.detail
        console.log(`${id}: ${message}`); // Log id and message

        try {
            const idStoredSS = sessionStorage.getItem('id')

            // Check if id is the same as the id having been stored in session storage when its upload had done
            // and if the message has 'deployed' string.
            // If the above condition is correct, make 'deploy-status-div' visible.
            if (id === idStoredSS && message.includes('deployed')) {
                const deployedUrl = `http://${id}.vercel-local.com/index.html`
                document.getElementById('deploy-status-div').style.display = 'block';
                document.getElementById('deployed-url').value = deployedUrl
                const formButton = document.getElementById('git_url_form_button')
                formButton.textContent = 'Deployed'
                formButton.disabled = false
                document.getElementById('button-in-status').href = deployedUrl
            } else {
                document.getElementById('deploy-status-div').style.display = 'none';
            }
        } catch (e){
           console.error(e)
        }
    });
};
