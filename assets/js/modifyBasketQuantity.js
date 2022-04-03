$('.buttonModifyQuantityUp').on('click', (e) => {
    let self = e.target;
    let nodeName = self.nodeName;
    if(nodeName === 'I') {
        self = self.parentNode;
    }
    let url = self.getAttribute('data-url');
    $.ajax({
        method: "PUT",
        url: url
    })
    .done(function(elements) {
        let total = 0;
        elements.panier.forEach(item => {
            $(`#quantity${item.id}-${item.size}`).val(item.quantity); 
            $(`#price${item.id}-${item.size}`).text(item.price + '€');
            total += item.price;
        });
        $('.total-basket').text('Total : ' + total + '€');
    });
})


$('.buttonModifyQuantityDown').on('click', (e) => {
    let self = e.target;
    let nodeName = self.nodeName;
    if(nodeName === 'I') {
        self = self.parentNode;
    }
    let url = self.getAttribute('data-url');
    $.ajax({
        method: "PUT",
        url: url
    })
    .done(function(elements) {
        let total = 0;
        elements.panier.forEach(item => {
            $(`#quantity${item.id}-${item.size}`).val(item.quantity); 
            $(`#price${item.id}-${item.size}`).text(item.price + '€'); 
            total += item.price;
        });
        elements.removeElements.forEach(item => {
            $(`#card${item.id}-${item.size}`).remove(); 
        });
        if(elements.panier.length == 0) {
            $(`.card-btn-pay`).remove(); 
            $('.total-basket').remove();
            $('.basket-status').append('<h2 class="text-center text-danger">Votre panier est vide !</h2>');
        }
        else {
            $('.total-basket').text('Total : ' + total + '€');
        }
    });
})