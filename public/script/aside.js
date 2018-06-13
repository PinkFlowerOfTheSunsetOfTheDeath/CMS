(function () {
    var searchBar = document.querySelector('.aside__searchBar'),
        searchBarContainer = document.querySelector('.aside__searchBar__container');

    searchBar.addEventListener('focus', function () {
        searchBarContainer.classList.add('aside__searchBar__container--active')
    });

    searchBar.addEventListener('blur', function () {
        searchBarContainer.classList.remove('aside__searchBar__container--active')
    })

})();