$('.addToBasket').on('click', () => {
    let id = $('.addToBasket').attr('data-id');
    let url = $('.addToBasket').attr('data-url');
    let sizes = document.getElementsByName('size');
    let size;      
    for(i = 0; i < sizes.length; i++) {
        if(sizes[i].checked) {
            size = sizes[i].value;
        }
    }
    $.ajax({
        method: "POST",
        url: url,
        data: { id : id, size : size }
    })
    .done(function(data) {
        if(data.itemInsert) {
            const myModalInsert = new bootstrap.Modal(document.getElementById("modalInsert"), {});
            myModalInsert.show();
        }
        else {
            const myModalNoInsert = new bootstrap.Modal(document.getElementById("modalNoInsert"), {});
            myModalNoInsert.show();
        }
    });
})
