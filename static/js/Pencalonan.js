document.getElementById('calendar').addEventListener('change', function () {
    const calendar = document.getElementById('calendar');
    const formSection = document.getElementById('formSection');

    if (calendar.value) {
        formSection.style.display = 'block';
    } else {
        formSection.style.display = 'none';
    }
});

document.getElementById('submitButton').addEventListener('click', function () {
    const selectedDate = document.getElementById('calendar').value;
    const time = document.getElementById('time').value;
    const venue = document.getElementById('venue').value;

    if (selectedDate && time && venue) {
        alert(`Tarikh: ${selectedDate}\nMasa: ${time}\nTempat: ${venue}`);
    } else {
        alert('Sila lengkapkan semua maklumat.');
    }
});
