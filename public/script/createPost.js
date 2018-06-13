(function () {

    var tags = Array.prototype.slice.call(document.querySelectorAll('.articleList__aside__tags--selectable'));
    var tagsActif = Array.prototype.slice.call(document.querySelectorAll('.articleList__aside__tags--actifs'));

    var parent = document.getElementById('insertTagsActif');

    function init() {
        for (let i = 0; i < tags.length; i++) {
            tags[i].addEventListener('click', function () {
                var element = createElement(tags[i].innerText, tags[i].getAttribute('data-pos'));
                parent.appendChild(element);
                tagsActif.splice(tagsActif.length, 1, tags[i]);
                tags[i].classList.add('articleList__aside__tags--inactive');
            })
        }
    }

    var removeActiveTag = function () {
        var pos = this.getAttribute('data-pos');
        tags[parseFloat(pos)].classList.remove('articleList__aside__tags--inactive');
        this.parentNode.removeChild(this);
    };

    function createElement(value, pos) {
        var para = document.createElement("li");
        var span = document.createElement("span");
        para.classList.add('articleList__aside__tags', 'articleList__aside__tags--actifs');
        var node = document.createTextNode(value + '');
        span.appendChild(node);
        para.appendChild(span);
        para.setAttribute('data-pos', pos);
        para.addEventListener('click', removeActiveTag);
        return para;
    };

    init();

})();