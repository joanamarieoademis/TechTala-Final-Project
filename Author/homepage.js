const slider = document.getElementById('slider');
const previous = document.querySelector('.s-button.previous');
const next = document.querySelector('.s-button.next');

previous.addEventListener('click', () => {
    slider.scrollBy({ left: -320, behavior: 'smooth' });
});

next.addEventListener('click', () => {
    slider.scrollBy({ left: 320, behavior: 'smooth' });
});
