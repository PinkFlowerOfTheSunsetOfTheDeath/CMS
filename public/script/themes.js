(function () {

    var links = document.querySelectorAll('.link__theme');
    var preview = document.querySelector('.themes__preview__image');
    var itemActive = document.querySelector('.themes__item--active');

    setImage(itemActive);

    function setImage(element) {
        element.classList.add('themes__item--active');
        var img = element.querySelector('img');
        var attr = img.getAttribute('src');
        preview.setAttribute('src', attr);
        for (let b = 0; b < links.length; b++) {
            links[b].classList.remove('themes__item--active')
        }
    }

    for (let i = 0; i < links.length; i++) {
        links[i].addEventListener('click', function (e) {
            e.preventDefault();
            setImage(links[i]);
        })
    }

    var validateButton = document.getElementById('validateTheme');

    validateButton.addEventListener('click', function () {
        var element = document.querySelector('.themes__item--active > p');
        var text = element.innerText;
        var html = document.createElement('a');
        var url = '/admin/themes/' + text + '/select';
        html.setAttribute('href', url);
        html.setAttribute('id', 'setThemesLink');
        var body = document.querySelector('body');
        body.appendChild(html);
        document.getElementById('setThemesLink').click();
    })

})();