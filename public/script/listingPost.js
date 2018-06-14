(function () {
   var deleteButtons = document.querySelectorAll('.table__button--delete');


    var createModal = function () {
        var container = document.createElement('div');

        var s = "Etes vous sur de vouloir supprimer l'article :";

        var parent = this.closest('.table__parent');
        var id = parent.querySelector('.table__id').textContent;
        var title = parent.querySelector('.table__title').textContent;

        var html = '<div><div><span>close</span><h3>' + s + id + ' ' + title + '</h3><div><button class="table__button table__button--delete">Delete</button></div></div>';
        console.log('parent', parent);
    };

   for (let i = 0; i<deleteButtons.length; i++) {
       deleteButtons[i].addEventListener('click', createModal);
   }

})();