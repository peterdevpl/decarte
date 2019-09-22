const sliderImages = document.querySelectorAll('#slider img');
if (sliderImages.length > 1) {
    const thumbnails = document.createElement('ul');
    thumbnails.id = 'slider-thumbnails';
    sliderImages.forEach(function (image, index) {
        const listItem = document.createElement('li');
        const thumbnail = document.createElement('img');
        thumbnail.src = image.src;
        thumbnail.setAttribute('data-index', (index + 1).toString());
        thumbnail.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const imagesOff = document.querySelectorAll('#slider img:not(:nth-child(' + index + '))');
            const imageOn = document.querySelector('#slider img:nth-child(' + index + ')');
            imagesOff.forEach(function(image) {
                image.style.opacity = 0;
            });
            imageOn.style.opacity = 1;
        });
        listItem.appendChild(thumbnail);
        thumbnails.appendChild(listItem);
    });

    const slider = document.getElementById('slider');
    slider.parentNode.insertBefore(thumbnails, slider.nextSibling);
}
