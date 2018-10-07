require('../scss/main.scss');

let sliderImages = document.querySelectorAll('#slider img');
if (sliderImages.length > 1) {
    let thumbnails = document.createElement('ul');
    thumbnails.id = 'slider-thumbnails';
    sliderImages.forEach(function (image, index) {
        let listItem = document.createElement('li');
        let thumbnail = document.createElement('img');
        thumbnail.src = image.src;
        thumbnail.setAttribute('data-index', (index + 1).toString());
        thumbnail.addEventListener('click', function() {
            let index = this.getAttribute('data-index');
            let imagesOff = document.querySelectorAll('#slider img:not(:nth-child(' + index + '))');
            let imageOn = document.querySelector('#slider img:nth-child(' + index + ')');
            imagesOff.forEach(function(image) {
                image.style.opacity = 0;
            });
            imageOn.style.opacity = 1;
        });
        listItem.appendChild(thumbnail);
        thumbnails.appendChild(listItem);
    });

    let slider = document.getElementById('slider');
    slider.parentNode.insertBefore(thumbnails, slider.nextSibling);
}
