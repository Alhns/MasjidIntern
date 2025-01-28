document.getElementById('signInButton').addEventListener('click', function () {
    const masjidIdInput = document.getElementById('masjidId');
    const errorMessage = document.getElementById('errorMessage');

    if (masjidIdInput.value.trim() === '') {
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
        window.location.href = 'next-page.html';
    }
});

document.getElementById('signUpButton').addEventListener('click', function () {
    alert('Redirecting to the Sign-Up page.');
});
