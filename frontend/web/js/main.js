$(document).ready(function () {
    let burger = document.getElementById("burger-button");

    burger.addEventListener("click", (e) => {
        e.preventDefault();
        document.body.classList.toggle("open");
        burger.classList.toggle("open");
    });

    $('.form-group.field-textarea').append('<div id="count-com">400</div>');

    //Ограничение символов

    $('textarea').keyup(function () {
        var re = /(http)|(www)/;
        var button = $('button.button');
        if (this.value.search(re) !== -1) {
            button.attr('disabled', true);
            button.css({
                'background-color': 'darkgray',
                'cursor': 'default'
            });
        } else {
            button.removeAttr('disabled');
            button.css({
                'background': 'none',
                'cursor': 'pointer'
            });
        }
    });

//счетчик символов

    $('textarea#textarea').keyup(function () {
        var box = $(this).val();
        var count = 400 - box.length;

        if (box.length <= 400) {
            $('#count-com').html(count);
        }
        return false;
    });

    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,

        autoHeightClass: 'owl-height',
        responsive: {
            0: {
                autoHeight: true,
                items: 1
            },
            600: {
                items: 2
            },
            1170: {
                items: 3
            }
        }
    })
});
