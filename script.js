document.getElementById('converter-form').addEventListener('submit', async function(e){
    e.preventDefault();
    
    const btn = e.target.querySelector('.cnvrt-btn');
    const youtubeUrl = document.getElementById('youtube_url').value;
    const statusDiv = document.querySelector('.status-message');

    btn.disabled = true;
    btn.innerText = "Converting...";
    statusDiv.textContent = '';
    statusDiv.className = 'status-message';

    try {
        const response = await fetch('controllers/snapvibe_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'youtube_url=' + encodeURIComponent(youtubeUrl),
        });

        if (!response.ok) {
            throw new Error('Server error (' + response.status + ')');
        }

        const data = await response.json();

        if(data.status === 'success') {
            statusDiv.textContent = data.message;
            statusDiv.classList.add('success');

            window.location.href = 'controllers/download.php?file=' + encodeURIComponent(data.filename);
        } else {
            statusDiv.textContent = data.message;
            statusDiv.classList.add('error');
        }
    } catch (err) {
        statusDiv.textContent = 'Something went wrong. Please try again.';
        statusDiv.classList.add('error');
        console.error('Snapvibe error:', err);
    } finally {
        btn.disabled = false;
        btn.innerText = "Convert";
    }
});