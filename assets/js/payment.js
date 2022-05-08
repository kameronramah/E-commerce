const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

$('.btn-pay').on('click', (e) => {
    let self = e.target;
    let url = self.getAttribute('data-url');
    $.ajax({
        method: "POST",
        url: url
    })
    .done(function(data) {
        if(data.connected === false) {
            window.location.replace(Routing.generate('app_login'));
        }
        else {
            if(data.orderCreate) {
                const myModalOrder = new bootstrap.Modal($("#modalOrder"), {});
                myModalOrder.show();
                data.panier.forEach(item => {
                    $(`#card${item.id}-${item.size}`).remove(); 
                });
                $(`.card-btn-pay`).remove(); 
                $('.total-basket').remove();
                $('.basket-status').append('<h2 class="text-center text-danger">Votre panier est vide !</h2>');
            }
            else {
                const myModalNoOrder = new bootstrap.Modal($("#modalNoOrder"), {});
                myModalNoOrder.show();
            }
        }
    })
})