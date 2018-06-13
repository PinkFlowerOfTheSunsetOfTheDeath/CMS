(function () {

    var email = document.querySelector('.connection__emailCheck');


    function emailCheck () {
        email.value = "";
        var parent = email.parentNode;
        var error = parent.querySelector('.connection__error');

        email.addEventListener('blur', function () {
            if(validateEmail(email.value)) {
                parent.classList.remove('connection__field--active');
                if (error.innerText !== "" ){
                    error.innerText = "";
                }
            }
            else {
                error.innerText = 'Email address is not valid. Please enter a valid address.';
                parent.classList.add('connection__field--active');
            }
        });
    }

    function validateEmail(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(String(email).toLowerCase());
    }

    emailCheck();
})();