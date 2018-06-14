(function () {
    var deleteButtons = document.querySelectorAll('.table__button--delete');
    var modal = document.getElementById('modal');


    var createModal = function (e) {
        console.log(e);
        e.preventDefault();
        var href = this.getAttribute('href');
        var container = document.createElement('div');
        var s = "Are you sure to delete the article ?";
        var parent = this.closest('.table__parent');
        var id = parent.querySelector('.table__id').textContent;
        var title = parent.querySelector('.table__title').textContent;
        container.classList.add('modal__container');
        container.innerHTML = "<span class='modal__container__close'>x</span><p>" + s + "</p><h3 class='modal__container__title'>" + id + ' ' + title + "</h3><div class='modal__button'><a href=" + href + " class='rkmd-btn btn-pink ripple-effect btn-lg'>Delete</a></div></div>";
        modal.appendChild(container);
        modal.classList.add('modal--active');

        var deleteModal = function (e) {
            if (e.target.classList.contains('modal') || e.target.classList.contains('modal__container__close')) {
                modal.removeChild(container);
                modal.classList.remove('modal--active');
            }
        };
        modal.querySelector('.modal__container__close').addEventListener('click', deleteModal);
        modal.addEventListener('click', deleteModal);
    };


    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', createModal);
    }

})();